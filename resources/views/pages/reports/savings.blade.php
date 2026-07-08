<?php

use App\Models\Branch;
use App\Models\SavingsAccount;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $branch_id = null;
    public ?string $status = 'active';

    #[Computed]
    public function accounts()
    {
        return SavingsAccount::with(['client', 'product', 'branch'])
            ->when($this->branch_id, fn($q) => $q->where('branch_id', $this->branch_id))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function totalBalance()
    {
        return $this->accounts->sum('balance');
    }

    public function branches()
    {
        return Branch::orderBy('name')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Savings Report</flux:heading>
            <flux:subheading>Savings accounts summary</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('reports.index')" wire:navigate>Back to Reports</flux:button>
    </div>

    <div class="flex gap-4 mb-6">
        <flux:select wire:model.live="branch_id" class="w-48">
            <option value="">All Branches</option>
            @foreach ($this->branches() as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="status" class="w-48">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="dormant">Dormant</option>
            <option value="closed">Closed</option>
        </flux:select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <flux:card>
            <div><flux:heading size="sm">Accounts</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->accounts->count()) }}</flux:heading></div>
        </flux:card>
        <flux:card>
            <div><flux:heading size="sm">Total Balance</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->totalBalance, 2) }}</flux:heading></div>
        </flux:card>
        <flux:card>
            <div><flux:heading size="sm">Average Balance</flux:heading></div>
            <div>
                <flux:heading size="xl">
                    {{ $this->accounts->count() > 0 ? number_format($this->totalBalance / $this->accounts->count(), 2) : '0.00' }}
                </flux:heading>
            </div>
        </flux:card>
    </div>

    <flux:card>
        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Account #</flux:table.column>
                    <flux:table.column>Client</flux:table.column>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Balance</flux:table.column>
                    <flux:table.column>Branch</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->accounts as $account)
                        <flux:table.row :key="$account->id">
                            <flux:table.cell><flux:badge size="sm" variant="pill">{{ $account->account_number }}</flux:badge></flux:table.cell>
                            <flux:table.cell>{{ $account->client?->full_name }}</flux:table.cell>
                            <flux:table.cell>{{ $account->product?->name }}</flux:table.cell>
                            <flux:table.cell><flux:heading size="sm">{{ number_format($account->balance, 2) }}</flux:heading></flux:table.cell>
                            <flux:table.cell>{{ $account->branch?->name }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge :color="$account->status === 'active' ? 'green' : ($account->status === 'dormant' ? 'yellow' : 'zinc')">
                                    {{ ucfirst($account->status) }}
                                </flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
