<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Account Code</flux:label>
        <flux:input wire:model="code" required hint="e.g. 1050" />
    </flux:field>

    <flux:field>
        <flux:label>Account Name</flux:label>
        <flux:input wire:model="name" required />
    </flux:field>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <flux:field>
        <flux:label>Type</flux:label>
        <flux:select wire:model="type" required>
            <option value="asset">Asset</option>
            <option value="liability">Liability</option>
            <option value="equity">Equity</option>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </flux:select>
    </flux:field>

    <flux:field>
        <flux:label>Parent Account</flux:label>
        <flux:select wire:model="parent_id">
            <option value="">None (Top Level)</option>
            @foreach ($this->parentAccounts() as $account)
                <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
            @endforeach
        </flux:select>
    </flux:field>
</div>

<flux:field>
    <flux:label>Active</flux:label>
    <flux:checkbox wire:model="is_active" />
</flux:field>

<flux:field>
    <flux:label>Description</flux:label>
    <flux:textarea wire:model="description" />
</flux:field>
