<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareCapitalTransaction extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'share_capital_id', 'type', 'shares', 'amount',
        'balance_before', 'balance_after', 'performed_by', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'shares' => 'integer',
            'amount' => 'decimal:2',
            'balance_before' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function shareCapital()
    {
        return $this->belongsTo(ShareCapital::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
