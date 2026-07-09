# Microfinance Management System (MMS)

A production-ready Microfinance Management System built with Laravel 13, Livewire 4 SFC, and Flux UI v2.

## Tech Stack

- **Backend:** Laravel 13, PHP 8.4+
- **Frontend:** Livewire 4 (Single File Components), Alpine.js, Tailwind CSS, Flux UI v2
- **Database:** SQLite (dev) / MySQL 8+ (production)
- **Auth:** Laravel Starter Kit (Flux) with email verification, passkeys
- **RBAC:** spatie/laravel-permission (30 permissions, 7 roles)

## Requirements

- PHP 8.4+
- Composer
- Node.js (for Vite/Flux assets)

## Quick Start

```bash
cp .env.example .env
# Edit .env if needed (SQLite default)
composer install
npm install
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

## Default Accounts

| Email | Password | Role |
|---|---|---|
| `admin@microfin.app` | `password` | Admin (all permissions) |
| `manager@microfin.app` | `password` | Branch Manager |

## Roles & Permissions

**7 roles:** Admin, Branch Manager, Loan Officer, Collector, Cashier, Accountant, Auditor

**Permissions (30):** CRUD per module (users, branches, clients, loans, loan-products, savings, collections, cashiering, accounting) plus `loans.approve`, `loans.release`, `reports.view`, `settings.*`, `audit.view`.

Admin has all permissions. Other roles get scoped access (e.g., Collector can only view clients/loans and create collections).

## Project Structure

```
app/
├── Models/               # 19 Eloquent models
│   ├── User.php          # UUID, spatie HasRoles
│   ├── Branch.php        # UUID, soft deletes
│   ├── Client.php        # UUID, soft deletes, KYC
│   ├── Loan.php          # UUID, amortization schedule
│   ├── LoanProduct.php   # UUID, interest config
│   ├── SavingsAccount.php
│   ├── ShareCapital.php
│   ├── ChartOfAccount.php
│   ├── JournalEntry.php  # Double-entry
│   └── ...               # Transaction models
├── Services/
│   └── InterestCalculator.php   # Flat, Declining, Add-on
├── Traits/
│   └── HasUuid.php       # UUID primary key trait
database/
├── migrations/           # 8 migration files
└── seeders/
    ├── RoleSeeder.php
    ├── ChartOfAccountSeeder.php  # 28 standard COA accounts
    └── DatabaseSeeder.php
resources/
└── views/
    ├── layouts/          # Sidebar + Header layouts
    └── pages/            # 43 Livewire SFC pages
        ├── branches/
        ├── users/
        ├── roles/
        ├── clients/
        ├── loans/
        ├── loan-products/
        ├── savings/
        ├── savings-products/
        ├── share-capital/
        ├── collections/
        ├── cashiering/
        ├── accounting/
        └── reports/
routes/
├── web.php               # 43 route definitions
└── settings.php
```

## Data Flow

### Loan Lifecycle

```
Client Registration
        ↓
Loan Application (pending)
        ↓
Loan Approval (approved)  ← loan officer/manager approves
        ↓
Loan Release (active)     ← generates amortization schedule
        ↓
Collection                 ← payments recorded via Collections
        ↓
Loan Closure (closed)     ← all installments paid
```

### Interest Computation (3 methods)

| Method | Formula | Use Case |
|---|---|---|
| **Flat Rate** | Interest = Principal × Rate × Term/12 | Simple, predictable payments |
| **Declining Balance** | PMT formula: P × r(1+r)^n / ((1+r)^n − 1) | Standard loan amortization |
| **Add-on Rate** | Total Interest = Principal × Rate × Term/12, then divided evenly | Higher effective rate |

Interest is computed at loan release time and stored in the amortization schedule.

### Payment Collection Flow

```
Collector selects active loan
        ↓
Enters payment amount + method (cash/transfer/check/mobile)
        ↓
System allocates payment across due installments (FIFO)
        ↓
Each installment marked paid/partial
        ↓
If total paid ≥ total due → loan auto-closed
```

### Double-Entry Accounting

```
Journal Entry
├── Line 1: Account A (Debit)   = Line 2: Account B (Credit)
└── Must balance (debits = credits)

Chart of Accounts: 28 standard accounts
  - Assets (1010-1401): Cash, Loans Receivable, Fixed Assets
  - Liabilities (2010-2300): Savings Deposits, Loans Payable
  - Equity (3010-3030): Share Capital, Retained Earnings
  - Income (4010-4100): Loan Interest, Fees
  - Expenses (5010-5100): Salaries, Rent, Utilities
```

### Cashiering Session Flow

```
Open Session (set opening balance)
        ↓
Record Cash In/Out throughout the day
        ↓
Close Session (auto-calculates closing balance)
```

## Modules

### Administration
- **Branches:** Multi-branch management with soft deletes
- **Users:** Staff accounts with role assignment per branch
- **Roles & Permissions:** Granular RBAC with grouped permission UI

### Client Management
- Individual/Group/Corporate clients
- KYC fields, documents, photos
- GPS coordinates for field tracking
- Full name computed from first/middle/last/suffix

### Loan Management
- **Loan Products:** Configurable interest type, rate, term, amount range
- **Loans:** Full lifecycle (pending → approved → active → closed)
- **Amortization Schedule:** Auto-generated on release, per-installment tracking
- **Transactions:** Payment history with method tracking

### Savings & Share Capital
- **Savings Products:** Configurable interest, minimums, fees
- **Savings Accounts:** Auto-generated account numbers, deposit/withdrawal transactions
- **Share Capital:** Share tracking with purchase/refund history

### Collections & Cashiering
- **Collections:** Loan payment recording with FIFO allocation to installments
- **Cashiering:** Daily cash session management (open/close, cash in/out)

### Accounting
- **Chart of Accounts:** 28 standard MFI accounts with parent-child hierarchy
- **Journal Entries:** Double-entry with debit/credit validation
- **Trial Balance:** Date-filtered with running totals

### Reports
- Loan Portfolio (filter by branch/product/status)
- Collections Summary (by date range, method breakdown)
- Savings Summary
- Trial Balance (date-filtered)

## Database Schema

All 19 tables use UUID primary keys, `timestamps()`, and `softDeletes()` where applicable.

Key relationships:
- Users → Branch (belongs to)
- Clients → Branch, ClientGroup
- Loans → Client, Branch, LoanProduct, LoanOfficer
- Loans → AmortizationSchedule (has many)
- Loans → Transactions (has many)
- SavingsAccounts → Client, Branch, SavingsProduct
- SavingsAccounts → SavingsTransactions (has many)
- ShareCapital → Client, Branch
- JournalEntry → JournalItems (has many)
- JournalItem → ChartOfAccount

## Commands

```bash
# Run tests
php artisan test

# Fresh seed
php artisan migrate:fresh --seed

# Clear cache
php artisan optimize:clear

# Build assets
npm run build
npm run dev
```

## Future Roadmap

- REST API (Sanctum) for mobile app
- Flutter mobile app (offline collections, GPS, camera)
- Email/SMS notifications
- PDF/Excel exports
- Audit trail logging
- Loan restructuring & renewals
- Advanced financial reports (PAR, Aging, Income Statement, Balance Sheet)
- Payment gateway integrations
