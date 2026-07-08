<?php

use App\Models\ShareCapital;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function shareCapitals()
    {
        return ShareCapital::query()
            ->with(['client', 'branch'])
            ->when($this->search, fn($q) => $q->whereHas('client', fn($q) => $q->whereAny(['first_name', 'last_name'], 'like', "%{$this->search}%")))
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Share Capital</flux:heading>
            <flux:subheading>Manage members' capital shares</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('share-capital.create')" wire:navigate>New Share Capital</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search by client..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Client</flux:table.column>
            <flux:table.column>Total Shares</flux:table.column>
            <flux:table.column>Share Price</flux:table.column>
            <flux:table.column>Total Amount</flux:table.column>
            <flux:table.column>Branch</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->shareCapitals as $sc)
                <flux:table.row :key="$sc->id">
                    <flux:table.cell>{{ $sc->client->full_name }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($sc->total_shares) }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($sc->share_price, 2) }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:heading size="sm">{{ number_format($sc->total_amount, 2) }}</flux:heading>
                    </flux:table.cell>
                    <flux:table.cell>{{ $sc->branch?->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge :color="$sc->status === 'active' ? 'green' : 'zinc'">{{ ucfirst($sc->status) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="pencil" size="sm" variant="ghost" :href="route('share-capital.edit', $sc)" wire:navigate />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    {{ $this->shareCapitals->links() }}
</div>
