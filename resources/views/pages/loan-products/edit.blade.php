<?php

use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public LoanProduct $product;

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

    public function mount(): void
    {
        $this->fill($this->product->only([
            'name', 'code', 'description', 'interest_type', 'interest_rate',
            'min_amount', 'max_amount', 'default_term_months', 'payment_frequency', 'status',
        ]));
    }

    public function save(): void
    {
        $data = $this->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:loan_products,code,' . $this->product->id,
            'description' => 'nullable|string',
            'interest_type' => 'required|in:flat,declining,add-on',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'min_amount' => 'required|numeric|min:0',
            'max_amount' => 'nullable|numeric|min:0|gte:min_amount',
            'default_term_months' => 'required|integer|min:1|max:120',
            'payment_frequency' => 'required|in:monthly,weekly,bi-monthly,daily',
            'status' => 'required|in:active,inactive',
        ]);

        $this->product->update($data);

        Flux::toast('Loan product updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Loan Product</flux:heading>
            <flux:subheading>{{ $product->name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('loan-products.index')" wire:navigate>Back</flux:button>
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
            @include('pages.loan-products._form')
        </flux:card>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('loan-products.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update Product</flux:button>
        </div>
    </form>
</div>
