<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavingsAccount extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'client_id', 'branch_id', 'savings_product_id',
        'account_number', 'balance', 'status',
    ];

    protected function casts(): array
    {
        return [
            'balance' => 'decimal:2',
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function product()
    {
        return $this->belongsTo(SavingsProduct::class, 'savings_product_id');
    }

    public function transactions()
    {
        return $this->hasMany(SavingsTransaction::class);
    }
}
