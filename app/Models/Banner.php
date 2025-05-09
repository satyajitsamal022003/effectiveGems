<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'image',
        'sort_order',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];
}
