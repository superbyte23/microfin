<?php

use App\Models\User;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public User $user;

    public string $name = '';
    public string $email = '';
    public string $branch_id = '';
    public string $role = '';
    public bool $is_active = true;

    public function mount(): void
    {
        $this->fill($this->user->only(['name', 'email']));
        $this->branch_id = $this->user->branch_id ?? '';
        $this->is_active = (bool) $this->user->is_active;
        $this->role = $this->user->getRoleNames()->first() ?? '';
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|exists:roles,name',
            'is_active' => 'boolean',
        ]);

        $this->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'branch_id' => $validated['branch_id'] ?: null,
            'is_active' => $validated['is_active'],
        ]);

        $this->user->syncRoles([$validated['role']]);

        Flux::toast('User updated successfully.', variant: 'success');

        $this->redirect(route('users.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit User</flux:heading>
            <flux:subheading>{{ $user->name }}</flux:subheading>
        </div>
    </div>

    @if ($errors->any())
        <flux:card class="border-red-300 bg-red-50 dark:bg-red-950">
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </flux:card>
    @endif

    <flux:card class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            @include('pages.users._form')

            <flux:field>
                <flux:label>Active</flux:label>
                <flux:switch wire:model="is_active" />
            </flux:field>

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('users.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:card>
</div>
