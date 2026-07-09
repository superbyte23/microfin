<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\ShareCapital;
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
            @include('pages.share-capital._form', ['editing' => false])
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Save Share Capital</flux:button>
        </div>
    </form>
</div>
