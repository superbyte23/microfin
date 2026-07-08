<?php

use App\Models\Loan;
use App\Services\InterestCalculator;
use Flux\Flux;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public Loan $loan;

    #[Computed]
    public function schedule()
    {
        return $this->loan->amortizationSchedule()->orderBy('installment_number')->get();
    }

    #[Computed]
    public function transactions()
    {
        return $this->loan->transactions()->with('collector')->latest()->get();
    }

    #[Computed]
    public function totalPaid(): float
    {
        return (float) $this->loan->transactions()->sum('amount');
    }

    public function approve(): void
    {
        $this->loan->update(['status' => 'approved', 'approved_by' => auth()->id()]);
        Flux::toast('Loan approved.', variant: 'success');
    }

    public function release(): void
    {
        $this->loan->update([
            'status' => 'active',
            'release_date' => now()->toDateString(),
        ]);

        $calculator = app(InterestCalculator::class);
        $schedule = $calculator->generateSchedule($this->loan);

        foreach ($schedule as $installment) {
            $this->loan->amortizationSchedule()->create($installment->toArray());
        }

        $lastDue = $schedule->last()->due_date;
        $this->loan->update(['maturity_date' => $lastDue]);

        Flux::toast('Loan disbursed. Amortization schedule generated.', variant: 'success');
    }

    public function reject(): void
    {
        $this->loan->update(['status' => 'rejected']);
        Flux::toast('Loan rejected.', variant: 'success');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">{{ $loan->client->full_name }}</flux:heading>
            <flux:subheading>{{ $loan->product?->name }} · {{ number_format($loan->principal_amount, 2) }}</flux:subheading>
        </div>
        <div class="flex gap-2">
            <flux:button variant="ghost" :href="route('loans.index')" wire:navigate>Back</flux:button>
            @if($loan->status === 'pending')
                <flux:button variant="danger" wire:click="reject" wire:confirm="Reject this loan application?">Reject</flux:button>
                <flux:button variant="primary" wire:click="approve">Approve</flux:button>
            @endif
            @if($loan->status === 'approved')
                <flux:button variant="primary" wire:click="release" wire:confirm="Release this loan? This will generate the amortization schedule.">Release</flux:button>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <flux:card>
            <div>
                <flux:heading size="sm">Status</flux:heading>
            </div>
            <div>
                @php $colors = ['pending' => 'yellow', 'approved' => 'blue', 'active' => 'green', 'closed' => 'zinc', 'rejected' => 'red', 'written-off' => 'red']; @endphp
                <flux:badge size="lg" :color="$colors[$loan->status] ?? 'zinc'">{{ ucfirst($loan->status) }}</flux:badge>
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading size="sm">Principal</flux:heading>
            </div>
            <div>
                <flux:heading size="lg">{{ number_format($loan->principal_amount, 2) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading size="sm">Total Paid</flux:heading>
            </div>
            <div>
                <flux:heading size="lg">{{ number_format($this->totalPaid, 2) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading size="sm">Balance</flux:heading>
            </div>
            <div>
                <flux:heading size="lg">{{ number_format(max($loan->principal_amount - $this->totalPaid, 0), 2) }}</flux:heading>
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <flux:card>
            <div>
                <flux:heading>Loan Details</flux:heading>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between"><flux:text>Client</flux:text><span>{{ $loan->client->full_name }}</span></div>
                <div class="flex justify-between"><flux:text>Product</flux:text><span>{{ $loan->product?->name }}</span></div>
                <div class="flex justify-between"><flux:text>Interest</flux:text><span>{{ $loan->interest_rate }}% {{ ucfirst($loan->interest_type) }}</span></div>
                <div class="flex justify-between"><flux:text>Term</flux:text><span>{{ $loan->term_months }} months</span></div>
                <div class="flex justify-between"><flux:text>Frequency</flux:text><span>{{ ucfirst($loan->payment_frequency) }}</span></div>
                <div class="flex justify-between"><flux:text>Release Date</flux:text><span>{{ $loan->release_date?->format('M d, Y') ?? '—' }}</span></div>
                <div class="flex justify-between"><flux:text>Maturity</flux:text><span>{{ $loan->maturity_date?->format('M d, Y') ?? '—' }}</span></div>
                <div class="flex justify-between"><flux:text>Branch</flux:text><span>{{ $loan->branch?->name }}</span></div>
                <div class="flex justify-between"><flux:text>Loan Officer</flux:text><span>{{ $loan->loanOfficer?->name ?? '—' }}</span></div>
                @if($loan->purpose)
                    <div><flux:text>Purpose</flux:text><p class="mt-1">{{ $loan->purpose }}</p></div>
                @endif
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading>Recent Transactions</flux:heading>
            </div>
            <div>
                @if($this->transactions->isEmpty())
                    <flux:text>No transactions yet.</flux:text>
                @else
                    <div class="space-y-2">
                        @foreach($this->transactions as $txn)
                            <div class="flex justify-between items-center py-1">
                                <div>
                                    <flux:text>{{ $txn->created_at->format('M d, Y') }}</flux:text>
                                    <flux:text class="text-xs">{{ $txn->payment_method }}</flux:text>
                                </div>
                                <flux:heading size="sm">{{ number_format($txn->amount, 2) }}</flux:heading>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </flux:card>
    </div>

    @if($loan->status === 'active' || $loan->status === 'closed')
        <flux:card class="mt-6">
            <div>
                <flux:heading>Amortization Schedule</flux:heading>
            </div>
            <div>
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>#</flux:table.column>
                        <flux:table.column>Due Date</flux:table.column>
                        <flux:table.column>Principal</flux:table.column>
                        <flux:table.column>Interest</flux:table.column>
                        <flux:table.column>Total Due</flux:table.column>
                        <flux:table.column>Paid</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach($this->schedule as $inst)
                            <flux:table.row :key="$inst->id">
                                <flux:table.cell>{{ $inst->installment_number }}</flux:table.cell>
                                <flux:table.cell>{{ $inst->due_date->format('M d, Y') }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($inst->principal, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($inst->interest, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($inst->total_due, 2) }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($inst->paid_amount, 2) }}</flux:table.cell>
                                <flux:table.cell>
                                    @php $sc = ['pending' => 'yellow', 'paid' => 'green', 'partial' => 'blue', 'overdue' => 'red']; @endphp
                                    <flux:badge size="sm" :color="$sc[$inst->status] ?? 'zinc'">{{ ucfirst($inst->status) }}</flux:badge>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>
    @endif
</div>
