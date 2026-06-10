<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // 1. Lấy danh sách tin nhắn của một phòng chat
    public function fetchMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Kiểm tra bảo mật: Chỉ người trong cuộc mới được xem tin nhắn
        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Bạn không có quyền truy cập phòng chat này.');
        }

        // Trả về danh sách tin nhắn cùng với thông tin người gửi
        $messages = Message::with('sender')->where('conversation_id', $conversationId)->get();

        return response()->json($messages);
    }

    // 2. Xử lý Gửi tin nhắn mới
    public function sendMessage(Request $request, $conversationId)
    {
        // Kiểm tra nội dung tin nhắn không được rỗng
        $request->validate([
            'message' => 'required|string'
        ]);

        $conversation = Conversation::findOrFail($conversationId);

        // Kiểm tra bảo mật
        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Bạn không có quyền gửi tin vào phòng chat này.');
        }

        // Lưu tin nhắn vào Database
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Lấy thêm thông tin user gửi để đưa lên màn hình (Avatar, Tên...)
        $message->load('sender');

        // PHÉP THUẬT NẰM Ở ĐÂY: Kích hoạt sự kiện phát sóng lên Pusher
        broadcast(new MessageSent($message))->toOthers();

        // Trả về tin nhắn vừa gửi cho Frontend
        return response()->json(['status' => 'success', 'message' => $message]);
    }
}