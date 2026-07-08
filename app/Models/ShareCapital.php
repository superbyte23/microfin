<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShareCapital extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'client_id', 'branch_id', 'total_shares',
        'share_price', 'total_amount', 'status',
    ];

    protected function casts(): array
    {
        return [
            'total_shares' => 'integer',
            'share_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
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

    public function transactions()
    {
        return $this->hasMany(ShareCapitalTransaction::class);
    }
}
