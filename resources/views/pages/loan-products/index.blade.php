<?php

use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function products()
    {
        return LoanProduct::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%"))
            ->orderBy('name')
            ->paginate(15);
    }

    public function delete(LoanProduct $product): void
    {
        $product->delete();
        Flux::toast('Loan product deleted.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Loan Products</flux:heading>
            <flux:subheading>Define loan product types and terms</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('loan-products.create')" wire:navigate>New Product</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search products..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Code</flux:table.column>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Interest</flux:table.column>
            <flux:table.column>Amount Range</flux:table.column>
            <flux:table.column>Term</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->products as $product)
                <flux:table.row wire:key="product-{{ $product->id }}">
                    <flux:table.cell>
                        <flux:badge size="sm" variant="pill">{{ $product->code }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $product->name }}</flux:table.cell>
                    <flux:table.cell>
                        {{ $product->interest_rate }}%
                        <flux:text class="text-xs">{{ ucfirst($product->interest_type) }}</flux:text>
                    </flux:table.cell>
                    <flux:table.cell>
                        {{ number_format($product->min_amount, 0) }}
                        @if($product->max_amount)
                            – {{ number_format($product->max_amount, 0) }}
                        @endif
                    </flux:table.cell>
                    <flux:table.cell>{{ $product->default_term_months }} months</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$product->status === 'active' ? 'green' : 'zinc'">{{ ucfirst($product->status) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end">
                            <flux:button icon="ellipsis-horizontal" size="sm" variant="ghost" />
                            <flux:menu>
                                <flux:menu.item icon="pencil" :href="route('loan-products.edit', $product)" wire:navigate>Edit</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="trash" variant="danger" wire:click="delete({{ $product->id }})" wire:confirm="Delete this product?">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->products" />
</div>
