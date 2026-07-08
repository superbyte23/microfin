<?php

use App\Models\Branch;
use App\Models\Loan;
use App\Models\LoanProduct;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Loan $loan;

    public ?string $loan_product_id = null;
    public ?string $branch_id = null;
    public string $principal_amount = '';
    public string $purpose = '';
    public string $status = '';

    public function mount(): void
    {
        $this->fill($this->loan->only([
            'loan_product_id', 'branch_id', 'principal_amount', 'purpose', 'status',
        ]));
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

    public function save(): void
    {
        $data = $this->validate([
            'loan_product_id' => 'required|exists:loan_products,id',
            'branch_id' => 'required|exists:branches,id',
            'principal_amount' => 'required|numeric|min:0',
            'purpose' => 'nullable|string',
            'status' => 'required|in:pending,approved,active,closed,rejected,written-off',
        ]);

        $this->loan->update($data);

        Flux::toast('Loan updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Loan</flux:heading>
            <flux:subheading>{{ $loan->client->full_name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('loans.show', $loan)" wire:navigate>Back to Loan</flux:button>
    </div>

    <flux:card>
        <flux:card.content class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="loan_product_id" label="Loan Product" required>
                    @foreach ($this->products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->interest_rate }}%)</option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="principal_amount" label="Principal Amount" type="number" step="0.01" required />
            </div>

            <flux:textarea wire:model="purpose" label="Loan Purpose" />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="branch_id" label="Branch" required>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="status" label="Status" required>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="active">Active</option>
                    <option value="closed">Closed</option>
                    <option value="rejected">Rejected</option>
                    <option value="written-off">Written Off</option>
                </flux:select>
            </div>
        </flux:card.content>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('loans.show', $loan)" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Update Loan</flux:button>
    </div>
</div>
