<?php

use App\Models\Loan;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts::app')] class extends Component {
    use WithPagination;

    public string $search = '';
    public ?string $status = null;

    #[Computed]
    public function loans()
    {
        return Loan::query()
            ->with(['client', 'product', 'branch'])
            ->when($this->search, fn($q) => $q->whereHas('client', fn($q) => $q->whereAny(['first_name', 'last_name'], 'like', "%{$this->search}%")))
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Loans</flux:heading>
            <flux:subheading>Manage loan applications and disbursements</flux:subheading>
        </div>
        <flux:button icon="plus" :href="route('loans.create')" wire:navigate>New Loan</flux:button>
    </div>

    <div class="flex gap-4 mb-4">
        <flux:input wire:model.live.debounce.300ms="search" placeholder="Search client..." icon="magnifying-glass" />
        <flux:select wire:model.live="status" class="w-auto">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="active">Active</option>
            <option value="closed">Closed</option>
            <option value="rejected">Rejected</option>
            <option value="written-off">Written Off</option>
        </flux:select>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Client</flux:table.column> 
            <flux:table.column>Product</flux:table.column>
            <flux:table.column>Principal</flux:table.column>
            <flux:table.column>Balance</flux:table.column>
            <flux:table.column>Term</flux:table.column>
            <flux:table.column>Status</flux:table.column>
            <flux:table.column></flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($this->loans as $loan)
                <flux:table.row wire:key="loan-{{ $loan->id }}">
                    <flux:table.cell>
                        <flux:heading size="sm">{{ $loan->client->full_name }}</flux:heading>
                        <flux:text>{{ $loan->branch->name }}</flux:text>
                    </flux:table.cell> 
                    <flux:table.cell>{{ $loan->product->name ?? '-' }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($loan->principal_amount, 2) }}</flux:table.cell>
                    <flux:table.cell>{{ number_format($loan->amortizationSchedule()->where('status', '!=', 'paid')->sum('total_due') - $loan->amortizationSchedule()->sum('paid_amount'), 2) }}</flux:table.cell>
                    <flux:table.cell>{{ $loan->term_months }} months</flux:table.cell>
                    <flux:table.cell>
                        @php
                            $colors = ['pending' => 'yellow', 'approved' => 'blue', 'active' => 'green', 'closed' => 'zinc', 'rejected' => 'red', 'written-off' => 'red'];
                        @endphp
                        <flux:badge size="sm" :color="$colors[$loan->status] ?? 'zinc'">{{ ucfirst($loan->status) }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>
                        <flux:dropdown position="bottom" align="end" offset="-15">
                            <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal" inset="top bottom"></flux:button>
                            <flux:menu> 
                                <flux:menu.item icon="eye" :href="route('loans.show', $loan)" wire:navigate >View Details</flux:menu.item>
                                <flux:menu.item icon="pencil" :href="route('loans.edit', $loan)" wire:navigate >Edit</flux:menu.item>
                                <flux:menu.item icon="archive-box" variant="danger">Archive</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell> 
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <flux:pagination :paginator="$this->loans" />
</div>
