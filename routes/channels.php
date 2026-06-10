<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Cấp quyền nghe ngóng kênh 'chat.{id_phong}'
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    
    // Nếu phòng chat tồn tại VÀ người đang truy cập là MUA hoặc BÁN của phòng đó -> Cho phép (return true)
    if ($conversation) {
        return $user->id === $conversation->buyer_id || $user->id === $conversation->seller_id;
    }
    
    return false;
});