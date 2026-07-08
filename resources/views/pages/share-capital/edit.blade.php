<?php

use App\Models\ShareCapital;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ShareCapital $share_capital;

    public string $total_shares = '';
    public string $share_price = '';
    public string $status = 'active';

    public function mount(): void
    {
        $this->fill($this->share_capital->only(['total_shares', 'share_price', 'status']));
    }

    public function save(): void
    {
        $data = $this->validate([
            'total_shares' => 'required|integer|min:1',
            'share_price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $this->share_capital->update([
            'total_shares' => $this->total_shares,
            'share_price' => $this->share_price,
            'total_amount' => (int) $this->total_shares * (float) $this->share_price,
            'status' => $this->status,
        ]);

        Flux::toast('Share capital updated.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Share Capital</flux:heading>
            <flux:subheading>{{ $share_capital->client->full_name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Back</flux:button>
    </div>

    <flux:card>
        <flux:card.content class="space-y-4">
            <flux:input wire:model="total_shares" label="Number of Shares" type="number" required />
            <flux:input wire:model="share_price" label="Price Per Share" type="number" step="0.01" required />
            <flux:select wire:model="status" label="Status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </flux:select>

            @if($total_shares && $share_price)
                <flux:card class="bg-zinc-50 dark:bg-zinc-800">
                    <flux:card.content>
                        <flux:heading size="sm">Total Amount: {{ number_format((int)$total_shares * (float)$share_price, 2) }}</flux:heading>
                    </flux:card.content>
                </flux:card>
            @endif
        </flux:card.content>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('share-capital.index')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Update Share Capital</flux:button>
    </div>
</div>
