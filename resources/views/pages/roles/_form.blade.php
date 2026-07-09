<flux:field>
    <flux:label>Role Name</flux:label>
    <flux:input wire:model="name" required placeholder="e.g. Loan Officer" />
</flux:field>

<div>
    <flux:label>Permissions</flux:label>
    <flux:text class="mb-3">Select the permissions for this role</flux:text>

    @php
        $grouped = Spatie\Permission\Models\Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0]);
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
