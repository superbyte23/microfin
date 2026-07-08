<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\Loan;
use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $client_id = null;
    public ?string $loan_product_id = null;
    public ?string $branch_id = null;
    public string $principal_amount = '';
    public string $purpose = '';
    public string $search = '';

    public ?LoanProduct $selectedProduct = null;

    protected function queryString(): array
    {
        return ['client_id'];
    }

    public function mount(): void
    {
        $this->branch_id = auth()->user()->branch_id;
    }

    public function updatedLoanProductId(): void
    {
        $this->selectedProduct = LoanProduct::find($this->loan_product_id);
    }

    #[Computed]
    public function clients()
    {
        return Client::query()
            ->where('status', 'active')
            ->when($this->search, fn($q) => $q->whereAny(['first_name', 'middle_name', 'last_name', 'phone'], 'like', "%{$this->search}%"))
            ->orderBy('last_name')
            ->get();
    }

    #[Computed]
    public function products()
    {
        return LoanProduct::where('status', 'active')->orderBy('name')->get();
    }

    #[Computed]
    public function branches()
    {
        return Branch::orderBy('name')->get();
    }

    public function selectClient(string $id): void
    {
        $this->client_id = $id;
    }

    public function save(): void
    {
        $data = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'loan_product_id' => 'required|exists:loan_products,id',
            'branch_id' => 'required|exists:branches,id',
            'principal_amount' => 'required|numeric|min:0',
            'purpose' => 'nullable|string',
        ]);

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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <flux:card>
                <div>
                    <flux:heading>Client</flux:heading>
                </div>
                <div>
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search clients..." icon="magnifying-glass" class="mb-4" />

                    <div class="space-y-2 max-h-48 overflow-y-auto">
                        @foreach ($this->clients as $client)
                            <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                                <flux:radio wire:model="client_id" value="{{ $client->id }}" />
                                <div>
                                    <div class="font-medium">{{ $client->full_name }}</div>
                                    <flux:text class="text-sm">{{ $client->phone }} · {{ $client->branch?->name }}</flux:text>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </flux:card>

            <flux:card>
                <div>
                    <flux:heading>Loan Details</flux:heading>
                </div>
                <div class="space-y-4">
                    <flux:select wire:model="loan_product_id" label="Loan Product" required>
                        <option value="">-- Select Product --</option>
                        @foreach ($this->products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} ({{ $product->interest_rate }}% {{ ucfirst($product->interest_type) }})
                            </option>
                        @endforeach
                    </flux:select>

                    @if ($selectedProduct)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input wire:model="principal_amount" label="Principal Amount" type="number" step="0.01" required />
                            <flux:input label="Interest Rate" :value="$selectedProduct->interest_rate . '% ' . ucfirst($selectedProduct->interest_type)" disabled />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <flux:input label="Term" :value="$selectedProduct->default_term_months . ' months'" disabled />
                            <flux:input label="Payment Frequency" :value="ucfirst($selectedProduct->payment_frequency)" disabled />
                        </div>
                    @else
                        <flux:input wire:model="principal_amount" label="Principal Amount" type="number" step="0.01" required />
                    @endif

                    <flux:textarea wire:model="purpose" label="Loan Purpose" />
                </div>
            </flux:card>
        </div>

        <div>
            <flux:card>
                <div>
                    <flux:heading>Assignment</flux:heading>
                </div>
                <div class="space-y-4">
                    <flux:select wire:model="branch_id" label="Branch" required>
                        @foreach ($this->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </flux:card>

            <div class="mt-6 space-y-2">
                <flux:button variant="primary" wire:click="save" class="w-full">Submit Loan Application</flux:button>
                <flux:button variant="ghost" :href="route('loans.index')" wire:navigate class="w-full">Cancel</flux:button>
            </div>
        </div>
    </div>
</div>
