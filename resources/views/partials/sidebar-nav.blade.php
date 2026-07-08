<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Dashboard')" class="grid">
        <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
            {{ __('Dashboard') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Administration')">
        <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.*')" wire:navigate>
            {{ __('Users') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="building-storefront" :href="route('branches.index')" :current="request()->routeIs('branches.*')" wire:navigate>
            {{ __('Branches') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="shield-check" :href="route('roles.index')" :current="request()->routeIs('roles.*')" wire:navigate>
            {{ __('Roles & Permissions') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Client Management')">
        <flux:sidebar.item icon="user-group" :href="route('clients.index')" :current="request()->routeIs('clients.*')" wire:navigate>
            {{ __('Clients') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Loan Management')">
        <flux:sidebar.item icon="banknotes" :href="route('loan-products.index')" :current="request()->routeIs('loan-products.*')" wire:navigate>
            {{ __('Loan Products') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="document-text" :href="route('loans.index')" :current="request()->routeIs('loans.*')" wire:navigate>
            {{ __('Loans') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Savings')">
        <flux:sidebar.item icon="piggy-bank" :href="route('savings.index')" :current="request()->routeIs('savings.*')" wire:navigate>
            {{ __('Savings Accounts') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="currency-dollar" :href="route('share-capital.index')" :current="request()->routeIs('share-capital.*')" wire:navigate>
            {{ __('Share Capital') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Collections & Cashiering')">
        <flux:sidebar.item icon="cash" :href="route('collections.index')" :current="request()->routeIs('collections.*')" wire:navigate>
            {{ __('Collections') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="calculator" :href="route('cashiering.index')" :current="request()->routeIs('cashiering.*')" wire:navigate>
            {{ __('Cashiering') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Accounting')">
        <flux:sidebar.item icon="chart-bar" :href="route('accounting.index')" :current="request()->routeIs('accounting.index')" wire:navigate>
            {{ __('Chart of Accounts') }}
        </flux:sidebar.item>
        <flux:sidebar.item icon="document-duplicate" :href="route('accounting.journals')" :current="request()->routeIs('accounting.journals')" wire:navigate>
            {{ __('Journal Entries') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:sidebar.nav>
    <flux:sidebar.group :heading="__('Reports')">
        <flux:sidebar.item icon="presentation-chart" :href="route('reports.index')" :current="request()->routeIs('reports.*')" wire:navigate>
            {{ __('Reports') }}
        </flux:sidebar.item>
    </flux:sidebar.group>
</flux:sidebar.nav>

<flux:spacer />

<flux:sidebar.nav>
    <flux:sidebar.item icon="cog" :href="route('profile.edit')" :current="request()->routeIs('profile.edit')" wire:navigate>
        {{ __('Settings') }}
    </flux:sidebar.item>
</flux:sidebar.nav>
