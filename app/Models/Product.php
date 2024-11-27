<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use HasFactory, Searchable;
    
    protected $table = 'product';
    
    protected $fillable = [
        'productName', 'variantName', 'categoryId', 'subCategoryId', 'price_type', 'priceDiscounted', 'priceMRP', 
        'priceB2C', 'priceB2B', 'min_product_qty', 'max_product_qty', 'image1', 'imageDesc1', 'imageCaption1', 
        'imageTitle1', 'imageAlt1', 'image2', 'imageDesc2', 'imageCaption2', 'imageTitle2', 'imageAlt2', 'image3', 
        'imageDesc3', 'imageCaption3', 'imageTitle3', 'imageAlt3', 'icon', 'description', 'productHeading5', 'productHeading4', 'productHeading3', 
        'productHeading2', 'productHeading1', 'seoUrl', 'sortOrder', 'sortOrderPopular', 'sortOrderCategory', 
        'sortOrderSubCategory', 'metaTitle', 'metaKeyword', 'metaDescription', 'metaImage', 'productDesc5', 'productDesc4', 'productDesc3', 
        'productDesc2', 'productDesc1', 'is_variant', 'variant', 'courierTypeId', 'activationId', 'certificationId', 
        'out_of_stock', 'imageAlt', 'imageTitle', 'imageCaption', 'imageDesc', 'status', 'featured_product', 
        'on_top', 'created_at', 'updated_at', 'default_product_heading4', 'default_product_description4'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'categoryId');
    }
    
    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subCategoryId');
    }
    
    public function activation()
    {
        return $this->belongsTo(Activations::class, 'activationId');
    }

    public function certificate()
    {
        return $this->belongsTo(Certification::class, 'certificationId');
    }
    
    public function courierType()
    {
        return $this->belongsTo(Couriertype::class, 'courierTypeId', 'id');
    }
}
