<flux:field>
    <flux:label>Product Name</flux:label>
    <flux:input wire:model="name" required />
</flux:field>

<flux:field>
    <flux:label>Code</flux:label>
    <flux:input wire:model="code" required hint="e.g. REGULAR" />
</flux:field>

<flux:field>
    <flux:label>Description</flux:label>
    <flux:textarea wire:model="description" />
</flux:field>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Annual Interest Rate (%)</flux:label>
        <flux:input wire:model="interest_rate" type="number" step="0.01" required />
    </flux:field>

    <flux:field>
        <flux:label>Minimum Balance</flux:label>
        <flux:input wire:model="min_balance" type="number" step="0.01" required />
    </flux:field>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Minimum Deposit</flux:label>
        <flux:input wire:model="min_deposit" type="number" step="0.01" required />
    </flux:field>

    <flux:field>
        <flux:label>Withdrawal Fee</flux:label>
        <flux:input wire:model="withdrawal_fee" type="number" step="0.01" required />
    </flux:field>
</div>

<flux:field>
    <flux:label>Status</flux:label>
    <flux:select wire:model="status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </flux:select>
</flux:field>
