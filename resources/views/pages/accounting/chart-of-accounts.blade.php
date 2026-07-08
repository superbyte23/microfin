<?php

use App\Models\ChartOfAccount;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $search = '';
    public ?string $type = null;

    #[Computed]
    public function accounts()
    {
        return ChartOfAccount::query()
            ->whereNull('parent_id')
            ->with('children')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->orderBy('code')
            ->get();
    }

    public function delete(ChartOfAccount $account): void
    {
        if ($account->children()->exists()) {
            Flux::toast('Cannot delete an account with sub-accounts.', variant: 'danger');
            return;
        }
        $account->delete();
        Flux::toast('Account deleted.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Chart of Accounts</flux:heading>
            <flux:subheading>Manage your chart of accounts</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('accounting.chart-of-accounts.create')" wire:navigate>New Account</flux:button>
    </div>

    <div class="flex gap-4 mb-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="flex-1" />
        <flux:select wire:model.live="type" class="w-48">
            <option value="">All Types</option>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </flux:select>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Code</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Type</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->accounts as $account)
                <flux:table.row :key="$account->id">
                    <flux:table.cell><flux:badge size="sm" variant="pill">{{ $account->code }}</flux:badge></flux:table.cell>
                    <flux:table.cell>
                        <flux:heading size="sm">{{ $account->name }}</flux:heading>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$account->type === 'asset' ? 'green' : ($account->type === 'liability' ? 'yellow' : ($account->type === 'equity' ? 'blue' : ($account->type === 'income' ? 'purple' : 'orange')))">
                            {{ ucfirst($account->type) }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$account->is_active ? 'green' : 'zinc'">{{ $account->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('accounting.chart-of-accounts.edit', $account)" wire:navigate>Edit</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $account->id }})" wire:confirm="Delete this account?">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
                @foreach ($account->children as $child)
                    <flux:table.row :key="$child->id" class="bg-zinc-50 dark:bg-zinc-800/50">
                        <flux:table.cell><flux:text class="ml-6">{{ $child->code }}</flux:text></flux:table.cell>
                        <flux:table.cell><flux:text class="ml-6">{{ $child->name }}</flux:text></flux:table.cell>
                        <flux:table.cell></flux:table.cell>
                        <flux:table.cell>
                            <flux:badge :color="$child->is_active ? 'green' : 'zinc'">{{ $child->is_active ? 'Active' : 'Inactive' }}</flux:badge>
                        </flux:table.cell>
                        <flux:table.cell>
                            <flux:dropdown position="bottom" align="end">
                                <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                                <flux:menu>
                                    <flux:menu.item icon="pencil" :href="route('accounting.chart-of-accounts.edit', $child)" wire:navigate>Edit</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $child->id }})" wire:confirm="Delete this account?">Delete</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </flux:table.cell>
                    </flux:table.row>
                @endforeach
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
