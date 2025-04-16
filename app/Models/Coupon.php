<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = ['wholeSite','products','categories','subCategories','startDate','endDate','status','name','description','value','type','code','min_quantity','is_combo'];
}
