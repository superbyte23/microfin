<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Loan extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'loan_product_id',
        'client_id',
        'branch_id',
        'loan_officer_id',
        'approved_by',
        'principal_amount',
        'interest_rate',
        'interest_type',
        'term_months',
        'payment_frequency',
        'release_date',
        'maturity_date',
        'first_payment_date',
        'purpose',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'principal_amount' => 'decimal:2',
            'interest_rate' => 'decimal:2',
            'release_date' => 'date',
            'maturity_date' => 'date',
            'first_payment_date' => 'date',
        ];
    }

    public function product()
    {
        return $this->belongsTo(LoanProduct::class, 'loan_product_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function loanOfficer()
    {
        return $this->belongsTo(User::class, 'loan_officer_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function amortizationSchedule()
    {
        return $this->hasMany(LoanAmortizationSchedule::class);
    }

    public function transactions()
    {
        return $this->hasMany(LoanTransaction::class);
    }
}
