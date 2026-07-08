<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Reports</flux:heading>
            <flux:subheading>Generate and view reports</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <flux:card class="hover:border-blue-500 transition-colors cursor-pointer" onclick="window.location.href='{{ route('reports.loan-portfolio') }}'" wire:navigate>
            <div>
                <flux:heading size="lg">Loan Portfolio</flux:heading>
                <flux:subheading>Active loans, disbursements, repayments</flux:subheading>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700" class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('reports.loan-portfolio')" wire:navigate>View Report</flux:button>
            </div>
        </flux:card>

        <flux:card class="hover:border-blue-500 transition-colors cursor-pointer" onclick="window.location.href='{{ route('reports.collections') }}'" wire:navigate>
            <div>
                <flux:heading size="lg">Collections</flux:heading>
                <flux:subheading>Payment collections summary</flux:subheading>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700" class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('reports.collections')" wire:navigate>View Report</flux:button>
            </div>
        </flux:card>

        <flux:card class="hover:border-blue-500 transition-colors cursor-pointer" onclick="window.location.href='{{ route('reports.savings') }}'" wire:navigate>
            <div>
                <flux:heading size="lg">Savings</flux:heading>
                <flux:subheading>Savings accounts and balances</flux:subheading>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700" class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('reports.savings')" wire:navigate>View Report</flux:button>
            </div>
        </flux:card>

        <flux:card class="hover:border-blue-500 transition-colors cursor-pointer">
            <div>
                <flux:heading size="lg">Trial Balance</flux:heading>
                <flux:subheading>Financial trial balance</flux:subheading>
            </div>
            <div class="mt-4 pt-4 border-t border-zinc-200 dark:border-zinc-700" class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('accounting.trial-balance')" wire:navigate>View Report</flux:button>
            </div>
        </flux:card>
    </div>
</div>
