<?php

use App\Models\Loan;
use App\Models\LoanAmortizationSchedule;
use App\Models\LoanTransaction;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $search = '';
    public ?string $selectedLoanId = null;
    public ?string $paid_amount = null;
    public string $payment_method = 'cash';
    public ?string $reference_number = null;
    public string $notes = '';

    #[Computed]
    public function loans()
    {
        return Loan::query()
            ->with(['client', 'product', 'branch'])
            ->whereIn('status', ['active', 'approved'])
            ->when($this->search, fn($q) => $q->whereHas('client', fn($q) => $q->whereAny(['first_name', 'last_name', 'phone'], 'like', "%{$this->search}%")))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function selectedLoan()
    {
        if (!$this->selectedLoanId) {
            return null;
        }
        return Loan::with(['client', 'product', 'amortizationSchedule' => fn($q) => $q->orderBy('installment_number')])->find($this->selectedLoanId);
    }

    public function selectLoan(string $id): void
    {
        $this->selectedLoanId = $id;
        $this->reset('paid_amount', 'payment_method', 'reference_number', 'notes');
    }

    public function recordPayment(): void
    {
        $this->validate([
            'paid_amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,check,mobile_money',
            'reference_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $loan = $this->selectedLoan;

        if (!$loan || $loan->status === 'closed') {
            Flux::toast('Loan is closed.', variant: 'danger');
            return;
        }

        $amount = (float) $this->paid_amount;

        $txn = LoanTransaction::create([
            'loan_id' => $loan->id,
            'client_id' => $loan->client_id,
            'collected_by' => auth()->id(),
            'branch_id' => $loan->branch_id,
            'amount' => $amount,
            'payment_method' => $this->payment_method,
            'reference_number' => $this->reference_number,
            'notes' => $this->notes,
        ]);

        $remaining = $amount;
        $dueInstallments = $loan->amortizationSchedule()
            ->whereIn('status', ['pending', 'partial', 'overdue'])
            ->orderBy('installment_number')
            ->get();

        foreach ($dueInstallments as $inst) {
            if ($remaining <= 0) {
                break;
            }

            $due = (float) $inst->total_due;
            $paid = (float) $inst->paid_amount;
            $unpaid = $due - $paid;

            $applyAmount = min($remaining, $unpaid);
            $newPaid = $paid + $applyAmount;

            $inst->update([
                'paid_amount' => $newPaid,
                'paid_date' => now()->toDateString(),
                'status' => $newPaid >= $due ? 'paid' : 'partial',
            ]);

            $remaining -= $applyAmount;
        }

        if ($remaining > 0) {
            $loan->transactions()->create([
                'client_id' => $loan->client_id,
                'collected_by' => auth()->id(),
                'branch_id' => $loan->branch_id,
                'amount' => $remaining,
                'payment_method' => $this->payment_method,
                'notes' => 'Overpayment / advance payment',
            ]);
        }

        $totalPaid = (float) $loan->transactions()->sum('amount');
        $totalDue = (float) $loan->amortizationSchedule()->sum('total_due');

        if ($totalPaid >= $totalDue && $loan->amortizationSchedule()->where('status', '!=', 'paid')->count() === 0) {
            $loan->update(['status' => 'closed']);
        }

        $this->reset('paid_amount', 'reference_number', 'notes');

        Flux::toast('Payment recorded successfully.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Collections</flux:heading>
            <flux:subheading>Record loan payments</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <flux:card>
                <flux:card.header>
                    <flux:heading>Active Loans</flux:heading>
                    <flux:subheading>Select a loan to record payment</flux:subheading>
                </flux:card.header>
                <flux:card.content>
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search client..." icon="magnifying-glass" class="mb-4" />

                    <div class="space-y-2 max-h-96 overflow-y-auto">
                        @forelse ($this->loans as $loan)
                            <button wire:click="selectLoan('{{ $loan->id }}')" class="w-full text-left p-3 rounded-lg border transition-colors hover:bg-zinc-50 dark:hover:bg-zinc-800 {{ $selectedLoanId === $loan->id ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-zinc-200 dark:border-zinc-700' }}">
                                <div class="font-medium text-sm">{{ $loan->client->full_name }}</div>
                                <div class="flex justify-between text-xs mt-1">
                                    <flux:text>{{ $loan->product?->name }}</flux:text>
                                    <flux:text>{{ number_format($loan->principal_amount, 0) }}</flux:text>
                                </div>
                                <div class="mt-1">
                                    <flux:badge size="sm" :color="$loan->status === 'active' ? 'green' : 'blue'">{{ ucfirst($loan->status) }}</flux:badge>
                                </div>
                            </button>
                        @empty
                            <flux:text>No active loans found.</flux:text>
                        @endforelse
                    </div>
                </flux:card.content>
            </flux:card>
        </div>

        <div class="lg:col-span-2">
            @if ($this->selectedLoan)
                @php $loan = $this->selectedLoan; @endphp

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <flux:card>
                        <flux:card.header><flux:heading size="sm">Principal</flux:heading></flux:card.header>
                        <flux:card.content><flux:heading>{{ number_format($loan->principal_amount, 2) }}</flux:heading></flux:card.content>
                    </flux:card>
                    <flux:card>
                        <flux:card.header><flux:heading size="sm">Total Paid</flux:heading></flux:card.header>
                        <flux:card.content><flux:heading>{{ number_format($loan->transactions()->sum('amount'), 2) }}</flux:heading></flux:card.content>
                    </flux:card>
                    <flux:card>
                        <flux:card.header><flux:heading size="sm">Balance</flux:heading></flux:card.header>
                        <flux:card.content>
                            @php
                                $totalDue = $loan->amortizationSchedule()->sum('total_due');
                                $totalPaid = $loan->amortizationSchedule()->sum('paid_amount');
                            @endphp
                            <flux:heading>{{ number_format(max($totalDue - $totalPaid, 0), 2) }}</flux:heading>
                        </flux:card.content>
                    </flux:card>
                </div>

                <flux:card class="mb-6">
                    <flux:card.header>
                        <flux:heading>Record Payment</flux:heading>
                    </flux:card.header>
                    <flux:card.content class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <flux:input wire:model="paid_amount" label="Amount" type="number" step="0.01" required placeholder="0.00" />
                            <flux:select wire:model="payment_method" label="Payment Method" required>
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="check">Check</option>
                                <option value="mobile_money">Mobile Money</option>
                            </flux:select>
                            <flux:input wire:model="reference_number" label="Reference #" />
                        </div>
                        <flux:textarea wire:model="notes" label="Notes" />
                        <flux:button variant="primary" wire:click="recordPayment" class="w-full">Record Payment</flux:button>
                    </flux:card.content>
                </flux:card>

                <flux:card>
                    <flux:card.header>
                        <flux:heading>Amortization Schedule</flux:heading>
                    </flux:card.header>
                    <flux:card.content>
                        <flux:table>
                            <flux:table.columns>
                                <flux:table.column>#</flux:table.column>
                                <flux:table.column>Due</flux:table.column>
                                <flux:table.column>Principal</flux:table.column>
                                <flux:table.column>Interest</flux:table.column>
                                <flux:table.column>Total</flux:table.column>
                                <flux:table.column>Paid</flux:table.column>
                                <flux:table.column>Status</flux:table.column>
                            </flux:table.columns>
                            <flux:table.rows>
                                @foreach($loan->amortizationSchedule as $inst)
                                    <flux:table.row :key="$inst->id">
                                        <flux:table.cell>{{ $inst->installment_number }}</flux:table.cell>
                                        <flux:table.cell>{{ $inst->due_date->format('M d') }}</flux:table.cell>
                                        <flux:table.cell>{{ number_format($inst->principal, 2) }}</flux:table.cell>
                                        <flux:table.cell>{{ number_format($inst->interest, 2) }}</flux:table.cell>
                                        <flux:table.cell>{{ number_format($inst->total_due, 2) }}</flux:table.cell>
                                        <flux:table.cell>{{ number_format($inst->paid_amount, 2) }}</flux:table.cell>
                                        <flux:table.cell>
                                            @php $sc = ['pending' => 'yellow', 'paid' => 'green', 'partial' => 'blue', 'overdue' => 'red']; @endphp
                                            <flux:badge size="sm" :color="$sc[$inst->status] ?? 'zinc'">{{ ucfirst($inst->status) }}</flux:badge>
                                        </flux:table.cell>
                                    </flux:table.row>
                                @endforeach
                            </flux:table.rows>
                        </flux:table>
                    </flux:card.content>
                </flux:card>
            @else
                <flux:card>
                    <flux:card.content class="text-center py-12">
                        <flux:heading>Select a loan from the left panel</flux:heading>
                        <flux:subheading>Search for an active loan to record payments</flux:subheading>
                    </flux:card.content>
                </flux:card>
            @endif
        </div>
    </div>
</div>
