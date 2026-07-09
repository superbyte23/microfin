<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\Loan;
use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Loan $loan;

    public ?string $client_id = null;
    public ?string $loan_product_id = null;
    public ?string $branch_id = null;
    public string $principal_amount = '';
    public string $purpose = '';
    public string $status = '';
    public string $search = '';

    public string $product_interest_rate = '';
    public string $product_interest_type = '';
    public int $product_term_months = 0;
    public string $product_payment_frequency = '';
    public string $product_min_amount = '0';
    public string $product_max_amount = '';

    public function mount(): void
    {
        $this->fill($this->loan->only([
            'client_id', 'loan_product_id', 'branch_id', 'principal_amount', 'purpose', 'status',
        ]));

        $this->loadProduct($this->loan_product_id);
    }

    public function updatedLoanProductId($value): void
    {
        $this->loadProduct($value);
    }

    public function loadProduct(?string $id = null): void
    {
        $id ??= $this->loan_product_id;
        if (!$id) {
            $this->product_interest_rate = '';
            $this->product_interest_type = '';
            $this->product_term_months = 0;
            $this->product_payment_frequency = '';
            $this->product_min_amount = '0';
            $this->product_max_amount = '';
            return;
        }

        $product = LoanProduct::find($id);
        if (!$product) {
            return;
        }

        $this->product_interest_rate = (string) $product->interest_rate;
        $this->product_interest_type = $product->interest_type;
        $this->product_term_months = $product->default_term_months;
        $this->product_payment_frequency = $product->payment_frequency;
        $this->product_min_amount = (string) $product->min_amount;
        $this->product_max_amount = $product->max_amount ? (string) $product->max_amount : '';
    }

    #[Computed]
    public function clients()
    {
        if (!strlen($this->search)) {
            return collect();
        }
        return Client::query()
            ->where('status', 'active')
            ->whereAny(['first_name', 'middle_name', 'last_name', 'phone'], 'like', "%{$this->search}%")
            ->orderBy('last_name')
            ->get();
    }

    #[Computed]
    public function products()
    {
        return LoanProduct::where('status', 'active')->orderBy('name')->get();
    }

    #[Computed]
    public function branch()
    {
        return Branch::find($this->branch_id);
    }

    public function save(): void
    {
        $this->validate([
            'status' => 'required|in:pending,approved,active,closed,rejected,written-off',
        ]);

        $this->loan->update([
            'status' => $this->status,
        ]);

        Flux::toast('Loan updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Loan</flux:heading>
            <flux:subheading>{{ $this->loan->client->full_name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('loans.show', $loan)" wire:navigate>Back to Loan</flux:button>
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
        <div class="grid grid-cols-1 gap-6">
            <div class="lg:col-span-2 space-y-6">
                @include('pages.loans._form', ['editing' => true])

                <flux:card class="space-y-4">
                    <flux:heading size="lg">Status</flux:heading>
                    <flux:field>
                        <flux:label>Status</flux:label>
                        <flux:select wire:model="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="active">Active</option>
                            <option value="closed">Closed</option>
                            <option value="rejected">Rejected</option>
                            <option value="written-off">Written Off</option>
                        </flux:select>
                    </flux:field>
                </flux:card> 
                <div class="mt-6 space-y-2">
                    <flux:button type="submit" variant="primary" class="w-full">Update Loan</flux:button>
                    <flux:button variant="ghost" :href="route('loans.show', $loan)" wire:navigate class="w-full">Cancel</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
