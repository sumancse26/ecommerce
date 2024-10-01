<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at'];
    public function brand()
    {

        return $this->belongsTo(Brand::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function  reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function prodDetail()
    {
        return $this->hasOne(ProductDetail::class);
    }
    public function prodSlider()
    {
        return $this->hasOne(ProductSlider::class);
    }

    public function productWish()
    {

        return $this->hasMany(ProductWish::class);
    }

    public function productCart()
    {
        return $this->hasMany(ProductCart::class);
    }

    public function productInvoice()
    {

        return $this->hasMany(InvoiceProduct::class);
    }
}
