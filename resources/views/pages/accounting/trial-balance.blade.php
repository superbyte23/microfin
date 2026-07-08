<?php

use App\Models\ChartOfAccount;
use App\Models\JournalItem;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ?string $as_of_date = null;

    public function mount(): void
    {
        $this->as_of_date = now()->toDateString();
    }

    #[Computed]
    public function accounts()
    {
        return ChartOfAccount::with(['journalItems' => function ($q) {
            $q->whereHas('entry', fn($e) => $e->where('entry_date', '<=', $this->as_of_date));
        }])->where('is_active', true)->orderBy('code')->get()->map(function ($account) {
            $debits = $account->journalItems->where('type', 'debit')->sum('amount');
            $credits = $account->journalItems->where('type', 'credit')->sum('amount');
            $balance = $debits - $credits;

            return (object) [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'debits' => $debits,
                'credits' => $credits,
                'balance' => $balance,
            ];
        });
    }

    #[Computed]
    public function totalDebits(): float
    {
        return $this->accounts->where('balance', '>', 0)->sum('balance');
    }

    #[Computed]
    public function totalCredits(): float
    {
        return abs($this->accounts->where('balance', '<', 0)->sum('balance'));
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Trial Balance</flux:heading>
            <flux:subheading>As of {{ $as_of_date ? \Carbon\Carbon::parse($as_of_date)->format('F d, Y') : '—' }}</flux:subheading>
        </div>
        <flux:input wire:model.live="as_of_date" type="date" label="As of Date" class="w-48" />
    </div>

    <flux:card>
        <div>
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Code</flux:table.column>
                    <flux:table.column>Account</flux:table.column>
                    <flux:table.column>Type</flux:table.column>
                    <flux:table.column class="text-right">Debit</flux:table.column>
                    <flux:table.column class="text-right">Credit</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @foreach ($this->accounts as $account)
                        <flux:table.row :key="$account->code">
                            <flux:table.cell><flux:badge size="sm" variant="pill">{{ $account->code }}</flux:badge></flux:table.cell>
                            <flux:table.cell>{{ $account->name }}</flux:table.cell>
                            <flux:table.cell>
                                <flux:badge size="sm" :color="$account->type === 'asset' ? 'green' : ($account->type === 'liability' ? 'yellow' : ($account->type === 'equity' ? 'blue' : ($account->type === 'income' ? 'purple' : 'orange')))">
                                    {{ ucfirst($account->type) }}
                                </flux:badge>
                            </flux:table.cell>
                            <flux:table.cell class="text-right {{ $account->balance > 0 ? 'text-red-600' : '' }}">
                                {{ $account->balance > 0 ? number_format($account->balance, 2) : '—' }}
                            </flux:table.cell>
                            <flux:table.cell class="text-right {{ $account->balance < 0 ? 'text-green-600' : '' }}">
                                {{ $account->balance < 0 ? number_format(abs($account->balance), 2) : '—' }}
                            </flux:table.cell>
                        </flux:table.row>
                    @endforeach
                </flux:table.rows>
            </flux:table>

            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                <div class="flex justify-between font-bold">
                    <span>Totals</span>
                    <span class="text-red-600">{{ number_format($this->totalDebits, 2) }}</span>
                    <span class="text-green-600">{{ number_format($this->totalCredits, 2) }}</span>
                </div>
            </div>
        </div>
    </flux:card>
</div>
