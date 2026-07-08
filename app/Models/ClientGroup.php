<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientGroup extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'branch_id',
        'leader_id',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function leader()
    {
        return $this->belongsTo(Client::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(Client::class, 'group_id');
    }
}
