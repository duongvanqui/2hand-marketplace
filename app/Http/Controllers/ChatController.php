<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Product; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // ========================================================
    // 1. TẢI DANH SÁCH TIN NHẮN TRONG PHÒNG CHAT
    // ========================================================
    public function fetchMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        // Kiểm tra bảo mật: Chỉ người trong cuộc mới được xem
        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Bạn không có quyền truy cập phòng chat này.');
        }

        // Đánh dấu tin nhắn của đối phương gửi là "Đã đọc"
        Message::where('conversation_id', $conversationId)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::with('sender')->where('conversation_id', $conversationId)->get();

        return response()->json($messages);
    }

    // ========================================================
    // 2. XỬ LÝ GỬI TIN NHẮN VÀ HÌNH ẢNH
    // ========================================================
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120' // Tối đa 5MB
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json(['error' => 'Vui lòng nhập tin nhắn hoặc chọn ảnh'], 422);
        }

        $conversation = Conversation::findOrFail($conversationId);

        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Bạn không có quyền gửi tin vào phòng chat này.');
        }

        // --------------------------------------------------------
        // KHÓA CHAT KHI SẢN PHẨM ĐÃ BÁN
        // --------------------------------------------------------
        if ($conversation->product && $conversation->product->status === 'sold') {
            if ($conversation->buyer_id != $conversation->product->buyer_id) {
                return response()->json(['error' => 'Sản phẩm này đã được bán. Phòng chat đã bị đóng!'], 403);
            }
        }

        // Xử lý lưu ảnh
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat_images', 'public');
        }

        // ĐÃ FIX LỖI SẬP DATABASE: Dùng fallback `?? ''` để nhét chuỗi rỗng thay vì NULL nếu chỉ gửi ảnh
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => Auth::id(),
            'message' => $request->message ?? '', 
            'image_path' => $imagePath,
        ]);

        $message->load('sender');
        
        // Cập nhật thời gian updated_at của Conversation để nhảy lên Top hộp thư
        $conversation->touch();

        // Phát sự kiện realtime
        broadcast(new MessageSent($message))->toOthers();

        return response()->json(['status' => 'success', 'message' => $message]);
    }

    // ========================================================
    // 3. TẠO HOẶC MỞ PHÒNG CHAT TỪ TRANG CHI TIẾT SẢN PHẨM
    // ========================================================
    public function startConversation(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $buyerId = Auth::id();
        $sellerId = $product->user_id;

        // Không cho phép tự chat với chính mình
        if ($buyerId === $sellerId) {
            return response()->json(['error' => 'Bạn không thể tự nhắn tin cho sản phẩm của chính mình!']);
        }

        $conversation = Conversation::firstOrCreate([
            'buyer_id'   => $buyerId,
            'seller_id'  => $sellerId,
            'product_id' => $product->id,
        ]);

        return response()->json([
            'success' => true,
            'conversation_id' => $conversation->id,
            'partner_name' => $product->user->name 
        ]);
    }

    // ========================================================
    // 4. TẠO HOẶC MỞ PHÒNG CHAT TRỰC TIẾP (TỪ TRANG CÁ NHÂN)
    // ========================================================
    public function startUserChat(User $user)
    {
        $authId = Auth::id();
        
        if ($authId === $user->id) {
            return response()->json(['error' => 'Bạn không thể tự nhắn tin cho chính mình.']);
        }

        // Bỏ qua liên kết sản phẩm (product_id = null)
        $conversation = Conversation::firstOrCreate([
            'buyer_id' => min($authId, $user->id),
            'seller_id' => max($authId, $user->id),
            'product_id' => null, 
        ]);

        return response()->json([
            'success' => true, 
            'conversation_id' => $conversation->id,
            'partner_name' => $user->name 
        ]);
    }

    // ========================================================
    // 5. HIỂN THỊ TRANG QUẢN LÝ HỘP THƯ (DASHBOARD)
    // ========================================================
    public function index()
    {
        $userId = Auth::id();

        $conversations = Conversation::where(function($q) use ($userId) {
                            $q->where('buyer_id', $userId)
                              ->orWhere('seller_id', $userId);
                        })
                        ->has('messages') // Giấu các phòng chat rỗng (chưa có tin nhắn)
                        ->with(['product', 'buyer', 'seller'])
                        ->orderBy('updated_at', 'desc')
                        ->get();

        return view('chat.index', compact('conversations'));
    }

    // ========================================================
    // 6. XÓA CUỘC TRÒ CHUYỆN
    // ========================================================
    public function destroy($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (Auth::id() !== $conversation->buyer_id && Auth::id() !== $conversation->seller_id) {
            abort(403, 'Không có quyền xóa.');
        }

        $conversation->messages()->delete();
        $conversation->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa đoạn chat thành công!']);
    }

    // ========================================================
    // 7. LẤY TỔNG SỐ TIN NHẮN CHƯA ĐỌC ĐỂ HIỂN THỊ CHẤM ĐỎ
    // ========================================================
    public function getUnreadCount()
    {
        $count = Message::whereHas('conversation', function($q) {
            $q->where('buyer_id', Auth::id())->orWhere('seller_id', Auth::id());
        })
        ->where('sender_id', '!=', Auth::id())
        ->where('is_read', false)
        ->count();

        return response()->json(['count' => $count]);
    }
}