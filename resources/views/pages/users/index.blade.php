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

    #[Computed]
    public function stats(): array
    {
        return [
            ['title' => 'Total Users', 'value' => number_format(User::count()), 'trend' => '', 'trendUp' => true],
            ['title' => 'Active', 'value' => number_format(User::where('is_active', true)->count()), 'trend' => '', 'trendUp' => true],
            ['title' => 'Inactive', 'value' => number_format(User::where('is_active', false)->count()), 'trend' => '', 'trendUp' => false],
            ['title' => 'Branches', 'value' => number_format(User::distinct('branch_id')->count('branch_id')), 'trend' => '', 'trendUp' => true],
        ];
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

    <div class="flex gap-6 mb-6">
        @foreach ($this->stats as $stat)
            <div wire:key="stat-{{ $loop->index }}" class="relative flex-1 rounded-lg p-4 bg-zinc-50 dark:bg-zinc-700 {{ $loop->iteration > 1 ? 'max-md:hidden' : '' }} {{ $loop->iteration > 3 ? 'max-lg:hidden' : '' }}">
                <flux:subheading>{{ $stat['title'] }}</flux:subheading>
                <flux:heading size="xl" class="mb-2">{{ $stat['value'] }}</flux:heading>
                <div class="absolute top-0 right-0 mr-2 mt-2">
                    <flux:button icon="ellipsis-horizontal" variant="subtle" size="sm" />
                </div>
            </div>
        @endforeach
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search users..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column class="max-md:hidden">Email</flux:table.column>
            <flux:table.column class="max-md:hidden">Branch</flux:table.column>
            <flux:table.column>Roles</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->users as $user)
                <flux:table.row wire:key="user-{{ $user->id }}">
                    <flux:table.cell>
                        <div class="flex items-center gap-2">
                            <flux:avatar size="xs" />
                            <span>{{ $user->name }}</span>
                        </div>
                    </flux:table.cell>
                    <flux:table.cell class="max-md:hidden">{{ $user->email }}</flux:table.cell>
                    <flux:table.cell class="max-md:hidden">{{ $user->branch?->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>
                        <div class="flex gap-1 flex-wrap">
                            @foreach ($user->getRoleNames() as $role)
                                <flux:badge size="sm">{{ $role }}</flux:badge>
                            @endforeach
                        </div>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$user->is_active ? 'green' : 'zinc'" size="sm" inset="top bottom">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end" offset="-15">
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('users.edit', $user)" wire:navigate>Edit</flux:menu.item>
                                <flux:menu.item icon="power" wire:click="toggle({{ $user->id }})">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $user->id }})" wire:confirm="Delete this user?">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->users" />
</div>
