<?php

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $entry_date = '';
    public string $description = '';
    public ?string $reference_type = null;
    public ?string $reference_id = null;
    public ?string $branch_id = null;

    public array $lines = [];

    public function mount(): void
    {
        $this->entry_date = now()->toDateString();
        $this->branch_id = auth()->user()->branch_id;
        $this->addLine();
        $this->addLine();
    }

    public function addLine(): void
    {
        $this->lines[] = ['account_id' => '', 'type' => 'debit', 'amount' => '0', 'description' => ''];
    }

    public function removeLine(int $index): void
    {
        unset($this->lines[$index]);
        $this->lines = array_values($this->lines);
    }

    public function accounts()
    {
        return ChartOfAccount::where('is_active', true)->orderBy('code')->get();
    }

    public function save(): void
    {
        $this->validate([
            'entry_date' => 'required|date',
            'description' => 'required|string',
            'branch_id' => 'required|exists:branches,id',
            'lines.*.account_id' => 'required|exists:chart_of_accounts,id',
            'lines.*.type' => 'required|in:debit,credit',
            'lines.*.amount' => 'required|numeric|min:0.01',
        ]);

        $totalDebits = collect($this->lines)->where('type', 'debit')->sum('amount');
        $totalCredits = collect($this->lines)->where('type', 'credit')->sum('amount');

        if (abs($totalDebits - $totalCredits) > 0.01) {
            Flux::toast('Debits must equal credits.', variant: 'danger');
            return;
        }

        $entry = JournalEntry::create([
            'entry_date' => $this->entry_date,
            'description' => $this->description,
            'reference_type' => $this->reference_type,
            'reference_id' => $this->reference_id,
            'branch_id' => $this->branch_id,
            'created_by' => auth()->id(),
        ]);

        foreach ($this->lines as $line) {
            $entry->items()->create([
                'account_id' => $line['account_id'],
                'type' => $line['type'],
                'amount' => $line['amount'],
                'description' => $line['description'] ?: null,
            ]);
        }

        Flux::toast('Journal entry created.', variant: 'success');

        $this->redirect(route('accounting.journals.show', $entry), navigate: true);
    }

    public function getTotalDebitsProperty()
    {
        return collect($this->lines)->where('type', 'debit')->sum('amount');
    }

    public function getTotalCreditsProperty()
    {
        return collect($this->lines)->where('type', 'credit')->sum('amount');
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Journal Entry</flux:heading>
            <flux:subheading>Create a double-entry journal entry</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('accounting.journals')" wire:navigate>Back</flux:button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <flux:card>
                <div>
                    <flux:heading>Entry Lines</flux:heading>
                    <flux:text>Total Debits: <span class="text-red-600 font-bold">{{ number_format($this->getTotalDebitsProperty(), 2) }}</span>
                    · Total Credits: <span class="text-green-600 font-bold">{{ number_format($this->getTotalCreditsProperty(), 2) }}</span></flux:text>
                </div>
                <div class="space-y-4">
                    @foreach ($lines as $i => $line)
                        <div class="flex gap-2 items-start">
                            <div class="flex-1">
                                <flux:select wire:model="lines.{{ $i }}.account_id" placeholder="Select account">
                                    <option value="">-- Account --</option>
                                    @foreach ($this->accounts() as $account)
                                        <option value="{{ $account->id }}">{{ $account->code }} - {{ $account->name }}</option>
                                    @endforeach
                                </flux:select>
                            </div>
                            <div class="w-24">
                                <flux:select wire:model="lines.{{ $i }}.type">
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </flux:select>
                            </div>
                            <div class="w-32">
                                <flux:input wire:model="lines.{{ $i }}.amount" type="number" step="0.01" placeholder="0.00" />
                            </div>
                            <div class="flex-1">
                                <flux:input wire:model="lines.{{ $i }}.description" placeholder="Description (optional)" />
                            </div>
                            <flux:button icon="trash" size="sm" variant="ghost" wire:click="removeLine({{ $i }})" class="mt-1" />
                        </div>
                    @endforeach
                    <flux:button variant="ghost" wire:click="addLine" class="w-full">+ Add Line</flux:button>
                </div>
            </flux:card>
        </div>

        <div>
            <flux:card>
                <div>
                    <flux:heading>Entry Details</flux:heading>
                </div>
                <div class="space-y-4">
                    <flux:input wire:model="entry_date" label="Entry Date" type="date" required />
                    <flux:textarea wire:model="description" label="Description" required />
                    <flux:input wire:model="reference_type" label="Reference Type" placeholder="e.g. loan_payment" />
                    <flux:select wire:model="branch_id" label="Branch" required>
                        @foreach (\App\Models\Branch::orderBy('name')->get() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </flux:select>
                </div>
            </flux:card>

            <div class="mt-6 space-y-2">
                <flux:button variant="primary" wire:click="save" class="w-full">Save Entry</flux:button>
                <flux:button variant="ghost" :href="route('accounting.journals')" wire:navigate class="w-full">Cancel</flux:button>
            </div>
        </div>
    </div>
</div>
