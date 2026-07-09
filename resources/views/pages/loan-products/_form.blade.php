<flux:field>
    <flux:label>Product Name</flux:label>
    <flux:input wire:model="name" required />
</flux:field>

<flux:field>
    <flux:label>Code</flux:label>
    <flux:input wire:model="code" required hint="Short identifier, e.g. MEL" />
</flux:field>

<flux:field>
    <flux:label>Description</flux:label>
    <flux:textarea wire:model="description" />
</flux:field>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <flux:field>
        <flux:label>Interest Type</flux:label>
        <flux:select wire:model="interest_type" required>
            <option value="flat">Flat Rate</option>
            <option value="declining">Declining Balance</option>
            <option value="add-on">Add-on Rate</option>
        </flux:select>
    </flux:field>

    <flux:field>
        <flux:label>Interest Rate (%)</flux:label>
        <flux:input wire:model="interest_rate" type="number" step="0.01" required />
    </flux:field>

    <flux:field>
        <flux:label>Payment Frequency</flux:label>
        <flux:select wire:model="payment_frequency" required>
            <option value="monthly">Monthly</option>
            <option value="weekly">Weekly</option>
            <option value="bi-monthly">Bi-monthly</option>
            <option value="daily">Daily</option>
        </flux:select>
    </flux:field>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <flux:field>
        <flux:label>Min Amount</flux:label>
        <flux:input wire:model="min_amount" type="number" step="0.01" required />
    </flux:field>

    <flux:field>
        <flux:label>Max Amount</flux:label>
        <flux:input wire:model="max_amount" type="number" step="0.01" />
    </flux:field>

    <flux:field>
        <flux:label>Default Term (months)</flux:label>
        <flux:input wire:model="default_term_months" type="number" required />
    </flux:field>
</div>

<flux:field>
    <flux:label>Status</flux:label>
    <flux:select wire:model="status" required>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </flux:select>
</flux:field>
