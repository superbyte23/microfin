<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientDocument extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'client_id',
        'type',
        'file_path',
        'original_name',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
