<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 
        'seller_id', 
        'product_id'
    ];

    // Quan hệ: Thuộc về Người mua
    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    // Quan hệ: Thuộc về Người bán
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Quan hệ: Trò chuyện về Sản phẩm nào
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ: Có nhiều Tin nhắn
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}