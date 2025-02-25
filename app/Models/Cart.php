<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userDetails()
    {
        return $this->belongsTo(Euser::class, 'userId', 'id'); 
        // Replace 'user_id' with 'userId' if needed
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function productDetails()
    {
        return $this->hasManyThrough(Product::class, CartItem::class, 'cart_id', 'id', 'id', 'product_id');
    }
    
}
