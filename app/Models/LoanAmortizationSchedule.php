<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanAmortizationSchedule extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'loan_id',
        'installment_number',
        'due_date',
        'principal',
        'interest',
        'total_due',
        'paid_amount',
        'paid_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'principal' => 'decimal:2',
            'interest' => 'decimal:2',
            'total_due' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'due_date' => 'date',
            'paid_date' => 'date',
        ];
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
