<?php

use Livewire\Attributes\Layout;
use Livewire\Component;

new #[Layout('layouts::app')] class extends Component {
};
?>

<div>
    <div class="flex items-center justify-between mb-6">
        <div>
            <flux:heading size="xl">Accounting</flux:heading>
            <flux:subheading>General ledger and financial management</flux:subheading>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Chart of Accounts</flux:heading>
                <flux:subheading>Manage your chart of accounts</flux:subheading>
            </flux:card.header>
            <flux:card.footer class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('accounting.chart-of-accounts')" wire:navigate>View Accounts</flux:button>
            </flux:card.footer>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Journal Entries</flux:heading>
                <flux:subheading>Record and view journal entries</flux:subheading>
            </flux:card.header>
            <flux:card.footer class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('accounting.journals')" wire:navigate>View Journals</flux:button>
            </flux:card.footer>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Trial Balance</flux:heading>
                <flux:subheading>View trial balance report</flux:subheading>
            </flux:card.header>
            <flux:card.footer class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('accounting.trial-balance')" wire:navigate>View Report</flux:button>
            </flux:card.footer>
        </flux:card>

        <flux:card>
            <flux:card.header>
                <flux:heading size="lg">Reports</flux:heading>
                <flux:subheading>Income statement and balance sheet</flux:subheading>
            </flux:card.header>
            <flux:card.footer class="border-t border-zinc-200 dark:border-zinc-700">
                <flux:button variant="primary" :href="route('reports.index')" wire:navigate>View Reports</flux:button>
            </flux:card.footer>
        </flux:card>
    </div>
</div>
