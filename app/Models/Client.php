<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    // Define the table associated with this model
    protected $table = 'clients';

    // Define which fields are mass assignable
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'address',
        'date_of_birth',
        'gender',
        'id_number',
        'employment_status',
        'employer',
        'monthly_income',
        'marital_status',
        'dependents',
        'client_status',
        'profile_image',
        'notes'
    ];

    // Optional: Disable timestamps if not needed
    // public $timestamps = false;

    // Accessor: Example of combining first and last name into full_name
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Mutator: Example of hashing password or handling specific field transformations
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    // Relations: Example of relation with loans (if there is a Loan model)
    public function loans()
    {
        return $this->hasMany(Loan::class, 'client_id');
    }
}
