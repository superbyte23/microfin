<?php

use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Role;

new #[Layout('layouts::app')] class extends Component {
    public Role $role;

    public string $name = '';
    public array $selectedPermissions = [];

    public function mount(): void
    {
        $this->name = $this->role->name;
        $this->selectedPermissions = $this->role->permissions->pluck('name')->toArray();
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $this->role->id,
            'selectedPermissions' => 'array',
        ]);

        $this->role->update(['name' => $validated['name']]);
        $this->role->syncPermissions($validated['selectedPermissions']);

        Flux::toast('Role updated successfully.', variant: 'success');

        $this->redirect(route('roles.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Role</flux:heading>
            <flux:subheading>{{ $role->name }}</flux:subheading>
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
            @include('pages.roles._form')

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('roles.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Update</flux:button>
            </div>
        </form>
    </flux:card>
</div>
