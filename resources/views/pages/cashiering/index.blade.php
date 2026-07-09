<?php

use App\Models\CashSession;
use App\Models\Branch;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $opening_balance = '0';
    public string $cash_in_amount = '';
    public string $cash_out_amount = '';
    public string $cash_in_category = 'collection';
    public string $cash_out_category = 'expense';
    public string $cash_in_notes = '';
    public string $cash_out_notes = '';
    public string $closing_note = '';

    #[Computed]
    public function activeSession()
    {
        return CashSession::query()
            ->where('user_id', auth()->id())
            ->where('status', 'open')
            ->with('transactions')
            ->first();
    }

    #[Computed]
    public function todaySessions()
    {
        return CashSession::query()
            ->where('user_id', auth()->id())
            ->whereDate('created_at', today())
            ->latest()
            ->get();
    }

    #[Computed]
    public function allSessions()
    {
        return CashSession::with('branch')
            ->when(auth()->user()->branch_id, fn($q) => $q->where('branch_id', auth()->user()->branch_id))
            ->latest()
            ->paginate(15);
    }

    public function openSession(): void
    {
        if ($this->activeSession) {
            Flux::toast('You already have an open session.', variant: 'warning');
            return;
        }

        $this->validate(['opening_balance' => 'required|numeric|min:0']);

        CashSession::create([
            'user_id' => auth()->id(),
            'branch_id' => auth()->user()->branch_id,
            'session_date' => now()->toDateString(),
            'opening_balance' => $this->opening_balance,
            'opened_at' => now(),
            'status' => 'open',
        ]);

        $this->reset('opening_balance');
        Flux::toast('Cash session opened.', variant: 'success');
    }

    public function recordCashIn(): void
    {
        $data = $this->validate([
            'cash_in_amount' => 'required|numeric|min:0.01',
            'cash_in_category' => 'required|string',
            'cash_in_notes' => 'nullable|string',
        ]);

        $session = $this->activeSession;

        $balanceBefore = (float) $session->total_cash_in - (float) $session->total_cash_out + (float) $session->opening_balance;
        $amount = (float) $this->cash_in_amount;

        $session->transactions()->create([
            'type' => 'cash_in',
            'category' => $this->cash_in_category,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore + $amount,
            'notes' => $this->cash_in_notes,
        ]);

        $session->increment('total_cash_in', $amount);

        $this->reset('cash_in_amount', 'cash_in_notes');
        Flux::toast('Cash in recorded.', variant: 'success');
    }

    public function recordCashOut(): void
    {
        $data = $this->validate([
            'cash_out_amount' => 'required|numeric|min:0.01',
            'cash_out_category' => 'required|string',
            'cash_out_notes' => 'nullable|string',
        ]);

        $session = $this->activeSession;

        $balanceBefore = (float) $session->total_cash_in - (float) $session->total_cash_out + (float) $session->opening_balance;
        $amount = (float) $this->cash_out_amount;

        if ($amount > $balanceBefore) {
            $this->addError('cash_out_amount', 'Insufficient cash balance.');
            return;
        }

        $session->transactions()->create([
            'type' => 'cash_out',
            'category' => $this->cash_out_category,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceBefore - $amount,
            'notes' => $this->cash_out_notes,
        ]);

        $session->increment('total_cash_out', $amount);

        $this->reset('cash_out_amount', 'cash_out_notes');
        Flux::toast('Cash out recorded.', variant: 'success');
        }

    public function closeSession(): void
    {
        $session = $this->activeSession;

        $closingBalance = (float) $session->opening_balance + (float) $session->total_cash_in - (float) $session->total_cash_out;

        $session->update([
            'closing_balance' => $closingBalance,
            'closed_at' => now(),
            'status' => 'closed',
            'notes' => $this->closing_note,
        ]);

        $this->reset('closing_note');
        Flux::toast('Cash session closed. Closing balance: ' . number_format($closingBalance, 2), variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Cashiering</flux:heading>
            <flux:subheading>Manage daily cash sessions</flux:subheading>
        </div>
    </div>

    @if ($this->activeSession)
        @php $session = $this->activeSession; @endphp
        @php $currentBalance = (float) $session->opening_balance + (float) $session->total_cash_in - (float) $session->total_cash_out; @endphp

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <flux:card>
                <div><flux:heading size="sm">Opening</flux:heading></div>
                <div><flux:heading>{{ number_format($session->opening_balance, 2) }}</flux:heading></div>
            </flux:card>
            <flux:card>
                <div><flux:heading size="sm">Cash In</flux:heading></div>
                <div class="text-green-600"><flux:heading>+{{ number_format($session->total_cash_in, 2) }}</flux:heading></div>
            </flux:card>
            <flux:card>
                <div><flux:heading size="sm">Cash Out</flux:heading></div>
                <div class="text-red-600"><flux:heading>-{{ number_format($session->total_cash_out, 2) }}</flux:heading></div>
            </flux:card>
            <flux:card>
                <div><flux:heading size="sm">Current Balance</flux:heading></div>
                <div><flux:heading size="xl">{{ number_format($currentBalance, 2) }}</flux:heading></div>
            </flux:card>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <flux:card>
                <div>
                    <flux:heading>Cash In</flux:heading>
                </div>
                <div class="space-y-4">
                    <flux:input wire:model="cash_in_amount" label="Amount" type="number" step="0.01" required />
                    <flux:select wire:model="cash_in_category" label="Category">
                        <option value="collection">Collection</option>
                        <option value="deposit">Deposit</option>
                        <option value="transfer">Transfer In</option>
                        <option value="other">Other</option>
                    </flux:select>
                    <flux:textarea wire:model="cash_in_notes" label="Notes" />
                    <flux:button variant="primary" wire:click="recordCashIn" class="w-full">Record Cash In</flux:button>
                </div>
            </flux:card>

            <flux:card>
                <div>
                    <flux:heading>Cash Out</flux:heading>
                </div>
                <div class="space-y-4">
                    <flux:input wire:model="cash_out_amount" label="Amount" type="number" step="0.01" required />
                    <flux:select wire:model="cash_out_category" label="Category">
                        <option value="expense">Expense</option>
                        <option value="disbursement">Loan Disbursement</option>
                        <option value="withdrawal">Withdrawal</option>
                        <option value="transfer">Transfer Out</option>
                        <option value="other">Other</option>
                    </flux:select>
                    <flux:textarea wire:model="cash_out_notes" label="Notes" />
                    <flux:button variant="primary" wire:click="recordCashOut" class="w-full">Record Cash Out</flux:button>
                </div>
            </flux:card>
        </div>

        <flux:card class="mb-6">
            <div>
                <flux:heading>Session Transactions</flux:heading>
            </div>
            <div>
                @if($session->transactions->isEmpty())
                    <flux:text>No transactions yet.</flux:text>
                @else
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Time</flux:table.column>
                            <flux:table.column>Type</flux:table.column>
                            <flux:table.column>Category</flux:table.column>
                            <flux:table.column>Amount</flux:table.column>
                            <flux:table.column>Balance</flux:table.column>
                            <flux:table.column>Notes</flux:table.column>
                        </flux:table.columns>
                        <flux:table.rows>
                            @foreach($session->transactions as $txn)
                                <flux:table.row :key="$txn->id">
                                    <flux:table.cell>{{ $txn->created_at->format('h:i A') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <flux:badge size="sm" :color="$txn->type === 'cash_in' ? 'green' : 'red'">
                                            {{ $txn->type === 'cash_in' ? 'IN' : 'OUT' }}
                                        </flux:badge>
                                    </flux:table.cell>
                                    <flux:table.cell>{{ ucfirst($txn->category) }}</flux:table.cell>
                                    <flux:table.cell>{{ number_format($txn->amount, 2) }}</flux:table.cell>
                                    <flux:table.cell>{{ number_format($txn->balance_after, 2) }}</flux:table.cell>
                                    <flux:table.cell><flux:text class="text-xs">{{ $txn->notes ?? '—' }}</flux:text></flux:table.cell>
                                </flux:table.row>
                            @endforeach
                        </flux:table.rows>
                    </flux:table>
                @endif
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading>Close Session</flux:heading>
            </div>
            <div class="space-y-4">
                <flux:textarea wire:model="closing_note" label="Closing Notes" />
                <flux:button variant="danger" wire:click="closeSession" wire:confirm="Close this cash session?">Close Session</flux:button>
            </div>
        </flux:card>
    @else
        <flux:card class="mb-6">
            <div>
                <flux:heading>Open Cash Session</flux:heading>
                <flux:subheading>Start your daily cash session</flux:subheading>
            </div>
            <div class="space-y-4">
                <flux:input wire:model="opening_balance" label="Opening Balance" type="number" step="0.01" required />
                <flux:button variant="primary" wire:click="openSession">Open Session</flux:button>
            </div>
        </flux:card>
    @endif

    <flux:card>
        <div>
            <flux:heading>Session History</flux:heading>
        </div>
        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Branch</flux:table.column>
                    <flux:table.column>Opening</flux:table.column>
                    <flux:table.column>Cash In</flux:table.column>
                    <flux:table.column>Cash Out</flux:table.column>
                    <flux:table.column>Closing</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->allSessions as $s)
                        <flux:table.row wire:key="session-{{ $s->id }}">
                            <flux:table.cell>{{ $s->session_date?->format('M d, Y') ?? $s->created_at->format('M d, Y') }}</flux:table.cell>
                            <flux:table.cell>{{ $s->branch?->name }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->opening_balance, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->total_cash_in, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($s->total_cash_out, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ $s->closing_balance ? number_format($s->closing_balance, 2) : '—' }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge :color="$s->status === 'open' ? 'green' : 'zinc'">{{ ucfirst($s->status) }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
            <flux:pagination :paginator="$this->allSessions" />
        </div>
    </flux:card>
</div>
