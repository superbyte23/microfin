<?php

namespace Database\Seeders;

use App\Models\LoanProduct;
use Illuminate\Database\Seeder;

class LoanProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Micro Enterprise Loan',
                'code' => 'MEL',
                'description' => 'For micro-entrepreneurs and small business owners. Declining balance interest rate.',
                'interest_type' => 'declining',
                'interest_rate' => 30.00,
                'min_amount' => 5000.00,
                'max_amount' => 50000.00,
                'default_term_months' => 12,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Salary Loan',
                'code' => 'SAL',
                'description' => 'For employed individuals with regular monthly income. Flat interest rate.',
                'interest_type' => 'flat',
                'interest_rate' => 18.00,
                'min_amount' => 3000.00,
                'max_amount' => 30000.00,
                'default_term_months' => 6,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Microfinance Loan',
                'code' => 'MFL',
                'description' => 'Grameen-style microfinance with weekly payments and center meetings.',
                'interest_type' => 'declining',
                'interest_rate' => 30.00,
                'min_amount' => 2000.00,
                'max_amount' => 20000.00,
                'default_term_months' => 6,
                'payment_frequency' => 'weekly',
                'status' => 'active',
            ],
            [
                'name' => 'Agri Loan',
                'code' => 'AGL',
                'description' => 'For farmers and agricultural workers. Flexible terms aligned with harvest cycles.',
                'interest_type' => 'declining',
                'interest_rate' => 24.00,
                'min_amount' => 5000.00,
                'max_amount' => 100000.00,
                'default_term_months' => 12,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Livelihood Loan',
                'code' => 'LVL',
                'description' => 'For self-employed individuals and informal sector workers.',
                'interest_type' => 'declining',
                'interest_rate' => 30.00,
                'min_amount' => 3000.00,
                'max_amount' => 30000.00,
                'default_term_months' => 12,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Housing Microfinance',
                'code' => 'HML',
                'description' => 'For home improvement, renovation, and incremental housing.',
                'interest_type' => 'declining',
                'interest_rate' => 18.00,
                'min_amount' => 10000.00,
                'max_amount' => 200000.00,
                'default_term_months' => 24,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Education Loan',
                'code' => 'EDL',
                'description' => 'For tuition and school-related expenses of dependents.',
                'interest_type' => 'flat',
                'interest_rate' => 12.00,
                'min_amount' => 5000.00,
                'max_amount' => 50000.00,
                'default_term_months' => 12,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Emergency Loan',
                'code' => 'EML',
                'description' => 'Quick-release loan for urgent personal or family needs.',
                'interest_type' => 'flat',
                'interest_rate' => 24.00,
                'min_amount' => 1000.00,
                'max_amount' => 10000.00,
                'default_term_months' => 3,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'OFW Loan',
                'code' => 'OFW',
                'description' => 'For overseas Filipino workers and their families. Higher loanable amount.',
                'interest_type' => 'declining',
                'interest_rate' => 24.00,
                'min_amount' => 10000.00,
                'max_amount' => 100000.00,
                'default_term_months' => 12,
                'payment_frequency' => 'monthly',
                'status' => 'active',
            ],
            [
                'name' => 'Negosyo Loan',
                'code' => 'NEG',
                'description' => 'For sari-sari store owners and small-scale vendors. Weekly collection.',
                'interest_type' => 'declining',
                'interest_rate' => 28.00,
                'min_amount' => 5000.00,
                'max_amount' => 25000.00,
                'default_term_months' => 6,
                'payment_frequency' => 'weekly',
                'status' => 'active',
            ],
        ];

        foreach ($products as $product) {
            LoanProduct::create($product);
        }
    }
}
