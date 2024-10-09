<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class SubCategory extends Model
{
    use HasFactory;
    protected $table = 'sub_category';
	protected $fillable = ['categoryId','seoUrl','subCategoryName','image','imageAlt','imageTitle','imageCaption','imageDesc','banner','description','class','sortOrder','metaTitle','metaKeyword','metaDescription','onTop','onFooter','status'];


    public function Category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }
}
