@extends('layouts.app')

@section('title', 'Hộp thư tin nhắn - 2HAND')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex h-[600px] overflow-hidden">
        
        {{-- CỘT TRÁI: DANH SÁCH CUỘC TRÒ CHUYỆN --}}
        <div class="w-1/3 bg-gray-50 border-r border-gray-200 flex flex-col">
            <div class="p-4 border-b border-gray-200 bg-white">
                <h2 class="font-black text-gray-800 text-lg"><i class="fa-brands fa-facebook-messenger text-green-600 mr-2"></i>Tin nhắn của bạn</h2>
            </div>
            
            <div class="flex-1 overflow-y-auto">
                @if($conversations->isEmpty())
                    <div class="p-8 text-center text-gray-400">
                        <i class="fa-regular fa-comments text-4xl mb-3"></i>
                        <p class="text-sm">Chưa có cuộc trò chuyện nào.</p>
                    </div>
                @else
                    @foreach($conversations as $conv)
                        @php
                            $isBuyer = $conv->buyer_id === Auth::id();
                            $partner = $isBuyer ? $conv->seller : $conv->buyer;
                            $roleText = $isBuyer ? 'Người bán' : 'Người mua';
                        @endphp
                        
                        <div onclick="loadFullConversation({{ $conv->id }}, '{{ $partner->name }}', '{{ $conv->product->title }}')" 
                             class="conv-item-full p-4 border-b border-gray-100 hover:bg-green-50 cursor-pointer transition flex gap-3 items-center group"
                             id="full-conv-{{ $conv->id }}">
                            
                            <div class="w-12 h-12 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold shrink-0 border border-green-200">
                                {{ substr($partner->name, 0, 1) }}
                            </div>
                            
                            <div class="overflow-hidden flex-1">
                                <div class="flex justify-between items-baseline mb-1">
                                    <h4 class="font-bold text-gray-800 truncate">{{ $partner->name }}</h4>
                                    <span class="text-[10px] bg-gray-200 text-gray-600 px-2 py-0.5 rounded">{{ $roleText }}</span>
                                </div>
                                <p class="text-xs text-green-600 font-medium truncate"><i class="fa-solid fa-box-open mr-1"></i> {{ $conv->product->title }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- CỘT PHẢI: KHUNG TRÒ CHUYỆN --}}
        <div class="w-2/3 flex flex-col bg-white relative">
            <div id="full-empty-state" class="absolute inset-0 z-10 bg-white flex flex-col items-center justify-center text-gray-400">
                <i class="fa-regular fa-paper-plane text-6xl mb-4 text-green-100"></i>
                <p class="font-medium text-gray-500">Chọn một cuộc trò chuyện để bắt đầu nhắn tin</p>
            </div>

            <div class="p-4 border-b border-gray-200 bg-white flex items-center gap-3 shadow-sm z-0">
                <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div>
                    <h3 id="full-partner-name" class="font-bold text-gray-800">Đang tải...</h3>
                    <p id="full-product-name" class="text-xs text-gray-500 truncate">...</p>
                </div>
            </div>

            <div id="full-chat-box" class="flex-1 overflow-y-auto p-4 bg-gray-50/50 flex flex-col gap-3"></div>

            <form id="full-chat-form" class="p-4 border-t border-gray-100 bg-white flex gap-2">
                <input type="text" id="full-message-input" autocomplete="off" placeholder="Nhập tin nhắn..." disabled class="flex-1 bg-gray-100 border-transparent rounded-xl px-4 py-3 text-sm focus:bg-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all outline-none disabled:opacity-50">
                <button type="submit" id="full-submit-btn" disabled class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-xl font-bold transition flex items-center gap-2 shadow-md shadow-green-200 disabled:opacity-50">
                    Gửi <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
        </div>
        
    </div>
</div>

<script>
    let activeFullConvId = null;
    const currentUserId = {{ Auth::id() }};
    const fullChatBox = document.getElementById('full-chat-box');
    const fullChatForm = document.getElementById('full-chat-form');
    const fullMsgInput = document.getElementById('full-message-input');
    const fullSubmitBtn = document.getElementById('full-submit-btn');

    function appendFullMessage(msg, isMe) {
        const alignClass = isMe ? 'self-end bg-green-600 text-white rounded-bl-xl' : 'self-start bg-white border border-gray-200 text-gray-800 rounded-br-xl';
        const html = `<div class="max-w-[70%] px-4 py-2 mt-1 shadow-sm text-sm rounded-t-xl ${alignClass}">${msg.message}</div>`;
        fullChatBox.insertAdjacentHTML('beforeend', html);
        fullChatBox.scrollTop = fullChatBox.scrollHeight;
    }

    window.loadFullConversation = function(convId, partnerName, productName) {
        if(activeFullConvId === convId) return; 
        activeFullConvId = convId;

        // Mở khóa ô nhập liệu
        fullMsgInput.disabled = false;
        fullSubmitBtn.disabled = false;

        document.getElementById('full-empty-state').style.display = 'none';
        document.getElementById('full-partner-name').innerText = partnerName;
        document.getElementById('full-product-name').innerText = "Sản phẩm: " + productName;
        
        document.querySelectorAll('.conv-item-full').forEach(el => el.classList.remove('bg-green-50', 'border-l-4', 'border-green-500'));
        document.getElementById(`full-conv-${convId}`).classList.add('bg-green-50', 'border-l-4', 'border-green-500');

        fullChatBox.innerHTML = '<div class="text-center text-gray-400 mt-5"><i class="fa-solid fa-circle-notch fa-spin"></i> Đang tải...</div>';
        
        fetch(`/chat/${convId}/messages`)
            .then(res => res.json())
            .then(messages => {
                fullChatBox.innerHTML = ''; 
                messages.forEach(msg => appendFullMessage(msg, msg.sender_id === currentUserId));

                if (window.Echo) {
                    if(activeFullConvId) {
                        window.Echo.leave(`chat.${activeFullConvId}`);
                    }
                    window.Echo.private(`chat.${convId}`)
                        .listen('.message.sent', (e) => {
                            if (e.message.sender_id !== currentUserId) {
                                appendFullMessage(e.message, false);
                            }
                        });
                }
            });
    };

    fullChatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = fullMsgInput.value.trim();
        if (!text || !activeFullConvId) return;

        fullMsgInput.value = ''; 
        appendFullMessage({ message: text }, true); 

        fetch(`/chat/${activeFullConvId}/messages`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ message: text })
        }).catch(err => alert("Lỗi khi gửi tin nhắn!"));
    });
</script>
@endsection