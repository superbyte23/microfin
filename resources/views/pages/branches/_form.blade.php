<div class="grid grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Branch Name</flux:label>
        <flux:input wire:model="name" required />
    </flux:field>

    <flux:field>
        <flux:label>Branch Code</flux:label>
        <flux:input wire:model="code" required hint="Unique identifier" />
    </flux:field>
</div>

<flux:field>
    <flux:label>Address</flux:label>
    <flux:textarea wire:model="address" />
</flux:field>

<div class="grid grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Phone</flux:label>
        <flux:input wire:model="phone" type="tel" />
    </flux:field>

    <flux:field>
        <flux:label>Email</flux:label>
        <flux:input wire:model="email" type="email" />
    </flux:field>
</div>

<flux:field>
    <flux:label>Active</flux:label>
    <flux:switch wire:model="is_active" />
</flux:field>
