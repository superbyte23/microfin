<?php

use App\Models\ChartOfAccount;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public ChartOfAccount $account;

    public string $code = '';
    public string $name = '';
    public string $type = 'asset';
    public ?string $parent_id = null;
    public bool $is_active = true;
    public ?string $description = null;

    public function mount(): void
    {
        $this->fill($this->account->only(['code', 'name', 'type', 'parent_id', 'is_active', 'description']));
    }

    public function save(): void
    {
        $data = $this->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts,code,' . $this->account->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        $this->account->update($data);

        Flux::toast('Account updated.', variant: 'success');
    }

    public function parentAccounts()
    {
        return ChartOfAccount::whereNull('parent_id')->where('id', '!=', $this->account->id)->orderBy('code')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Edit Account</flux:heading>
            <flux:subheading>{{ $account->code }} - {{ $account->name }}</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('accounting.chart-of-accounts')" wire:navigate>Back</flux:button>
    </div>

    <flux:card>
        <flux:card.content class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="code" label="Account Code" required />
                <flux:input wire:model="name" label="Account Name" required />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="type" label="Type" required>
                    <option value="asset">Asset</option>
                    <option value="liability">Liability</option>
                    <option value="equity">Equity</option>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </flux:select>
                <flux:select wire:model="parent_id" label="Parent Account">
                    <option value="">None (Top Level)</option>
                    @foreach ($this->parentAccounts() as $parent)
                        <option value="{{ $parent->id }}">{{ $parent->code }} - {{ $parent->name }}</option>
                    @endforeach
                </flux:select>
            </div>

            <flux:checkbox wire:model="is_active" label="Active" />

            <flux:textarea wire:model="description" label="Description" />
        </flux:card.content>
    </flux:card>

    <div class="mt-6 flex justify-end gap-4">
        <flux:button variant="ghost" :href="route('accounting.chart-of-accounts')" wire:navigate>Cancel</flux:button>
        <flux:button variant="primary" wire:click="save">Update Account</flux:button>
    </div>
</div>
