<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('savings_products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->decimal('min_balance', 12, 2)->default(0);
            $table->decimal('min_deposit', 12, 2)->default(0);
            $table->decimal('withdrawal_fee', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('savings_accounts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id');
            $table->uuid('branch_id');
            $table->uuid('savings_product_id');
            $table->string('account_number')->unique();
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
            $table->foreign('savings_product_id')->references('id')->on('savings_products')->cascadeOnDelete();
        });

        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('savings_account_id');
            $table->string('type'); // deposit, withdrawal, transfer, interest, fee
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->string('reference_number')->nullable();
            $table->uuid('performed_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('savings_account_id')->references('id')->on('savings_accounts')->cascadeOnDelete();
            $table->foreign('performed_by')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('share_capitals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id');
            $table->uuid('branch_id');
            $table->integer('total_shares')->default(0);
            $table->decimal('share_price', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnDelete();
            $table->foreign('branch_id')->references('id')->on('branches')->cascadeOnDelete();
        });

        Schema::create('share_capital_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('share_capital_id');
            $table->string('type'); // purchase, refund
            $table->integer('shares');
            $table->decimal('amount', 12, 2);
            $table->decimal('balance_before', 12, 2);
            $table->decimal('balance_after', 12, 2);
            $table->uuid('performed_by');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('share_capital_id')->references('id')->on('share_capitals')->cascadeOnDelete();
            $table->foreign('performed_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('share_capital_transactions');
        Schema::dropIfExists('share_capitals');
        Schema::dropIfExists('savings_transactions');
        Schema::dropIfExists('savings_accounts');
        Schema::dropIfExists('savings_products');
    }
};
