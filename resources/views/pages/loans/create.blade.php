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
    public ?string $client_id = null;
    public ?string $loan_product_id = null;
    public ?string $branch_id = null;
    public string $principal_amount = '';
    public string $purpose = '';
    public string $search = '';

    public string $product_interest_rate = '';
    public string $product_interest_type = '';
    public int $product_term_months = 0;
    public string $product_payment_frequency = '';
    public string $product_min_amount = '0';
    public string $product_max_amount = '';

    public function mount(): void
    {
        $this->branch_id = auth()->user()->branch_id;
    }

    public function updatedClientId($value): void
    {
        if (!$value) {
            return;
        }
        $client = Client::find($value);
        if ($client && $client->branch_id) {
            $this->branch_id = $client->branch_id;
        }
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
    public function selectedClient()
    {
        return $this->client_id ? Client::find($this->client_id) : null;
    }

    #[Computed]
    public function branch()
    {
        return Branch::find($this->branch_id);
    }

    public function selectClient(string $id): void
    {
        $this->client_id = $id;
        $this->search = '';

        $client = Client::find($id);
        if ($client && $client->branch_id) {
            $this->branch_id = $client->branch_id;
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'branch_id' => 'required|exists:branches,id',
            'principal_amount' => 'required|numeric|min:0',
            'purpose' => 'nullable|string',
        ]);

        $existing = Loan::where('client_id', $this->client_id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->exists();

        if ($existing) {
            Flux::toast('Client already has an active or pending loan.', variant: 'danger');
            $this->addError('client_id', 'Client already has an active or pending loan.');
            return;
        }

        $product = LoanProduct::findOrFail($this->loan_product_id);

        $loan = Loan::create([
            'loan_product_id' => $this->loan_product_id,
            'client_id' => $this->client_id,
            'branch_id' => $this->branch_id,
            'loan_officer_id' => auth()->id(),
            'principal_amount' => $this->principal_amount,
            'interest_rate' => $product->interest_rate,
            'interest_type' => $product->interest_type,
            'term_months' => $product->default_term_months,
            'payment_frequency' => $product->payment_frequency,
            'purpose' => $this->purpose,
            'status' => 'pending',
        ]);

        Flux::toast('Loan application created.', variant: 'success');

        $this->redirect(route('loans.show', $loan), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Loan</flux:heading>
            <flux:subheading>Create a loan application</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('loans.index')" wire:navigate>Back</flux:button>
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
                @include('pages.loans._form', ['editing' => false])
             
                <div class="mt-6 space-y-2">
                    <flux:button type="submit" variant="primary" class="w-full">Submit Loan Application</flux:button>
                    <flux:button variant="ghost" :href="route('loans.index')" wire:navigate class="w-full">Cancel</flux:button>
                </div>
            </div>
        </div>
    </form>
</div>
