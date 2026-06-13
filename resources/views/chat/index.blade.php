@extends('layouts.admin')

@section('title', 'Hộp thư tin nhắn - 2HAND')
@section('header_title', 'Hộp thư tin nhắn')

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex overflow-hidden h-[calc(100vh-170px)]">
    
    {{-- ========================================== --}}
    {{-- CỘT TRÁI: DANH SÁCH CUỘC TRÒ CHUYỆN --}}
    {{-- ========================================== --}}
    <div class="w-1/3 min-w-[300px] max-w-[360px] bg-gray-50/30 border-r border-gray-100 flex flex-col z-10">
        
        {{-- Header Hộp thư: Logo 2HAND --}}
        <div class="p-5 border-b border-gray-100 bg-white sticky top-0 shrink-0">
            <h2 class="font-black text-gray-900 text-lg flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shadow-inner border border-emerald-200">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
                Hộp thư
            </h2>
        </div>
        
        <div class="flex-1 overflow-y-auto no-scrollbar py-2">
            @if($conversations->isEmpty())
                <div class="p-8 text-center text-gray-400 h-full flex flex-col items-center justify-center">
                    <i class="fa-regular fa-comments text-4xl mb-3 text-gray-200"></i>
                    <p class="text-sm font-medium">Hộp thư đang trống.</p>
                </div>
            @else
                @foreach($conversations as $conv)
                    @php
                        $isBuyer = $conv->buyer_id === Auth::id();
                        $partner = $isBuyer ? $conv->seller : $conv->buyer;
                        $roleText = $isBuyer ? 'Người bán' : 'Người mua';
                        $avatarUrl = $partner->avatar ? asset('storage/' . $partner->avatar) : '';
                        $firstLetter = substr($partner->name, 0, 1);

                        // LẤY SỐ TIN NHẮN CHƯA ĐỌC CỦA CUỘC TRÒ CHUYỆN NÀY
                        $unreadInConv = \App\Models\Message::where('conversation_id', $conv->id)
                            ->where('sender_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    
                    {{-- Mục hội thoại --}}
                    <div onclick="loadFullConversation({{ $conv->id }}, '{{ addslashes($partner->name) }}')" 
                         class="conv-item-full p-4 border-b border-gray-50 hover:bg-emerald-50 cursor-pointer transition-all flex items-center gap-3 relative group"
                         id="full-conv-{{ $conv->id }}">
                        
                        <div class="absolute inset-y-0 left-0 w-1 bg-emerald-500 rounded-r-full transform scale-y-0 group-hover:scale-y-100 transition-transform duration-300 active-conv-bar"></div>

                        {{-- Avatar --}}
                        <div class="w-11 h-11 bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-600 rounded-full flex items-center justify-center font-bold text-lg shrink-0 border border-white shadow-sm overflow-hidden ml-1 relative">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                            @else
                                {{ $firstLetter }}
                            @endif
                        </div>

                        <div class="flex-1 overflow-hidden space-y-0.5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1.5 truncate pr-2">
                                    <h4 class="font-bold text-gray-900 text-sm truncate">{{ $partner->name }}</h4>
                                    
                                    {{-- HUY HIỆU ĐỎ CHO TIN NHẮN MỚI --}}
                                    @if($unreadInConv > 0)
                                        <span id="unread-badge-{{ $conv->id }}" class="bg-red-500 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm">
                                            {{ $unreadInConv > 99 ? '99+' : $unreadInConv }}
                                        </span>
                                    @endif
                                </div>
                                
                                <span class="text-[9px] font-bold bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded whitespace-nowrap">{{ $roleText }}</span>
                            </div>
                            <p class="text-[11px] text-emerald-600 font-medium truncate">
                                <i class="fa-solid fa-box-open mr-1"></i> {{ $conv->product->title }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- CỘT PHẢI: KHUNG TRÒ CHUYỆN CHI TIẾT --}}
    {{-- ========================================== --}}
    <div class="flex-1 flex flex-col bg-white relative">
        
        <div id="full-empty-state" class="absolute inset-0 z-20 bg-white flex flex-col items-center justify-center text-gray-400">
            <i class="fa-regular fa-paper-plane text-6xl text-emerald-100 mb-4"></i>
            <h3 class="text-lg font-black text-gray-800 mb-1">Tin nhắn 2HAND</h3>
            <p class="font-medium text-gray-500 text-sm">Chọn một cuộc trò chuyện ở bên trái để bắt đầu</p>
        </div>

        {{-- HEADER KHUNG CHAT --}}
        <div class="p-5 border-b border-gray-100 bg-white flex items-center justify-between z-10 shrink-0 h-[75px]">
            <div>
                <h3 id="full-partner-name" class="font-black text-gray-900 text-lg leading-tight ml-2">...</h3>
                <div class="flex items-center gap-1 mt-1 ml-2">
                    <span class="flex items-center gap-1.5 text-[10px] text-emerald-500 font-medium">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online
                    </span>
                </div>
            </div>

            <form id="delete-chat-form" action="" method="POST" onsubmit="return confirm('CẢNH BÁO: Xóa vĩnh viễn đoạn chat này?')">
                @csrf @method('DELETE')
                <button type="submit" id="delete-chat-btn" disabled class="w-9 h-9 rounded-full hover:bg-red-50 text-gray-400 hover:text-red-500 transition-colors flex items-center justify-center disabled:opacity-0" title="Xóa đoạn chat">
                    <i class="fa-solid fa-trash-can text-lg"></i>
                </button>
            </form>
        </div>

        <div id="full-chat-box" class="flex-1 overflow-y-auto p-6 bg-white flex flex-col gap-4"></div>

        <div id="image-preview-container" class="hidden p-3 bg-white border-t border-gray-100 shrinkage-0">
            <div class="relative inline-block">
                <img id="image-preview" src="" class="h-16 rounded-xl border border-gray-200 object-cover shadow-sm">
                <button type="button" id="remove-image-btn" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-[10px] hover:bg-red-600 shadow-md flex items-center justify-center">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- FORM NHẬP TIN NHẮN --}}
        <div class="p-4 border-t border-gray-100 bg-white shrink-0">
            <form id="full-chat-form" class="flex items-end gap-3 relative">
                
                <label class="p-2 mb-1 text-gray-400 hover:text-emerald-500 cursor-pointer transition-colors" :class="{'opacity-50 pointer-events-none': !activeFullConvId}" title="Đính kèm ảnh">
                    <i class="fa-regular fa-image text-2xl"></i>
                    <input type="file" id="image-input" accept="image/png, image/jpeg, image/webp" class="hidden" disabled>
                </label>

                <textarea id="full-message-input" rows="1" disabled placeholder="Nhập tin nhắn..." 
                    class="flex-1 bg-gray-50 border border-gray-200 rounded-2xl px-5 py-3 text-sm focus:bg-white focus:ring-1 focus:ring-emerald-400 transition-all resize-none outline-none disabled:opacity-50 leading-relaxed no-scrollbar" 
                    style="min-height: 46px; max-height: 120px;"></textarea>
                
                <button type="submit" id="full-submit-btn" disabled class="p-2 mb-1 text-emerald-500 hover:text-emerald-600 transition-colors disabled:opacity-50 disabled:text-gray-300">
                    <i class="fa-solid fa-paper-plane text-2xl"></i>
                </button>
            </form>
        </div>
        
    </div>
</div>
@endsection

@section('scripts')
<style>
    .conv-item-full.active-conv { background-color: rgb(236 253 245); }
    .conv-item-full.active-conv .active-conv-bar { transform: scaleY(1); }
</style>
<script>
    let activeFullConvId = null;
    const currentUserId = {{ Auth::id() }};
    
    const fullChatBox = document.getElementById('full-chat-box');
    const fullChatForm = document.getElementById('full-chat-form');
    const fullMsgInput = document.getElementById('full-message-input');
    const fullSubmitBtn = document.getElementById('full-submit-btn');
    const imageInput = document.getElementById('image-input');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');
    const deleteChatForm = document.getElementById('delete-chat-form');
    const deleteChatBtn = document.getElementById('delete-chat-btn');

    fullMsgInput.addEventListener('input', function() {
        this.style.height = '46px';
        this.style.height = (this.scrollHeight < 120 ? this.scrollHeight : 120) + 'px';
    });

    imageInput.addEventListener('change', function() {
        if(this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        previewContainer.classList.add('hidden');
        imagePreview.src = '';
    });

    function appendFullMessage(msg, isMe) {
        const wrapperClass = isMe ? 'flex justify-end' : 'flex justify-start';
        const bubbleClass = isMe ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-2xl rounded-tr-sm shadow-md' : 'bg-gray-100 text-gray-800 rounded-2xl rounded-tl-sm';
        
        let contentHTML = '';
        if (msg.image_path && msg.image_path.startsWith('data:image')) {
            contentHTML += `<img src="${msg.image_path}" class="rounded-xl mb-1 max-w-[200px] object-cover border border-black/5 shadow-sm">`;
        } else if (msg.image_path) {
            contentHTML += `<a href="/storage/${msg.image_path}" target="_blank"><img src="/storage/${msg.image_path}" class="rounded-xl mb-1 max-w-[200px] object-cover hover:opacity-90 transition border border-black/5 shadow-sm"></a>`;
        }
        
        if (msg.message) {
            contentHTML += `<p class="text-[14px] leading-relaxed">${msg.message.replace(/\n/g, '<br>')}</p>`;
        }

        const html = `
            <div class="${wrapperClass} animate-fade-in-up mt-1">
                <div class="max-w-[75%] px-4 py-2.5 ${bubbleClass}">
                    ${contentHTML}
                </div>
            </div>`;
        fullChatBox.insertAdjacentHTML('beforeend', html);
        fullChatBox.scrollTop = fullChatBox.scrollHeight;
    }

    window.loadFullConversation = function(convId, partnerName) {
        if(activeFullConvId === convId) return; 
        
        if(window.Echo && activeFullConvId) { window.Echo.leave(`chat.${activeFullConvId}`); }
        
        activeFullConvId = convId;

        fullMsgInput.disabled = false;
        fullSubmitBtn.disabled = false;
        imageInput.disabled = false;
        deleteChatBtn.disabled = false;
        document.getElementById('full-empty-state').style.display = 'none';

        document.getElementById('full-partner-name').innerText = partnerName;
        deleteChatForm.action = `/chat/${convId}`;
        
        document.querySelectorAll('.conv-item-full').forEach(el => el.classList.remove('active-conv'));
        document.getElementById(`full-conv-${convId}`).classList.add('active-conv');

        // TỰ ĐỘNG XÓA HUY HIỆU ĐỎ KHI BẤM VÀO
        const badge = document.getElementById(`unread-badge-${convId}`);
        if(badge) {
            badge.style.display = 'none';
            // Cập nhật lại số tổng trên thanh Navbar
            if (typeof window.syncUnreadCount === 'function') {
                setTimeout(window.syncUnreadCount, 500);
            }
        }

        fullChatBox.innerHTML = '<div class="text-center text-gray-400 mt-10"><i class="fa-solid fa-circle-notch fa-spin text-3xl mb-2"></i></div>';
        
        fetch(`/chat/${convId}/messages`)
            .then(res => res.json())
            .then(messages => {
                fullChatBox.innerHTML = '<div class="text-center my-4"><span class="text-[10px] font-bold text-gray-400 bg-gray-50 px-3 py-1 rounded-full uppercase tracking-wider border border-gray-200">Bắt đầu cuộc trò chuyện</span></div>'; 
                messages.forEach(msg => appendFullMessage(msg, msg.sender_id === currentUserId));

                if (window.Echo) {
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
        const file = imageInput.files[0];

        if ((!text && !file) || !activeFullConvId) return;

        let tempMsg = {};
        if(text) tempMsg.message = text;
        if(file) tempMsg.image_path = imagePreview.src;
        appendFullMessage(tempMsg, true); 

        fullMsgInput.value = ''; 
        fullMsgInput.style.height = '46px';
        removeImageBtn.click();

        let formData = new FormData();
        if (text) formData.append('message', text);
        if (file) formData.append('image', file);

        fetch(`/chat/${activeFullConvId}/messages`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        }).catch(err => alert("Lỗi gửi tin hoặc ảnh quá lớn!"));
    });

    fullMsgInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            fullChatForm.dispatchEvent(new Event('submit'));
        }
    });
</script>
@endsection