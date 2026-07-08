<?php

use App\Models\Branch;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
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
        $this->name = $this->user->name;
        $this->email = $this->user->email;
        $this->branch_id = $this->user->branch_id ?? '';
        $this->is_active = $this->user->is_active;
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

    <flux:card class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Full Name" required />

            <flux:input wire:model="email" label="Email Address" type="email" required />

            <flux:select wire:model="branch_id" label="Branch" placeholder="Select branch">
                @foreach (Branch::where('is_active', true)->get() as $branch)
                    <flux:select.option value="{{ $branch->id }}">{{ $branch->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select wire:model="role" label="Role" placeholder="Select role" required>
                @foreach (Spatie\Permission\Models\Role::all() as $role)
                    <flux:select.option value="{{ $role->name }}">{{ $role->name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:switch wire:model="is_active" label="Active" />

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('users.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:card>
</div>
