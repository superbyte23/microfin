<?php

use App\Models\SavingsProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public SavingsProduct $product;

    public string $name = '';
    public string $code = '';
    public ?string $description = null;
    public string $interest_rate = '0';
    public string $min_balance = '0';
    public string $min_deposit = '0';
    public string $withdrawal_fee = '0';
    public string $status = 'active';

    public function mount(): void
    {
        $this->fill($this->product->only([
            'name', 'code', 'description', 'interest_rate',
            'min_balance', 'min_deposit', 'withdrawal_fee', 'status',
        ]));
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:savings_products,code,' . $this->product->id,
            'description' => 'nullable|string',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_balance' => 'required|numeric|min:0',
            'min_deposit' => 'required|numeric|min:0',
            'withdrawal_fee' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $this->product->update($data);

        Flux::toast('Savings product updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Savings Product</flux:heading>
            <flux:subheading>{{ $product->name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('savings-products.index')" wire:navigate>Back</flux:button>
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

    <form wire:submit="save">
        <flux:card class="space-y-6">
            @include('pages.savings-products._form')
        </flux:card>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('savings-products.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update Product</flux:button>
        </div>
    </form>
</div>
