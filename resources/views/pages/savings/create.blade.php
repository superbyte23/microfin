<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\SavingsAccount;
use App\Models\SavingsProduct;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $client_id = null;
    public ?string $savings_product_id = null;
    public ?string $branch_id = null;
    public string $search = '';
    public string $initial_deposit = '0';

    public function mount(): void
    {
        $this->branch_id = auth()->user()->branch_id;
    }

    #[Computed]
    public function clients()
    {
        return Client::where('status', 'active')
            ->when($this->search, fn($q) => $q->whereAny(['first_name', 'middle_name', 'last_name'], 'like', "%{$this->search}%"))
            ->orderBy('last_name')
            ->get();
    }

    #[Computed]
    public function products()
    {
        return SavingsProduct::where('status', 'active')->orderBy('name')->get();
    }

    public function save(): void
    {
        $data = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'savings_product_id' => 'required|exists:savings_products,id',
            'branch_id' => 'required|exists:branches,id',
            'initial_deposit' => 'required|numeric|min:0',
        ]);

        $product = SavingsProduct::findOrFail($this->savings_product_id);

        $accountNumber = 'SAV-' . strtoupper(substr(uniqid(), -8));

        $account = SavingsAccount::create([
            'client_id' => $this->client_id,
            'branch_id' => $this->branch_id,
            'savings_product_id' => $this->savings_product_id,
            'account_number' => $accountNumber,
            'balance' => $this->initial_deposit,
            'status' => 'active',
        ]);

        if ((float) $this->initial_deposit > 0) {
            $account->transactions()->create([
                'type' => 'deposit',
                'amount' => $this->initial_deposit,
                'balance_before' => 0,
                'balance_after' => $this->initial_deposit,
                'performed_by' => auth()->id(),
                'notes' => 'Initial deposit',
            ]);
        }

        Flux::toast('Savings account created.', variant: 'success');

        $this->redirect(route('savings.show', $account), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Savings Account</flux:heading>
            <flux:subheading>Open a savings account for a client</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('savings.index')" wire:navigate>Back</flux:button>
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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <flux:card class="space-y-4">
                <flux:heading size="lg">Client</flux:heading>

                <flux:field>
                    <flux:label>Search Clients</flux:label>
                    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search clients..." icon="magnifying-glass" />
                </flux:field>

                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @foreach ($this->clients as $client)
                        <label class="flex items-center gap-3 p-2 rounded-lg cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800">
                            <input type="radio" name="client_id" wire:model="client_id" value="{{ $client->id }}" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <div class="font-medium">{{ $client->full_name }}</div>
                                <flux:text class="text-sm">{{ $client->branch?->name }}</flux:text>
                            </div>
                        </label>
                    @endforeach
                </div>
                <flux:error name="client_id" />
            </flux:card>

            <flux:card class="space-y-4">
                <flux:heading size="lg">Account Details</flux:heading>

                <flux:field>
                    <flux:label>Savings Product</flux:label>
                    <flux:select wire:model="savings_product_id" required>
                        <option value="">-- Select --</option>
                        @foreach ($this->products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->interest_rate }}%)</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Branch</flux:label>
                    <flux:select wire:model="branch_id" required>
                        @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </flux:select>
                </flux:field>

                <flux:field>
                    <flux:label>Initial Deposit</flux:label>
                    <flux:input wire:model="initial_deposit" type="number" step="0.01" required />
                </flux:field>
            </flux:card>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('savings.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Create Account</flux:button>
        </div>
    </form>
</div>
