<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->string('interest_type'); // flat, declining, add-on
            $table->decimal('interest_rate', 5, 2);
            $table->decimal('min_amount', 12, 2)->default(0);
            $table->decimal('max_amount', 12, 2)->nullable();
            $table->integer('default_term_months');
            $table->string('payment_frequency'); // monthly, weekly, bi-monthly, daily
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_product_id');
            $table->uuid('client_id');
            $table->uuid('branch_id');
            $table->uuid('loan_officer_id')->nullable();
            $table->uuid('approved_by')->nullable();
            $table->decimal('principal_amount', 12, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->string('interest_type'); // captured from product
            $table->integer('term_months');
            $table->string('payment_frequency');
            $table->date('release_date')->nullable();
            $table->date('maturity_date')->nullable();
            $table->date('first_payment_date')->nullable();
            $table->text('purpose')->nullable();
            $table->string('status')->default('pending'); // pending, approved, active, closed, rejected, written-off
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('loan_product_id')->references('id')->on('loan_products')->cascadeOnDelete();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('loan_officer_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('loan_amortization_schedules', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_id');
            $table->integer('installment_number');
            $table->date('due_date');
            $table->decimal('principal', 12, 2);
            $table->decimal('interest', 12, 2);
            $table->decimal('total_due', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->date('paid_date')->nullable();
            $table->string('status')->default('pending'); // pending, paid, partial, overdue
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->cascadeOnDelete();
            $table->index(['loan_id', 'installment_number']);
        });

        Schema::create('loan_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('loan_id');
            $table->uuid('client_id');
            $table->uuid('collected_by');
            $table->uuid('branch_id');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method'); // cash, bank_transfer, check, mobile_money
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('loan_id')->references('id')->on('loans')->cascadeOnDelete();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('collected_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_transactions');
        Schema::dropIfExists('loan_amortization_schedules');
        Schema::dropIfExists('loans');
        Schema::dropIfExists('loan_products');
    }
};
