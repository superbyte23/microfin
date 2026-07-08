<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Administration
    Route::livewire('users', 'pages::users.index')->name('users.index');
    Route::livewire('users/create', 'pages::users.create')->name('users.create');
    Route::livewire('users/{user}', 'pages::users.edit')->name('users.edit');
    Route::livewire('branches', 'pages::branches.index')->name('branches.index');
    Route::livewire('branches/create', 'pages::branches.create')->name('branches.create');
    Route::livewire('branches/{branch}', 'pages::branches.edit')->name('branches.edit');
    Route::livewire('roles', 'pages::roles.index')->name('roles.index');
    Route::livewire('roles/create', 'pages::roles.create')->name('roles.create');
    Route::livewire('roles/{role}', 'pages::roles.edit')->name('roles.edit');

    // Client Management
    Route::livewire('clients', 'pages::clients.index')->name('clients.index');
    Route::livewire('clients/create', 'pages::clients.create')->name('clients.create');
    Route::livewire('clients/{client}', 'pages::clients.edit')->name('clients.edit');

    // Loan Management
    Route::livewire('loan-products', 'pages::loan-products.index')->name('loan-products.index');
    Route::livewire('loan-products/create', 'pages::loan-products.create')->name('loan-products.create');
    Route::livewire('loan-products/{product}', 'pages::loan-products.edit')->name('loan-products.edit');
    Route::livewire('loans', 'pages::loans.index')->name('loans.index');
    Route::livewire('loans/create', 'pages::loans.create')->name('loans.create');
    Route::livewire('loans/{loan}', 'pages::loans.show')->name('loans.show');
    Route::livewire('loans/{loan}/edit', 'pages::loans.edit')->name('loans.edit');

    // Savings
    Route::livewire('savings-products', 'pages::savings-products.index')->name('savings-products.index');
    Route::livewire('savings-products/create', 'pages::savings-products.create')->name('savings-products.create');
    Route::livewire('savings-products/{product}', 'pages::savings-products.edit')->name('savings-products.edit');
    Route::livewire('savings-accounts', 'pages::savings.index')->name('savings.index');
    Route::livewire('savings-accounts/create', 'pages::savings.create')->name('savings.create');
    Route::livewire('savings-accounts/{account}', 'pages::savings.show')->name('savings.show');
    Route::livewire('share-capital', 'pages::share-capital.index')->name('share-capital.index');
    Route::livewire('share-capital/create', 'pages::share-capital.create')->name('share-capital.create');
    Route::livewire('share-capital/{share_capital}', 'pages::share-capital.edit')->name('share-capital.edit');

    // Collections & Cashiering
    Route::livewire('collections', 'pages::collections.index')->name('collections.index');
    Route::livewire('cashiering', 'pages::cashiering.index')->name('cashiering.index');

    // Accounting
    Route::livewire('accounting', 'pages::accounting.index')->name('accounting.index');
    Route::livewire('accounting/chart-of-accounts', 'pages::accounting.chart-of-accounts')->name('accounting.chart-of-accounts');
    Route::livewire('accounting/chart-of-accounts/create', 'pages::accounting.chart-of-accounts-create')->name('accounting.chart-of-accounts.create');
    Route::livewire('accounting/chart-of-accounts/{account}', 'pages::accounting.chart-of-accounts-edit')->name('accounting.chart-of-accounts.edit');
    Route::livewire('accounting/journals', 'pages::accounting.journals')->name('accounting.journals');
    Route::livewire('accounting/journals/create', 'pages::accounting.journals-create')->name('accounting.journals.create');
    Route::livewire('accounting/journals/{journal}', 'pages::accounting.journals-show')->name('accounting.journals.show');
    Route::livewire('accounting/trial-balance', 'pages::accounting.trial-balance')->name('accounting.trial-balance');

    // Reports
    Route::livewire('reports', 'pages::reports.index')->name('reports.index');
});

require __DIR__.'/settings.php';
