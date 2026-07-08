<?php

use App\Models\Branch;
use App\Models\LoanTransaction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $branch_id = null;
    public string $date_from = '';
    public string $date_to = '';

    public function mount(): void
    {
        $this->date_from = now()->startOfMonth()->toDateString();
        $this->date_to = now()->toDateString();
    }

    #[Computed]
    public function transactions()
    {
        return LoanTransaction::with(['loan.client', 'collector', 'branch'])
            ->when($this->branch_id, fn($q) => $q->where('branch_id', $this->branch_id))
            ->when($this->date_from, fn($q) => $q->whereDate('created_at', '>=', $this->date_from))
            ->when($this->date_to, fn($q) => $q->whereDate('created_at', '<=', $this->date_to))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    #[Computed]
    public function totalCollected()
    {
        return $this->transactions->sum('amount');
    }

    #[Computed]
    public function byMethod()
    {
        return $this->transactions->groupBy('payment_method')->map(fn($items, $method) => [
            'method' => $method,
            'count' => $items->count(),
            'total' => $items->sum('amount'),
        ]);
    }

    public function branches()
    {
        return Branch::orderBy('name')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Collections Report</flux:heading>
            <flux:subheading>Payment collection summary</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('reports.index')" wire:navigate>Back to Reports</flux:button>
    </div>

    <div class="flex gap-4 mb-6">
        <flux:select wire:model.live="branch_id" class="w-48">
            <option value="">All Branches</option>
            @foreach ($this->branches() as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
            @endforeach
        </flux:select>
        <flux:input wire:model.live="date_from" type="date" class="w-40" />
        <flux:input wire:model.live="date_to" type="date" class="w-40" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <flux:card>
            <div><flux:heading size="sm">Transactions</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->transactions->count()) }}</flux:heading></div>
        </flux:card>
        <flux:card>
            <div><flux:heading size="sm">Total Collected</flux:heading></div>
            <div><flux:heading size="xl">{{ number_format($this->totalCollected, 2) }}</flux:heading></div>
        </flux:card>
        @foreach($this->byMethod as $method => $data)
            <flux:card>
                <div><flux:heading size="sm">{{ ucfirst($method) }}</flux:heading></div>
                <div>
                    <flux:heading size="xl">{{ number_format($data['total'], 2) }}</flux:heading>
                    <flux:text>{{ $data['count'] }} transactions</flux:text>
                </div>
            </flux:card>
        @endforeach
    </div>

    <flux:card>
        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Date</flux:table.column>
                    <flux:table.column>Client</flux:table.column>
                    <flux:table.column>Amount</flux:table.column>
                    <flux:table.column>Method</flux:table.column>
                    <flux:table.column>Collector</flux:table.column>
                    <flux:table.column>Reference</flux:table.column>
                </flux:table.columns>
                <flux:table.rows>
                    @foreach ($this->transactions as $txn)
                        <flux:table.row :key="$txn->id">
                            <flux:table.cell>{{ $txn->created_at->format('M d, Y h:i A') }}</flux:table.cell>
                            <flux:table.cell>{{ $txn->loan?->client?->full_name ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>{{ number_format($txn->amount, 2) }}</flux:table.cell>
                            <flux:table.cell>{{ ucfirst($txn->payment_method) }}</flux:table.cell>
                            <flux:table.cell>{{ $txn->collector?->name ?? 'N/A' }}</flux:table.cell>
                            <flux:table.cell>{{ $txn->reference_number ?? '—' }}</flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>
        </div>
    </flux:card>
</div>
