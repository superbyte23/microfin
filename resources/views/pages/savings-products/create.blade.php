<?php

use App\Models\SavingsProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $name = '';
    public string $code = '';
    public ?string $description = null;
    public string $interest_rate = '0';
    public string $min_balance = '0';
    public string $min_deposit = '0';
    public string $withdrawal_fee = '0';
    public string $status = 'active';

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:savings_products,code',
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_balance' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'withdrawal_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        SavingsProduct::create($data);

        Flux::toast('Savings product created.', variant: 'success');

        $this->redirect(route('savings-products.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Savings Product</flux:heading>
            <flux:subheading>Create a new savings product type</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('savings-products.index')" wire:navigate>Back</flux:button>
    </div>

    <flux:card>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Product Name" required />
                <flux:input wire:model="code" label="Code" required hint="e.g. REGULAR" />
            </div>

            <flux:textarea wire:model="description" label="Description" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="interest_rate" label="Annual Interest Rate (%)" type="number" step="0.01" required />
                <flux:input wire:model="min_balance" label="Minimum Balance" type="number" step="0.01" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="min_deposit" label="Minimum Deposit" type="number" step="0.01" required />
                <flux:input wire:model="withdrawal_fee" label="Withdrawal Fee" type="number" step="0.01" required />
            </div>

            <flux:select wire:model="status" label="Status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>
        </div>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('savings-products.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Save Product</flux:button>
    </div>
</div>
