<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Euser extends Authenticatable
{
    use Notifiable;

    protected $table = 'eusers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'is_mobile_verified'
    ];

    protected $hidden = [
        'password',
    ];
}
