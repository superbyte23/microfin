<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Administration
    Route::livewire('users', 'pages::users.index')->name('users.index');
    Route::livewire('branches', 'pages::branches.index')->name('branches.index');
    Route::livewire('roles', 'pages::roles.index')->name('roles.index');

    // Client Management
    Route::livewire('clients', 'pages::clients.index')->name('clients.index');

    // Loan Management
    Route::livewire('loan-products', 'pages::loan-products.index')->name('loan-products.index');
    Route::livewire('loans', 'pages::loans.index')->name('loans.index');

    // Savings
    Route::livewire('savings', 'pages::savings.index')->name('savings.index');
    Route::livewire('share-capital', 'pages::share-capital.index')->name('share-capital.index');

    // Collections & Cashiering
    Route::livewire('collections', 'pages::collections.index')->name('collections.index');
    Route::livewire('cashiering', 'pages::cashiering.index')->name('cashiering.index');

    // Accounting
    Route::livewire('accounting', 'pages::accounting.index')->name('accounting.index');
    Route::livewire('accounting/journals', 'pages::accounting.journals')->name('accounting.journals');

    // Reports
    Route::livewire('reports', 'pages::reports.index')->name('reports.index');
});

require __DIR__.'/settings.php';
