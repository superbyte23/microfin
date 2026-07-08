<?php

use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

new #[Layout('layouts::app')] class extends Component {
    public string $name = '';
    public array $selectedPermissions = [];

    public function mount(): void
    {
        $this->selectedPermissions = [];
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'selectedPermissions' => 'array',
        ]);

        $role = Role::create(['name' => $validated['name'], 'guard_name' => 'web']);
        $role->syncPermissions($validated['selectedPermissions']);

        Flux::toast('Role created successfully.', variant: 'success');

        $this->redirect(route('roles.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Role</flux:heading>
            <flux:subheading>Create a new role with permissions</flux:subheading>
        </div>
    </div>

    <flux:card class="max-w-2xl">
        <form wire:submit="save" class="space-y-6">
            <flux:input wire:model="name" label="Role Name" required placeholder="e.g. Loan Officer" />

            <div>
                <flux:label>Permissions</flux:label>
                <flux:text class="mb-3">Select the permissions for this role</flux:text>

                @php
                    $grouped = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);
                @endphp

                <div class="space-y-3">
                    @foreach ($grouped as $group => $permissions)
                        <flux:card class="!p-3">
                            <flux:heading size="sm" class="capitalize mb-2">{{ str_replace('-', ' ', $group) }}</flux:heading>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach ($permissions as $permission)
                                    <flux:checkbox
                                        wire:model="selectedPermissions"
                                        value="{{ $permission->name }}"
                                        label="{{ $permission->name }}"
                                    />
                                @endforeach
                            </div>
                        </flux:card>
                    @endforeach
                </div>
                <flux:error name="selectedPermissions" />
            </div>

            <div class="flex gap-2 justify-end">
                <flux:button :href="route('roles.index')" variant="ghost" wire:navigate>Cancel</flux:button>
                <flux:button type="submit" variant="primary">Save</flux:button>
            </div>
        </form>
    </flux:card>
</div>
