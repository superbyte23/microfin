<?php

use App\Models\JournalEntry;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public JournalEntry $journal;

    #[Computed]
    public function totalDebits(): float
    {
        return (float) $this->journal->items->where('type', 'debit')->sum('amount');
    }

    #[Computed]
    public function totalCredits(): float
    {
        return (float) $this->journal->items->where('type', 'credit')->sum('amount');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Journal Entry</flux:heading>
            <flux:subheading>{{ $journal->entry_date->format('F d, Y') }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('accounting.journals')" wire:navigate>Back</flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <flux:card>
            <flux:card.header><flux:heading size="sm">Date</flux:heading></flux:card.header>
            <flux:card.content><flux:heading>{{ $journal->entry_date->format('M d, Y') }}</flux:heading></flux:card.content>
        </flux:card>
        <flux:card>
            <flux:card.header><flux:heading size="sm">Total Debits</flux:heading></flux:card.header>
            <flux:card.content><flux:heading class="text-red-600">{{ number_format($this->totalDebits, 2) }}</flux:heading></flux:card.content>
        </flux:card>
        <flux:card>
            <flux:card.header><flux:heading size="sm">Total Credits</flux:heading></flux:card.header>
            <flux:card.content><flux:heading class="text-green-600">{{ number_format($this->totalCredits, 2) }}</flux:heading></flux:card.content>
        </flux:card>
    </div>

    <flux:card class="mb-6">
        <flux:card.header>
            <flux:heading>Details</flux:heading>
        </flux:card.header>
        <flux:card.content class="space-y-2">
            <div class="flex justify-between"><flux:text>Description</flux:text><span>{{ $journal->description }}</span></div>
            @if($journal->reference_type)
                <div class="flex justify-between"><flux:text>Reference</flux:text><span>{{ $journal->reference_type }}: {{ $journal->reference_id ?? '—' }}</span></div>
            @endif
            <div class="flex justify-between"><flux:text>Branch</flux:text><span>{{ $journal->branch?->name }}</span></div>
            <div class="flex justify-between"><flux:text>Created By</flux:text><span>{{ $journal->createdBy?->name }}</span></div>
        </flux:card.content>
    </flux:card>

    <flux:card>
        <flux:card.header>
            <flux:heading>Journal Lines</flux:heading>
        </flux:card.header>
        <flux:card.content>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Account Code</flux:table.column>
                    <flux:table.column>Account Name</flux:table.column>
                    <flux:table.column>Debit</flux:table.column>
                    <flux:table.column>Credit</flux:table.column>
                    <flux:table.column>Description</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach($journal->items as $item)
                        <flux:table.row :key="$item->id">
                            <flux:table.cell><flux:badge size="sm" variant="pill">{{ $item->account?->code }}</flux:badge></flux:table.cell>
                            <flux:table.cell>{{ $item->account?->name }}</flux:table.cell>
                            <flux:table.cell>
                                @if($item->type === 'debit')
                                    <span class="text-red-600">{{ number_format($item->amount, 2) }}</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($item->type === 'credit')
                                    <span class="text-green-600">{{ number_format($item->amount, 2) }}</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell><flux:text class="text-xs">{{ $item->description ?? '—' }}</flux:text></flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </flux:card.content>
    </flux:card>
</div>
