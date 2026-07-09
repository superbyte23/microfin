<?php

use App\Models\ChartOfAccount;
use Flux\Flux;
use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
    public string $code = '';
    public string $name = '';
    public string $type = 'asset';
    public ?string $parent_id = null;
    public bool $is_active = true;
    public ?string $description = null;

    public function save(): void
    {
        $data = $this->validate([
            'code' => 'required|string|max:20|unique:chart_of_accounts,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,liability,equity,income,expense',
            'parent_id' => 'nullable|exists:chart_of_accounts,id',
            'is_active' => 'required|boolean',
            'description' => 'nullable|string',
        ]);

        ChartOfAccount::create($data);

        Flux::toast('Account created.', variant: 'success');

        $this->redirect(route('accounting.chart-of-accounts'), navigate: true);
    }

    public function parentAccounts()
    {
        return ChartOfAccount::whereNull('parent_id')->orderBy('code')->get();
    }
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">New Account</flux:heading>
            <flux:subheading>Add a chart of account</flux:subheading>
        </div>
        <flux:button variant="ghost" :href="route('accounting.chart-of-accounts')" wire:navigate>Back</flux:button>
    </div>

    @if ($errors->any())
        <flux:card class="border-red-300 bg-red-50 dark:bg-red-950">
            <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </flux:card>
    @endif

    <form wire:submit="save">
        <flux:card class="space-y-6">
            @include('pages.chart-of-accounts._form')
        </flux:card>

        <div class="mt-6 flex justify-end gap-4">
            <flux:button variant="ghost" :href="route('accounting.chart-of-accounts')" wire:navigate>Cancel</flux:button>
            <flux:button type="submit" variant="primary">Save Account</flux:button>
        </div>
    </form>
</div>
