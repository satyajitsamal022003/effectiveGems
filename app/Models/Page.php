<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $table = 'pages';

    protected $fillable = [
        'pageName',
        'heading',
        'description',
        'sortOrder',
        'status',
        'seoUrl',
        'metaTitle',
        'metaDescription',
        'metaKeyword',
    ];
}
