<?php

use App\Models\JournalEntry;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';

    #[Computed]
    public function entries()
    {
        return JournalEntry::with(['items.account', 'branch', 'createdBy'])
            ->when($this->search, fn($q) => $q->where('description', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(15);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Journal Entries</flux:heading>
            <flux:subheading>Recorded journal entries</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('accounting.journals.create')" wire:navigate>New Entry</flux:button>
    </div>

    <flux:input wire:model.live.debounce.300ms="search" placeholder="Search..." icon="magnifying-glass" class="mb-4" />

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Date</flux:table.column>
            <flux:table.column>Description</flux:table.column>
            <flux:table.column>Debit</flux:table.column>
            <flux:table.column>Credit</flux:table.column>
            <flux:table.column>Branch</flux:table.column>
            <flux:table.column>Created By</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->entries as $entry)
                @php
                    $debits = $entry->items->where('type', 'debit')->sum('amount');
                    $credits = $entry->items->where('type', 'credit')->sum('amount');
                @endphp
                <flux:table.row :key="$entry->id">
                    <flux:table.cell>{{ $entry->entry_date->format('M d, Y') }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:text>{{ Str::limit($entry->description, 40) }}</flux:text>
                        @if($entry->reference_type)
                            <flux:text class="text-xs">{{ $entry->reference_type }}</flux:text>
                        @endif
                    </flux:table.cell>
                    <flux:table.cell class="text-red-600">{{ number_format($debits, 2) }}</flux:table.cell>
                    <flux:table.cell class="text-green-600">{{ number_format($credits, 2) }}</flux:table.cell>
                    <flux:table.cell>{{ $entry->branch?->name }}</flux:table.cell>
                    <flux:table.cell>{{ $entry->createdBy?->name }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button icon="eye" size="sm" variant="ghost" :href="route('accounting.journals.show', $entry)" wire:navigate />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    {{ $this->entries->links() }}
</div>
