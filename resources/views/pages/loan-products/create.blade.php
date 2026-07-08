<?php

use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $name = '';
    public string $code = '';
    public ?string $description = null;
    public string $interest_type = 'flat';
    public string $interest_rate = '';
    public string $min_amount = '';
    public ?string $max_amount = null;
    public string $default_term_months = '';
    public string $payment_frequency = 'monthly';
    public string $status = 'active';

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:loan_products,code',
            'description' => 'nullable|string',
            'interest_type' => 'required|in:flat,declining,add-on',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0|gte:min_amount',
            'default_term_months' => 'required|integer|min:1|max:120',
            'payment_frequency' => 'required|in:monthly,weekly,bi-monthly,daily',
            'status' => 'required|in:active,inactive',
        ]);

        LoanProduct::create($data);

        Flux::toast('Loan product created.', variant: 'success');

        $this->redirect(route('loan-products.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Loan Product</flux:heading>
            <flux:subheading>Create a new loan product type</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('loan-products.index')" wire:navigate>Back</flux:button>
    </div>

    <flux:card>
        <div class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="name" label="Product Name" required />
                <flux:input wire:model="code" label="Code" required hint="Short identifier, e.g. MEL" />
            </div>

            <flux:textarea wire:model="description" label="Description" />

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:select wire:model="interest_type" label="Interest Type" required>
                    <option value="flat">Flat Rate</option>
                    <option value="declining">Declining Balance</option>
                    <option value="add-on">Add-on Rate</option>
                </flux:select>
                <flux:input wire:model="interest_rate" label="Interest Rate (%)" type="number" step="0.01" required />
                <flux:select wire:model="payment_frequency" label="Payment Frequency" required>
                    <option value="monthly">Monthly</option>
                    <option value="weekly">Weekly</option>
                    <option value="bi-monthly">Bi-monthly</option>
                    <option value="daily">Daily</option>
                </flux:select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="min_amount" label="Min Amount" type="number" step="0.01" required />
                <flux:input wire:model="max_amount" label="Max Amount" type="number" step="0.01" />
                <flux:input wire:model="default_term_months" label="Default Term (months)" type="number" required />
            </div>

            <flux:select wire:model="status" label="Status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>
        </div>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('loan-products.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Save Product</flux:button>
    </div>
</div>
