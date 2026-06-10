<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 
        'seller_id', 
        'product_id', 
        'total_amount', 
        'fee_amount', 
        'seller_amount', 
        'payment_method', 
        'status', 
        'receiver_name',
        'phone_number', 
        'shipping_address'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}