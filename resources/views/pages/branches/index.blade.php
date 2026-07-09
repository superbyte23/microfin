<?php

use App\Models\Branch;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function branches()
    {
        return Branch::query()
            ->when($this->search, fn($q) => $q->whereAny(['name', 'code', 'phone', 'email'], 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }

    public function delete(Branch $branch): void
    {
        $branch->delete();
        Flux::toast('Branch deleted successfully.', variant: 'success');
    }

    public function toggle(Branch $branch): void
    {
        $branch->update(['is_active' => !$branch->is_active]);
        Flux::toast('Branch status updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Branches</flux:heading>
            <flux:subheading>Manage branches and offices</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('branches.create')" wire:navigate>New Branch</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search branches..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Code</flux:table.column>
            <flux:table.column>Contact</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->branches as $branch)
                <flux:table.row wire:key="branch-{{ $branch->id }}">
                    <flux:table.cell>
                        <flux:heading size="sm">{{ $branch->name }}</flux:heading>
                        <flux:text>{{ $branch->address }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm">{{ $branch->code }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:text>{{ $branch->phone }}</flux:text>
                        <flux:text>{{ $branch->email }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$branch->is_active ? 'green' : 'zinc'">
                            {{ $branch->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />

                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('branches.edit', $branch)" wire:navigate>
                                    Edit
                                </flux:menu.item>
                                <flux:menu.item icon="power" wire:click="toggle({{ $branch->id }})">
                                    {{ $branch->is_active ? 'Deactivate' : 'Activate' }}
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $branch->id }})" wire:confirm="Delete this branch?">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->branches" />
</div>
