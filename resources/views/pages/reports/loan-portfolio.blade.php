<?php

use App\Models\Branch;
use App\Models\Loan;
use App\Models\LoanProduct;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $branch_id = null;
    public ?string $product_id = null;
    public ?string $status = 'active';

    #[Computed]
    public function loans()
    {
        return Loan::with(['client', 'product', 'branch'])
            ->when($this->branch_id, fn($q) => $q->where('branch_id', $this->branch_id))
            ->when($this->product_id, fn($q) => $q->where('loan_product_id', $this->product_id))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function totalPrincipal()
    {
        return $this->loans->sum('principal_amount');
    }

    #[Computed]
    public function totalPaid()
    {
        return $this->loans->loadSum('transactions', 'amount')->sum('transactions_sum_amount');
    }

    public function branches()
    {
        return Branch::orderBy('name')->get();
    }

    public function products()
    {
        return LoanProduct::orderBy('name')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Loan Portfolio Report</flux:heading>
            <flux:subheading>Summary of loan portfolio</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('reports.index')" wire:navigate>Back to Reports</flux:button>
    </div>

    <div class="flex gap-4 mb-6">
        <flux:select wire:model.live="branch_id" class="w-48">
            <option value="">All Branches</option>
            @foreach ($this->branches() as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="product_id" class="w-48">
            <option value="">All Products</option>
            @foreach ($this->products() as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </flux:select>
        <flux:select wire:model.live="status" class="w-48">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
            <option value="rejected">Rejected</option>
            <option value="written-off">Written Off</option>
        </flux:select>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <flux:card>
            <div><flux:heading size="sm">Total Loans</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->loans->count()) }}</flux:heading></div>
        </flux:card>
        <flux:card>
            <div><flux:heading size="sm">Total Principal</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->totalPrincipal, 2) }}</flux:heading></div>
        </flux:card>
        <flux:card>
            <div><flux:heading size="sm">Total Repaid</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->totalPaid, 2) }}</flux:heading></div>
        </flux:card>
    </div>

    <flux:card>
        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Client</flux:table.column>
                    <flux:table.column>Product</flux:table.column>
                    <flux:table.column>Principal</flux:table.column>
                    <flux:table.column>Paid</flux:table.column>
                    <flux:table.column>Balance</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->loans as $loan)
                        @php $paid = (float) $loan->transactions()->sum('amount'); @endphp
                        <flux:table.row :key="$loan->id">
                            <flux:table.cell>{{ $loan->client?->full_name }}</flux:table.cell>
                            <flux:table.cell>{{ $loan->product?->name }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($loan->principal_amount, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($paid, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ number_format(max($loan->principal_amount - $paid, 0), 2) }}</flux:table.cell>
                            <flux:table.cell>
                                @php $colors = ['pending' => 'yellow', 'approved' => 'blue', 'active' => 'green', 'closed' => 'zinc', 'rejected' => 'red', 'written-off' => 'red']; @endphp
                                <flux:badge :color="$colors[$loan->status] ?? 'zinc'">{{ ucfirst($loan->status) }}</flux:badge>
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
