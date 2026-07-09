<flux:field>
    <flux:label>Full Name</flux:label>
    <flux:input wire:model="name" required />
</flux:field>

<flux:field>
    <flux:label>Email Address</flux:label>
    <flux:input wire:model="email" type="email" required />
</flux:field>

<flux:field>
    <flux:label>Branch</flux:label>
    <flux:select wire:model="branch_id" placeholder="Select branch">
        @foreach (App\Models\Branch::where('is_active', true)->get() as $branch)
            <flux:select.option value="{{ $branch->id }}">{{ $branch->name }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>

<flux:field>
    <flux:label>Role</flux:label>
    <flux:select wire:model="role" placeholder="Select role" required>
        @foreach (Spatie\Permission\Models\Role::all() as $role)
            <flux:select.option value="{{ $role->name }}">{{ $role->name }}</flux:select.option>
        @endforeach
    </flux:select>
</flux:field>
