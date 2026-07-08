<?php

use App\Models\SavingsAccount;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public SavingsAccount $account;

    public string $transaction_type = 'deposit';
    public string $amount = '';
    public string $notes = '';

    #[Computed]
    public function transactions()
    {
        return $this->account->transactions()->with('performedBy')->latest()->get();
    }

    public function saveTransaction(): void
    {
        $data = $this->validate([
            'transaction_type' => 'required|in:deposit,withdrawal',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $balanceBefore = (float) $this->account->balance;
        $amount = (float) $this->amount;

        if ($this->transaction_type === 'withdrawal' && $amount > $balanceBefore) {
            $this->addError('amount', 'Insufficient balance.');
            return;
        }

        $newBalance = $this->transaction_type === 'deposit'
            ? $balanceBefore + $amount
            : $balanceBefore - $amount;

        $this->account->transactions()->create([
            'type' => $this->transaction_type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $newBalance,
            'performed_by' => auth()->id(),
            'notes' => $this->notes,
        ]);

        $this->account->update(['balance' => $newBalance]);

        $this->reset('amount', 'notes');

        Flux::toast(ucfirst($this->transaction_type) . ' recorded.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">{{ $account->client->full_name }}</flux:heading>
            <flux:subheading>Account #{{ $account->account_number }} · {{ $account->product?->name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('savings.index')" wire:navigate>Back</flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <flux:card>
            <flux:card.header><flux:heading size="sm">Balance</flux:heading></flux:card.header>
            <flux:card.content>
                <flux:heading size="xl">{{ number_format($account->balance, 2) }}</flux:heading>
            </flux:card.content>
        </flux:card>
        <flux:card>
            <flux:card.header><flux:heading size="sm">Status</flux:heading></flux:card.header>
            <flux:card.content>
                <flux:badge size="lg" :color="$account->status === 'active' ? 'green' : 'zinc'">{{ ucfirst($account->status) }}</flux:badge>
            </flux:card.content>
        </flux:card>
        <flux:card>
            <flux:card.header><flux:heading size="sm">Branch</flux:heading></flux:card.header>
            <flux:card.content>
                <flux:heading size="sm">{{ $account->branch?->name }}</flux:heading>
            </flux:card.content>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <flux:card>
            <flux:card.header>
                <flux:heading>New Transaction</flux:heading>
            </flux:card.header>
            <flux:card.content class="space-y-4">
                <flux:select wire:model="transaction_type" label="Type">
                    <option value="deposit">Deposit</option>
                    <option value="withdrawal">Withdrawal</option>
                </flux:select>
                <flux:input wire:model="amount" label="Amount" type="number" step="0.01" required />
                <flux:textarea wire:model="notes" label="Notes" />
                <flux:button variant="primary" wire:click="saveTransaction" class="w-full">Save Transaction</flux:button>
            </flux:card.content>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading>Transaction History</flux:heading>
            </flux:card.header>
            <flux:card.content>
                @if($this->transactions->isEmpty())
                    <flux:text>No transactions yet.</flux:text>
                @else
                    <div class="space-y-3">
                        @foreach($this->transactions as $txn)
                            <div class="flex justify-between items-center py-1 border-b border-zinc-100 dark:border-zinc-800">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <flux:badge size="sm" :color="$txn->type === 'deposit' ? 'green' : 'red'">
                                            {{ ucfirst($txn->type) }}
                                        </flux:badge>
                                        <flux:text>{{ $txn->created_at->format('M d, Y h:i A') }}</flux:text>
                                    </div>
                                    @if($txn->notes)
                                        <flux:text class="text-xs">{{ $txn->notes }}</flux:text>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <flux:heading size="sm" :class="$txn->type === 'deposit' ? 'text-green-600' : 'text-red-600'">
                                        {{ $txn->type === 'deposit' ? '+' : '-' }}{{ number_format($txn->amount, 2) }}
                                    </flux:heading>
                                    <flux:text class="text-xs">Balance: {{ number_format($txn->balance_after, 2) }}</flux:text>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </flux:card.content>
        </flux:card>
    </div>
</div>
