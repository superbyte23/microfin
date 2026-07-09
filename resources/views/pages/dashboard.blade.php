<?php

use App\Models\Client;
use App\Models\Loan;
use App\Models\LoanTransaction;
use App\Models\SavingsAccount;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {

    #[Computed]
    public function totalClients()
    {
        return Client::count();
    }

    #[Computed]
    public function activeLoans()
    {
        return Loan::where('status', 'active')->count();
    }

    #[Computed]
    public function totalLoanPortfolio()
    {
        return Loan::whereIn('status', ['active', 'approved'])->sum('principal_amount');
    }

    #[Computed]
    public function totalSavings()
    {
        return SavingsAccount::where('status', 'active')->sum('balance');
    }

    #[Computed]
    public function todayCollections()
    {
        return LoanTransaction::whereDate('created_at', today())->sum('amount');
    }

    #[Computed]
    public function recentPayments()
    {
        return LoanTransaction::with(['loan.client', 'collector'])->latest()->take(5)->get();
    }

    #[Computed]
    public function overdueLoans()
    {
        return Loan::where('status', 'active')->whereHas('amortizationSchedule', function ($q) {
            $q->where('status', 'overdue');
        })->count();
    }

    #[Computed]
    public function portfolioAtRisk()
    {
        $totalPortfolio = Loan::where('status', 'active')->sum('principal_amount');
        if ($totalPortfolio <= 0) {
            return 0;
        }
        $overdueTotal = Loan::where('status', 'active')->whereHas('amortizationSchedule', function ($q) {
            $q->where('status', 'overdue');
        })->sum('principal_amount');
        return round(($overdueTotal / $totalPortfolio) * 100, 1);
    }

    #[Computed]
    public function recentLoans()
    {
        return Loan::with('client')->latest()->take(5)->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Dashboard</flux:heading>
            <flux:subheading>Microfin Overview</flux:subheading>
        </div>
        <flux:text>{{ now()->format('l, F d, Y') }}</flux:text>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <flux:card>
            <div class="pb-0">
                <flux:heading size="sm" class="text-zinc-500">Total Clients</flux:heading>
            </div>
            <div>
                <flux:heading size="xl">{{ number_format($this->totalClients) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div class="pb-0">
                <flux:heading size="sm" class="text-zinc-500">Active Loans</flux:heading>
            </div>
            <div>
                <flux:heading size="xl">{{ number_format($this->activeLoans) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div class="pb-0">
                <flux:heading size="sm" class="text-zinc-500">Loan Portfolio</flux:heading>
            </div>
            <div>
                <flux:heading size="xl">{{ number_format($this->totalLoanPortfolio, 2) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div class="pb-0">
                <flux:heading size="sm" class="text-zinc-500">Total Savings</flux:heading>
            </div>
            <div>
                <flux:heading size="xl">{{ number_format($this->totalSavings, 2) }}</flux:heading>
            </div>
        </flux:card>

        <flux:card>
            <div class="pb-0">
                <flux:heading size="sm" class="text-zinc-500">Today's Collections</flux:heading>
            </div>
            <div>
                <flux:heading size="xl">{{ number_format($this->todayCollections, 2) }}</flux:heading>
            </div>
        </flux:card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <flux:card>
            <div>
                <flux:heading>Portfolio Health</flux:heading>
            </div>
            <div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-4 rounded-lg bg-green-50 dark:bg-green-900/20">
                        <flux:heading size="xl" class="text-green-600">{{ $this->portfolioAtRisk }}%</flux:heading>
                        <flux:text>Portfolio at Risk</flux:text>
                    </div>
                    <div class="text-center p-4 rounded-lg bg-red-50 dark:bg-red-900/20">
                        <flux:heading size="xl" class="text-red-600">{{ number_format($this->overdueLoans) }}</flux:heading>
                        <flux:text>Overdue Loans</flux:text>
                    </div>
                </div>
            </div>
        </flux:card>

        <flux:card>
            <div>
                <flux:heading>Recent Payments</flux:heading>
            </div>
            <div>
                @if($this->recentPayments->isEmpty())
                    <flux:text>No recent payments.</flux:text>
                @else
                    <div class="space-y-3">
                        @foreach($this->recentPayments as $payment)
                            <div class="flex justify-between items-center">
                                <div>
                                    <flux:text>{{ $payment->loan?->client?->full_name ?? 'N/A' }}</flux:text>
                                    <flux:text class="text-xs">{{ $payment->created_at->diffForHumans() }}</flux:text>
                                </div>
                                <flux:heading size="sm">{{ number_format($payment->amount, 2) }}</flux:heading>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </flux:card>
    </div>

    <flux:card>
        <div>
            <flux:heading>Recent Loan Applications</flux:heading>
        </div>
        <div>
            @if($this->recentLoans->isEmpty())
                <flux:text>No recent loans.</flux:text>
            @else
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Client</flux:table.column>
                        <flux:table.column>Amount</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        <flux:table.column>Date</flux:table.column>
                    </flux:table.columns>
                    <flux:table.rows>
                        @foreach($this->recentLoans as $loan)
                            <flux:table.row :key="$loan->id">
                                <flux:table.cell>{{ $loan->client?->full_name }}</flux:table.cell>
                                <flux:table.cell>{{ number_format($loan->principal_amount, 2) }}</flux:table.cell>
                                <flux:table.cell>
                                    @php $colors = ['pending' => 'yellow', 'approved' => 'blue', 'active' => 'green', 'closed' => 'zinc', 'rejected' => 'red']; @endphp
                                    <flux:badge :color="$colors[$loan->status] ?? 'zinc'">{{ ucfirst($loan->status) }}</flux:badge>
                                </flux:table.cell>
                                <flux:table.cell>{{ $loan->created_at->format('M d, Y') }}</flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            @endif
        </div>
    </flux:card>
</div>
