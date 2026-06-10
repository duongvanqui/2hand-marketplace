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

    public function startConversation(Request $request, $productId)
    {
        // Lấy thông tin sản phẩm
        $product = \App\Models\Product::findOrFail($productId);

        $buyerId = Auth::id(); // ID của bạn (người đang đăng nhập)
        $sellerId = $product->user_id; // ID của người bán (Lưu ý: Nếu bảng products của bạn dùng cột khác như 'seller_id' thì nhớ sửa lại cho đúng nhé)

        // Chặn người dùng tự nhắn tin cho chính sản phẩm của mình
        if ($buyerId === $sellerId) {
            return back()->with('error', 'Bạn không thể tự nhắn tin cho sản phẩm của chính mình!');
        }

        // Kiểm tra xem phòng chat giữa 2 người về sản phẩm này đã tồn tại chưa
        $conversation = \App\Models\Conversation::where('buyer_id', $buyerId)
            ->where('seller_id', $sellerId)
            ->where('product_id', $product->id)
            ->first();

        // Nếu chưa từng chat, tạo phòng chat mới
        if (!$conversation) {
            $conversation = \App\Models\Conversation::create([
                'buyer_id'   => $buyerId,
                'seller_id'  => $sellerId,
                'product_id' => $product->id,
            ]);
        }

        // Chuyển hướng người dùng sang trang giao diện Chat (ID phòng chat tương ứng)
        // Lưu ý: Đảm bảo route 'chat.show' là cái route trỏ tới trang chat của bạn
        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id
        ]);
    }

    public function index()
{
    $userId = Auth::id();

    // Lấy danh sách phòng chat, nạp sẵn thông tin Sản phẩm, Người mua, Người bán
    $conversations = \App\Models\Conversation::where('buyer_id', $userId)
                        ->orWhere('seller_id', $userId)
                        ->with(['product', 'buyer', 'seller'])
                        ->orderBy('updated_at', 'desc')
                        ->get();

    return view('chat.index', compact('conversations'));
}
}
