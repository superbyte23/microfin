<?php

use App\Models\Branch;
use App\Models\User;
use Flux\Flux;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $branch_id = '';
    public string $role = '';

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'branch_id' => 'nullable|exists:branches,id',
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'branch_id' => $validated['branch_id'] ?: null,
            'is_active' => true,
        ]);

        $user->assignRole($validated['role']);

        Flux::toast('User created successfully.', variant: 'success');

        $this->redirect(route('users.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New User</flux:heading>
            <flux:subheading>Create a new system user</flux:subheading>
        </div>
    </div>

    <flux:card class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Full Name" required />

            <flux:input wire:model="email" label="Email Address" type="email" required />

            <div class="grid grid-cols-2 gap-4">
                <flux:input wire:model="password" label="Password" type="password" required />
                <flux:input wire:model="password_confirmation" label="Confirm Password" type="password" required />
            </div>

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

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('users.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:card>
</div>
