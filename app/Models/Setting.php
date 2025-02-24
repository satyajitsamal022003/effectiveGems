<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone1',
        'phone2',
        'email1',
        'email2',
        'address',
        'workingHour',
        'fbLink',
        'twitterLink',
        'instaLink',
        'youtubeLink',
        'heading1',
        'announcement_text',
        'description1',
        'image',
        'button',
        'header_script',
        'footer_script',
    ];
}
