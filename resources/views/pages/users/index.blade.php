<?php

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Flux\Flux;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function users()
    {
        return User::query()
            ->with('branch')
            ->when($this->search, fn($q) => $q->whereAny(['name', 'email'], 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }

    public function toggle(User $user): void
    {
        $user->update(['is_active' => !$user->is_active]);
        Flux::toast('User status updated.', variant: 'success');
    }

    public function delete(User $user): void
    {
        if ($user->id === auth()->id()) {
            Flux::toast('You cannot delete yourself.', variant: 'danger');
            return;
        }
        $user->delete();
        Flux::toast('User deleted successfully.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Users</flux:heading>
            <flux:subheading>Manage system users</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('users.create')" wire:navigate>New User</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search users..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Email</flux:table.column>
            <flux:table.column>Branch</flux:table.column>
            <flux:table.column>Roles</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->users as $user)
                <flux:table.row :key="$user->id">
                    <flux:table.cell>{{ $user->name }}</flux:table.cell>
                    <flux:table.cell>{{ $user->email }}</flux:table.cell>
                    <flux:table.cell>{{ $user->branch?->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex gap-1 flex-wrap">
                            @foreach ($user->getRoleNames() as $role)
                                <flux:badge size="sm">{{ $role }}</flux:badge>
                            @endforeach
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$user->is_active ? 'green' : 'zinc'">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('users.edit', $user)" wire:navigate>
                                    Edit
                                </flux:menu.item>
                                <flux:menu.item icon="power" wire:click="toggle({{ $user->id }})">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $user->id }})" wire:confirm="Delete this user?">
                                    Delete
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    {{ $this->users->links() }}
</div>
