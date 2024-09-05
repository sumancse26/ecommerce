<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'total',
        'discount',
        'vat',
        'payable',
        'cus_details',
        'ship_details',
        'shipping_method',
        'tran_id',
        'delivery_status',
        'payment_status',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoiceProduct()
    {
        return $this->hasMany(InvoiceProduct::class);
    }
}
