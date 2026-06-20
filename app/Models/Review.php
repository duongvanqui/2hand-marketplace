<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'receiver_id',
        'rating',
        'comment',
    ];

    // Thuộc về đơn hàng nào
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Ai là người đánh giá (Người mua)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Ai là người nhận đánh giá (Người bán)
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}