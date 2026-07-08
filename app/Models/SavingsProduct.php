<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingsProduct extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'description', 'interest_rate',
        'min_balance', 'min_deposit', 'withdrawal_fee', 'status',
    ];

    protected function casts(): array
    {
        return [
            'interest_rate' => 'decimal:2',
            'min_balance' => 'decimal:2',
            'min_deposit' => 'decimal:2',
            'withdrawal_fee' => 'decimal:2',
        ];
    }

    public function accounts()
    {
        return $this->hasMany(SavingsAccount::class);
    }
}
