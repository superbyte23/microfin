<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

class Client extends Model
{
    use HasApiTokens, HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'type',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birth_date',
        'gender',
        'civil_status',
        'phone',
        'email',
        'address',
        'gps_lat',
        'gps_lng',
        'photo_path',
        'branch_id',
        'group_id',
        'status',
        'kyc_fields',
        'member_id',
        'pin',
    ];

    protected $hidden = [
        'pin',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'kyc_fields' => 'array',
            'pin' => 'hashed',
        ];
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function group()
    {
        return $this->belongsTo(ClientGroup::class);
    }

    public function documents()
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function getFullNameAttribute(): string
    {
        $parts = array_filter([$this->first_name, $this->middle_name, $this->last_name, $this->suffix]);
        return implode(' ', $parts);
    }
}
