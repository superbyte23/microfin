<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\ShareCapital;
use App\Models\ShareCapitalTransaction;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $client_id = null;
    public ?string $branch_id = null;
    public string $total_shares = '';
    public string $share_price = '100';
    public string $search = '';

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

    public function save(): void
    {
        $data = $this->validate([
            'client_id' => 'required|exists:clients,id',
            'branch_id' => 'required|exists:branches,id',
            'total_shares' => 'required|integer|min:1',
            'share_price' => 'required|numeric|min:0',
        ]);

        $sc = ShareCapital::create([
            'client_id' => $this->client_id,
            'branch_id' => $this->branch_id,
            'total_shares' => $this->total_shares,
            'share_price' => $this->share_price,
            'total_amount' => (int) $this->total_shares * (float) $this->share_price,
            'status' => 'active',
        ]);

        $sc->transactions()->create([
            'type' => 'purchase',
            'shares' => $this->total_shares,
            'amount' => $sc->total_amount,
            'balance_before' => 0,
            'balance_after' => $sc->total_amount,
            'performed_by' => auth()->id(),
            'notes' => 'Initial share purchase',
        ]);

        Flux::toast('Share capital created.', variant: 'success');

        $this->redirect(route('share-capital.index'), navigate: true);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Share Capital</flux:heading>
            <flux:subheading>Record share capital for a member</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Back</flux:button>
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
                <flux:heading>Share Details</flux:heading>
            </div>
            <div class="space-y-4">
                <flux:input wire:model="total_shares" label="Number of Shares" type="number" required />
                <flux:input wire:model="share_price" label="Price Per Share" type="number" step="0.01" required />
                <flux:select wire:model="branch_id" label="Branch" required>
                    @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </flux:select>

                @if($total_shares && $share_price)
                    <flux:card class="bg-zinc-50 dark:bg-zinc-800">
                        <div>
                            <flux:heading size="sm">Total Amount: {{ number_format((int)$total_shares * (float)$share_price, 2) }}</flux:heading>
                        </div>
                    </flux:card>
                @endif
            </div>
        </flux:card>
    </div>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Save Share Capital</flux:button>
    </div>
</div>
