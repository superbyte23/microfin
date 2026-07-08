<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['code' => '1010', 'name' => 'Cash on Hand', 'type' => 'asset'],
            ['code' => '1020', 'name' => 'Cash in Bank', 'type' => 'asset'],
            ['code' => '1100', 'name' => 'Loan Receivables', 'type' => 'asset'],
            ['code' => '1101', 'name' => 'Loan Interest Receivable', 'type' => 'asset'],
            ['code' => '1200', 'name' => 'Accounts Receivable', 'type' => 'asset'],
            ['code' => '1300', 'name' => 'Prepaid Expenses', 'type' => 'asset'],
            ['code' => '1400', 'name' => 'Fixed Assets', 'type' => 'asset'],
            ['code' => '1401', 'name' => 'Accumulated Depreciation', 'type' => 'asset'],
            ['code' => '2010', 'name' => 'Accounts Payable', 'type' => 'liability'],
            ['code' => '2020', 'name' => 'Accrued Expenses', 'type' => 'liability'],
            ['code' => '2030', 'name' => 'Interest Payable', 'type' => 'liability'],
            ['code' => '2100', 'name' => 'Client Savings Deposits', 'type' => 'liability'],
            ['code' => '2200', 'name' => 'Share Capital Payable', 'type' => 'liability'],
            ['code' => '2300', 'name' => 'Loans Payable', 'type' => 'liability'],
            ['code' => '3010', 'name' => 'Share Capital', 'type' => 'equity'],
            ['code' => '3020', 'name' => 'Retained Earnings', 'type' => 'equity'],
            ['code' => '3030', 'name' => 'Current Year Earnings', 'type' => 'equity'],
            ['code' => '4010', 'name' => 'Loan Interest Income', 'type' => 'income'],
            ['code' => '4020', 'name' => 'Loan Service Fees', 'type' => 'income'],
            ['code' => '4030', 'name' => 'Savings Account Fees', 'type' => 'income'],
            ['code' => '4100', 'name' => 'Other Income', 'type' => 'income'],
            ['code' => '5010', 'name' => 'Salaries and Wages', 'type' => 'expense'],
            ['code' => '5020', 'name' => 'Rent Expense', 'type' => 'expense'],
            ['code' => '5030', 'name' => 'Utilities', 'type' => 'expense'],
            ['code' => '5040', 'name' => 'Office Supplies', 'type' => 'expense'],
            ['code' => '5050', 'name' => 'Depreciation', 'type' => 'expense'],
            ['code' => '5060', 'name' => 'Loan Loss Provision', 'type' => 'expense'],
            ['code' => '5070', 'name' => 'Interest Expense', 'type' => 'expense'],
            ['code' => '5100', 'name' => 'Other Expenses', 'type' => 'expense'],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::create($account);
        }
    }
}
