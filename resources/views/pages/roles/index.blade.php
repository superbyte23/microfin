<?php

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Flux\Flux;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function roles()
    {
        return Role::query()
            ->with('permissions')
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }

    public function delete(Role $role): void
    {
        if ($role->users()->count() > 0) {
            Flux::toast('Cannot delete role with assigned users.', variant: 'danger');
            return;
        }
        $role->delete();
        Flux::toast('Role deleted successfully.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Roles & Permissions</flux:heading>
            <flux:subheading>Manage user roles and their permissions</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('roles.create')" wire:navigate>New Role</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search roles..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Role</flux:table.column>
            <flux:table.column>Permissions</flux:table.column>
            <flux:table.column>Users</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->roles as $role)
                <flux:table.row :key="$role->id">
                    <flux:table.cell>
                        <flux:heading size="sm">{{ $role->name }}</flux:heading>
                    </flux:table.cell>
                    <flux:table.cell>
                        <div class="flex gap-1 flex-wrap max-w-md">
                            @foreach ($role->permissions as $permission)
                                <flux:badge size="sm" color="indigo">{{ $permission->name }}</flux:badge>
                            @endforeach
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>{{ $role->users()->count() }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('roles.edit', $role)" wire:navigate>
                                    Edit
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $role->id }})" wire:confirm="Delete this role?">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    {{ $this->roles->links() }}
</div>
