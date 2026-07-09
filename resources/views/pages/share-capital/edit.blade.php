<?php

use App\Models\Branch;
use App\Models\Client;
use App\Models\ShareCapital;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ShareCapital $share_capital;

    public ?string $client_id = null;
    public ?string $branch_id = null;
    public string $total_shares = '';
    public string $share_price = '';

    public function mount(): void
    {
        $this->fill($this->share_capital->only(['client_id', 'branch_id', 'total_shares', 'share_price']));
    }

    public function save(): void
    {
        $data = $this->validate([
            'total_shares' => 'required|integer|min:1',
            'share_price' => 'required|numeric|min:0',
        ]);

        $this->share_capital->update([
            'total_shares' => $this->total_shares,
            'share_price' => $this->share_price,
            'total_amount' => (int) $this->total_shares * (float) $this->share_price,
        ]);

        Flux::toast('Share capital updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Share Capital</flux:heading>
            <flux:subheading>{{ $this->share_capital->client->full_name }}</flux:subheading>
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
            @include('pages.share-capital._form', ['editing' => true])
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Update Share Capital</flux:button>
        </div>
    </form>
</div>
