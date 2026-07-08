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

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
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
                                <flux:text class="text-sm">{{ $client->branch?->name }}</flux:text>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading>Account Details</flux:heading>
            </div>
            <div class="space-y-4">
                <flux:select wire:model="savings_product_id" label="Savings Product" required>
                    <option value="">-- Select --</option>
                    @foreach ($this->products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->interest_rate }}%)</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="branch_id" label="Branch" required>
                    @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </flux:select>
                <flux:input wire:model="initial_deposit" label="Initial Deposit" type="number" step="0.01" required />
            </div>
        </flux:card>
    </div>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('savings.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Create Account</flux:button>
    </div>
</div>
