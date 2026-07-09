<?php

use App\Models\SavingsAccount;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function accounts()
    {
        return SavingsAccount::query()
            ->with(['client', 'product', 'branch'])
            ->when($this->search, fn($q) => $q->whereHas('client', fn($q) => $q->whereAny(['first_name', 'last_name'], 'like', "%{$this->search}%"))
                ->orWhere('account_number', 'like', "%{$this->search}%"))
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Savings Accounts</flux:heading>
            <flux:subheading>Manage client savings accounts</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('savings.create')" wire:navigate>New Account</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by client or account number..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Account #</flux:table.column>
            <flux:table.column>Client</flux:table.column>
            <flux:table.column>Product</flux:table.column>
            <flux:table.column>Balance</flux:table.column>
            <flux:table.column>Branch</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->accounts as $account)
                <flux:table.row wire:key="account-{{ $account->id }}">
                    <flux:table.cell><flux:badge size="sm" variant="pill">{{ $account->account_number }}</flux:badge></flux:table.cell>
                    <flux:table.cell>{{ $account->client->full_name }}</flux:table.cell>
                    <flux:table.cell>{{ $account->product?->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:heading size="sm">{{ number_format($account->balance, 2) }}</flux:heading>
                    </flux:table.cell>
                    <flux:table.cell>{{ $account->branch?->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$account->status === 'active' ? 'green' : ($account->status === 'dormant' ? 'yellow' : 'zinc')">
                            {{ ucfirst($account->status) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="eye" size="sm" variant="ghost" :href="route('savings.show', $account)" wire:navigate />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->accounts" />
</div>
