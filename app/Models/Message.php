<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 
        'sender_id', 
        'message', 
        'is_read'
    ];

    // Quan hệ: Nằm trong Phòng chat nào
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // Quan hệ: Ai là người gửi dòng tin này
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}