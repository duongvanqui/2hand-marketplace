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

        // ========================================================
        // TÍNH NĂNG MỚI: ĐÁNH DẤU LÀ "ĐÃ ĐỌC" ĐỂ TẮT THÔNG BÁO ĐỎ
        // Cập nhật tất cả tin nhắn do ĐỐI PHƯƠNG gửi thành đã đọc
        // ========================================================
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // Trả về danh sách tin nhắn cùng với thông tin người gửi
        $messages = Message::with('sender')->where('conversation_id', $conversationId)->get();

        return response()->json($messages);
    }

    // 2. Gửi tin nhắn (HỖ TRỢ GỬI ẢNH)
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120' // Max 5MB
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh'], 422);
        }

        $conversation = Conversation::findOrFail($conversationId);

        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Bạn không có quyền gửi tin vào phòng chat này.');
        }

        // Xử lý lưu ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message,
            'image_path' => $imagePath, // Lưu đường dẫn ảnh
            // Mặc định is_read trong DB sẽ tự động là false
        ]);

        $message->load('sender');

        // Cập nhật thời gian phòng chat để nó nhảy lên top hộp thư
        $conversation->touch();

        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    // 3. Tạo phòng chat mới từ trang chi tiết sản phẩm
    public function startConversation(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $buyerId = Auth::id();
        $sellerId = $product->user_id;

        // Chặn người dùng tự nhắn tin cho chính mình
        if ($buyerId === $sellerId) {
            return response()->json(['error' => 'Bạn không thể tự nhắn tin cho sản phẩm của chính mình!']);
        }

        // Tìm phòng chat cũ hoặc tạo phòng mới nếu chưa có
        $conversation = \App\Models\Conversation::firstOrCreate([
            'buyer_id'   => $buyerId,
            'seller_id'  => $sellerId,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id
        ]);
    }

    // 4. Lấy danh sách hộp thư (ẨN PHÒNG CHAT TRỐNG)
    public function index()
    {
        $userId = Auth::id();

        // Chỉ lấy những phòng chat CÓ ÍT NHẤT 1 TIN NHẮN (has('messages'))
        $conversations = \App\Models\Conversation::where(function($q) use ($userId) {
                            $q->where('buyer_id', $userId)
                              ->orWhere('seller_id', $userId);
                        })
                        ->has('messages') 
                        ->with(['product', 'buyer', 'seller'])
                        ->orderBy('updated_at', 'desc')
                        ->get();

        return view('chat.index', compact('conversations'));
    }

    // 5. XÓA CUỘC TRÒ CHUYỆN
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Không có quyền xóa.');
        }

        // Xóa phòng chat (tin nhắn con sẽ bị xóa theo nếu DB thiết lập cascade, hoặc bạn tự xóa tay)
        $conversation->messages()->delete();
        $conversation->delete();

        return redirect()->back()->with('success', 'Đã xóa đoạn chat thành công!');
    }

    public function getUnreadCount()
    {
        $count = \App\Models\Message::whereHas('conversation', function($q) {
            $q->where('buyer_id', Auth::id())->orWhere('seller_id', Auth::id());
        })
        ->where('sender_id', '!=', Auth::id())
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }
}