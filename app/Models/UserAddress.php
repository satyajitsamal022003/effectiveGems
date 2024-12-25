<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'country_id',
        'state_id',
        'city_name',
        'zip_code',
        'landmark',
        'apartment',
        'address',
        'address_type'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }
}
