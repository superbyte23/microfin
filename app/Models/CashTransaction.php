<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'cash_session_id', 'type', 'category', 'amount',
        'balance_before', 'balance_after',
        'reference_id', 'reference_type', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function session()
    {
        return $this->belongsTo(CashSession::class, 'cash_session_id');
    }
}
