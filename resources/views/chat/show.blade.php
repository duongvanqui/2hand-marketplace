@extends('layouts.app')

@section('title', 'Phòng Chat Mặc Cả')

@section('content')
<div class="max-w-4xl mx-auto my-8 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-[600px]">
    
    {{-- Phần Header --}}
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between rounded-t-2xl">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">
                <i class="fa-solid fa-store"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-800">Phòng Chat Sản Phẩm #{{ $conversation->product_id }}</h3>
                <p class="text-xs text-green-500 font-medium flex items-center gap-1">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Đang kết nối Real-time
                </p>
            </div>
        </div>
    </div>

    {{-- Phần hiển thị tin nhắn --}}
    <div id="chat-box" class="flex-1 p-4 overflow-y-auto bg-gray-50/50 flex flex-col gap-3">
        {{-- Tin nhắn sẽ được Javascript đổ vào đây --}}
    </div>

    {{-- Phần nhập tin nhắn --}}
    <div class="p-4 border-t border-gray-100 bg-white rounded-b-2xl">
        <form id="chat-form" class="flex gap-2 relative">
            <input type="text" id="message-input" autocomplete="off" placeholder="Nhập tin nhắn mặc cả..." class="flex-1 bg-gray-100 border-transparent rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-bold transition shadow-md shadow-indigo-200">
                <i class="fa-solid fa-paper-plane"></i> Gửi
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT NHẬN VÀ GỬI TIN NHẮN REAL-TIME --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', function () {
        const conversationId = {{ $conversation->id }};
        const currentUserId = {{ auth()->id() }};
        const chatBox = document.getElementById('chat-box');
        const chatForm = document.getElementById('chat-form');
        const messageInput = document.getElementById('message-input');

        // Hàm vẽ tin nhắn ra màn hình
        function appendMessage(message, isMe) {
            const alignmentClass = isMe ? 'self-end bg-indigo-600 text-white rounded-bl-xl' : 'self-start bg-white border border-gray-100 text-gray-800 rounded-br-xl';
            
            const msgHTML = `
                <div class="max-w-[70%] px-4 py-2 rounded-t-xl shadow-sm ${alignmentClass}">
                    <p class="text-sm">${message.message}</p>
                </div>
            `;
            chatBox.insertAdjacentHTML('beforeend', msgHTML);
            chatBox.scrollTop = chatBox.scrollHeight; // Tự động cuộn xuống cuối
        }

        // 1. Tải tin nhắn cũ khi vừa vào phòng
        window.axios.get(`/chat/${conversationId}/messages`).then(response => {
            response.data.forEach(msg => {
                appendMessage(msg, msg.sender_id === currentUserId);
            });
        });

        // 2. Lắng nghe sóng Real-time từ Pusher (Phép màu ở đây)
        // Lưu ý: Có dấu chấm trước 'message.sent' vì chúng ta đã khai báo broadcastAs()
        window.Echo.private(`chat.${conversationId}`)
            .listen('.message.sent', (e) => {
                // Nếu tin nhắn nhận được KHÔNG phải do mình gửi thì mới in ra (tránh bị in lặp 2 lần)
                if (e.message.sender_id !== currentUserId) {
                    appendMessage(e.message, false);
                }
            });

        // 3. Xử lý khi nhấn nút Gửi
        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = messageInput.value.trim();
            if (!text) return;

            // Xóa rỗng ô nhập liệu ngay lập tức cho mượt
            messageInput.value = '';

            // Vẽ ngay tin nhắn lên màn hình của mình cho cảm giác tốc độ cao
            appendMessage({ message: text, sender_id: currentUserId }, true);

            // Gửi ngầm lên Server
            window.axios.post(`/chat/${conversationId}/messages`, {
                message: text
            }).catch(error => {
                alert("Lỗi khi gửi tin nhắn!");
            });
        });
    });
</script>
@endsection