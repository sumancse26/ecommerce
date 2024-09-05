<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $fillable = ['email', 'otp'];

    public function profile()
    {
        return $this->hasOne(CustomerProfile::class);
    }

    public function productWish()
    {
        return $this->hasMany(ProductWish::class);
    }

    public function productCart()
    {
        return $this->hasMany(ProductCart::class);
    }

    public function invoice()
    {

        return $this->hasMany(Invoice::class);
    }

    public function invoiceProduct()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
