@auth
@if(!request()->routeIs('chat.index'))
@php
    // Lọc sạch chat rỗng
    $myConversations = \App\Models\Conversation::where(function($q) {
            $q->where('buyer_id', Auth::id())
              ->orWhere('seller_id', Auth::id());
        })
        ->has('messages')
        ->with(['product', 'buyer', 'seller'])
        ->orderBy('updated_at', 'desc')
        ->get();
@endphp

{{-- DÙNG CHUẨN ALPINE.DATA() ĐỂ CHỐNG LỖI TẢI TRANG --}}
<div x-data="chatWidget"
    @toggle-chat.window="isChatOpen = !isChatOpen; const dot = document.getElementById('chat-notification-dot'); if(isChatOpen && dot) dot.classList.add('hidden');"
    @load-conversation.window="loadNewChat($event.detail)"
    @message-arrived.window="handleIncomingMessage($event.detail)"
    x-show="isChatOpen"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-10 scale-95"
    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
    x-transition:leave-end="opacity-0 translate-y-10 scale-95"
    class="fixed bottom-4 right-4 md:right-8 w-[380px] sm:w-[800px] h-[600px] bg-white shadow-[0_20px_70px_-15px_rgba(0,0,0,0.3)] rounded-3xl flex border-2 border-gray-200 z-[99999] overflow-hidden pointer-events-auto"
>
    {{-- CỘT TRÁI: DANH SÁCH CHAT --}}
    <div class="w-[35%] bg-gray-50/50 border-r-2 border-gray-100 flex-col hidden sm:flex h-full relative z-10 shadow-[3px_0_10px_-5px_rgba(0,0,0,0.05)]">
        
        <div class="p-4 bg-white/80 backdrop-blur-sm border-b-2 border-gray-100 shrink-0 flex items-center justify-between z-20">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gradient-to-br from-green-400 to-emerald-600 rounded-lg flex items-center justify-center text-white text-xs shadow-md shadow-emerald-200/50 border border-emerald-400/50">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
                <span class="text-xl font-black uppercase tracking-tighter">
                    <span class="text-gray-900">2</span><span class="bg-gradient-to-r from-green-500 to-emerald-600 bg-clip-text text-transparent">HAND</span>
                </span>
            </div>
        </div>
        
        <div id="chat-list-container" class="flex-1 overflow-y-auto no-scrollbar p-2.5 space-y-1.5">
            @if($myConversations->isEmpty())
                <div class="text-center text-gray-400 h-full flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 border-2 border-white shadow-sm">
                        <i class="fa-regular fa-comments text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-xs font-bold text-gray-500">Hộp thư trống</p>
                </div>
            @else
                @foreach($myConversations as $conv)
                    @php
                        $isBuyer = $conv->buyer_id === Auth::id();
                        $partner = $isBuyer ? $conv->seller : $conv->buyer;
                        
                        // ĐÃ SỬA LỖI SẬP WEB DO NULL USER
                        $partnerName = $partner ? $partner->name : 'Người dùng đã xóa';
                        $partnerAvatar = ($partner && $partner->avatar) ? asset('storage/' . $partner->avatar) : null;
                        
                        $unreadInConv = \App\Models\Message::where('conversation_id', $conv->id)->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();
                        
                        $roleText = $conv->product ? ($isBuyer ? 'Người bán' : 'Người mua') : '';
                        $locked = false;
                        if($conv->product && $conv->product->status === 'sold' && $conv->buyer_id != $conv->product->buyer_id) {
                            $locked = true; 
                        }
                    @endphp
                    
                    <div @click="initChat({{ $conv->id }}, {{ $locked ? 'true' : 'false' }})" 
                         id="gconv-{{ $conv->id }}"
                         data-name="{{ $partnerName }}"
                         class="global-conv-item p-3 rounded-2xl border-2 border-transparent hover:border-emerald-100 hover:bg-emerald-50/50 cursor-pointer transition-all flex items-center gap-3 relative group">

                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-700 flex items-center justify-center font-black text-base shrink-0 border-2 border-white shadow-sm overflow-hidden uppercase">
                            @if($partnerAvatar)
                                <img src="{{ $partnerAvatar }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($partnerName, 0, 1) }}
                            @endif
                        </div>

                        <div class="flex-1 overflow-hidden space-y-1">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1.5 truncate pr-1">
                                    <h4 class="font-black text-gray-900 text-sm truncate">{{ $partnerName }}</h4>
                                    @if($unreadInConv > 0)
                                        <span id="floating-unread-badge-{{ $conv->id }}" class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm animate-pulse border border-white">
                                            {{ $unreadInConv > 99 ? '99+' : $unreadInConv }}
                                        </span>
                                    @endif
                                </div>
                                @if($roleText)
                                    <span class="text-[8px] font-bold bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-md whitespace-nowrap">{{ $roleText }}</span>
                                @endif
                            </div>
                            
                            @if($conv->product)
                                <p class="text-[10px] text-emerald-600 font-bold truncate">
                                    <i class="fa-solid fa-box-open text-emerald-400 mr-1"></i> {{ $conv->product->title }}
                                </p>
                            @else
                                <p class="text-[10px] text-blue-500 font-bold truncate">
                                    <i class="fa-solid fa-comments text-blue-400 mr-1"></i> Trò chuyện trực tiếp
                                </p>
                            @endif
                        </div>

                        @if($locked)
                            <div class="absolute inset-0 bg-gray-50/50 backdrop-blur-[1px] flex items-center justify-center rounded-2xl">
                                <i class="fa-solid fa-lock text-gray-400 text-xl drop-shadow-md"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- CỘT PHẢI: KHUNG TRÒ CHUYỆN --}}
    <div class="flex-1 flex flex-col bg-gray-50/30 h-full relative z-20">
        <div class="px-5 py-4 border-b-2 border-gray-200 bg-white/95 backdrop-blur flex justify-between items-center z-10 h-[70px] shrink-0 shadow-[0_5px_15px_-10px_rgba(0,0,0,0.05)]">
            <div>
                <span x-text="partnerName" class="font-black text-gray-900 text-lg leading-tight"></span>
                <div class="flex items-center gap-1.5 mt-0.5" x-show="activeConv">
                    <span class="flex items-center gap-1.5 text-[10px] text-emerald-500 font-bold">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_4px_#10b981]"></span> Đang hoạt động
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="deleteChat()" x-show="activeConv" class="w-8 h-8 rounded-lg text-gray-400 hover:bg-red-50 hover:text-red-500 hover:border-red-200 border-2 border-transparent transition-all flex items-center justify-center shadow-sm bg-white" title="Xóa đoạn chat">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
                <button @click="isChatOpen = false" class="w-8 h-8 rounded-lg text-gray-500 bg-gray-100 hover:bg-gray-200 hover:text-gray-800 transition-all flex items-center justify-center border-2 border-transparent shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div id="global-chat-box" class="flex-1 overflow-y-auto p-5 bg-transparent flex flex-col gap-3 shadow-inner">
            <div x-show="!activeConv" class="h-full flex flex-col items-center justify-center text-gray-400">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 border-4 border-emerald-50 shadow-sm">
                    <i class="fa-regular fa-paper-plane text-4xl text-emerald-400 ml-[-3px] mt-[3px]"></i>
                </div>
                <p class="text-sm font-bold text-gray-500">Chọn cuộc trò chuyện để bắt đầu</p>
            </div>
        </div>

        <div x-show="imagePreview" class="p-4 bg-white border-t-2 border-gray-100" style="display: none;">
            <div class="relative inline-block">
                <img :src="imagePreview" class="h-16 rounded-xl border-2 border-gray-200 object-cover shadow-sm">
                <button @click="removeImage()" type="button" class="absolute -top-3 -right-3 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600 shadow-md border-2 border-white hover:scale-110 transition-transform">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        
        <div x-show="isLocked && activeConv" class="p-4 bg-gray-50 border-t-2 border-gray-200 text-center text-gray-500 font-bold text-sm" style="display:none;">
            <i class="fa-solid fa-lock mr-1"></i> Sản phẩm đã bán. Phòng chat đã bị đóng.
        </div>

        <form @submit.prevent="sendMsg" x-show="!isLocked" enctype="multipart/form-data" class="px-5 py-4 border-t-2 border-gray-100 bg-white flex items-center gap-3 shrink-0">
            <label class="p-2.5 bg-blue-50 border-2 border-blue-200 text-blue-500 hover:text-blue-600 hover:bg-blue-100 hover:border-blue-300 rounded-xl cursor-pointer transition-all shadow-sm flex items-center justify-center" :class="{'opacity-50 pointer-events-none': !activeConv || isSending}" title="Gửi ảnh">
                <i class="fa-solid fa-camera-retro text-lg"></i>
                <input type="file" id="global-image-input" accept="image/*" class="hidden" @change="handleImageSelect" :disabled="!activeConv || isSending">
            </label>

            <input type="text" x-model="text" :disabled="!activeConv || isSending" class="flex-grow bg-white border-2 border-gray-200 rounded-2xl px-5 py-3 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 outline-none disabled:opacity-50 transition-all shadow-sm" placeholder="Viết tin nhắn..." autocomplete="off">
            
            <button type="submit" :disabled="!activeConv || isSending || (!text.trim() && !imageFile)" class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl shadow-md shadow-emerald-200/50 hover:from-emerald-600 hover:to-green-700 transition-all disabled:opacity-50 disabled:shadow-none flex items-center justify-center transform hover:-translate-y-0.5 active:translate-y-0">
                <template x-if="!isSending">
                    <i class="fa-solid fa-paper-plane text-lg ml-[-2px] mt-[2px]"></i>
                </template>
                <template x-if="isSending">
                    <i class="fa-solid fa-spinner fa-spin text-lg ml-[-2px] mt-[2px]"></i>
                </template>
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('chatWidget', () => ({
            isChatOpen: false,
            activeConv: null,
            text: '',
            imageFile: null,
            imagePreview: null,
            isSending: false, // ĐÃ SỬA: Thêm trạng thái Loading
            myId: {{ auth()->id() ?? 'null' }},
            partnerName: 'Hộp thư tin nhắn',
            isLocked: false,

            initChat(convId, locked = false) {
                this.isChatOpen = true;
                this.isLocked = locked;
                
                const dot = document.getElementById('chat-notification-dot');
                if(dot) dot.classList.add('hidden');
                
                if(!convId || this.activeConv === convId) return;
                this.activeConv = convId;
                
                const box = document.getElementById('global-chat-box');
                box.innerHTML = "<div class='text-center text-xs text-emerald-400 mt-24'><i class='fa-solid fa-circle-notch fa-spin text-4xl mb-3 text-emerald-300'></i><br><span class='font-bold text-gray-500'>Đang kết nối...</span></div>";

                document.querySelectorAll('.global-conv-item').forEach(el => {
                    el.classList.remove('bg-emerald-50', 'border-emerald-200');
                    el.classList.add('border-transparent');
                });
                
                const activeItem = document.getElementById('gconv-' + convId);
                if(activeItem) {
                    activeItem.classList.remove('border-transparent');
                    activeItem.classList.add('bg-emerald-50', 'border-emerald-200');
                    this.partnerName = activeItem.getAttribute('data-name');
                }

                const badge = document.getElementById('floating-unread-badge-' + convId);
                if(badge) badge.style.display = 'none';

                fetch(`/chat/${convId}/messages`)
                    .then(res => res.json())
                    .then(messages => {
                        box.innerHTML = "<div class='text-center my-6 relative'><div class='absolute inset-0 flex items-center'><div class='w-full border-t-2 border-gray-100'></div></div><span class='relative bg-gray-50 px-4 py-1.5 text-[9px] font-black text-gray-400 rounded-full uppercase tracking-widest border-2 border-gray-100'>Bắt đầu nhắn tin</span></div>";
                        messages.forEach(msg => this.appendMsg(msg, msg.sender_id === this.myId));
                        setTimeout(() => { if(window.syncUnreadCount) window.syncUnreadCount(); }, 500);
                    });
            },

            loadNewChat(data) {
                this.isChatOpen = true;
                this.activeConv = data.convId;
                this.partnerName = data.partnerName;
                this.isLocked = false;
                
                const dot = document.getElementById('chat-notification-dot');
                if(dot) dot.classList.add('hidden');

                const box = document.getElementById('global-chat-box');
                box.innerHTML = "<div class='text-center text-xs text-emerald-400 mt-24'><i class='fa-solid fa-circle-notch fa-spin text-4xl mb-3 text-emerald-300'></i><br><span class='font-bold text-gray-500'>Đang chuẩn bị phòng chat...</span></div>";

                document.querySelectorAll('.global-conv-item').forEach(el => {
                    el.classList.remove('bg-emerald-50', 'border-emerald-200');
                    el.classList.add('border-transparent');
                });
                
                const activeItem = document.getElementById('gconv-' + data.convId);
                if(activeItem) {
                    activeItem.classList.remove('border-transparent');
                    activeItem.classList.add('bg-emerald-50', 'border-emerald-200');
                }

                const badge = document.getElementById('floating-unread-badge-' + data.convId);
                if(badge) badge.style.display = 'none';

                fetch(`/chat/${data.convId}/messages`)
                    .then(res => res.json())
                    .then(messages => {
                        box.innerHTML = "<div class='text-center my-6 relative'><div class='absolute inset-0 flex items-center'><div class='w-full border-t-2 border-gray-100'></div></div><span class='relative bg-gray-50 px-4 py-1.5 text-[9px] font-black text-gray-400 rounded-full uppercase tracking-widest border-2 border-gray-100'>Hãy gửi lời chào đầu tiên</span></div>";
                        messages.forEach(msg => this.appendMsg(msg, msg.sender_id === this.myId));
                        setTimeout(() => { if(window.syncUnreadCount) window.syncUnreadCount(); }, 500);
                    });
            },

            handleIncomingMessage(msg) {
                if (this.activeConv === msg.conversation_id && this.isChatOpen) {
                    this.appendMsg(msg, false);
                    fetch(`/chat/${msg.conversation_id}/messages`);
                } else {
                    const convItem = document.getElementById('gconv-' + msg.conversation_id);
                    if (convItem) {
                        let badge = document.getElementById('floating-unread-badge-' + msg.conversation_id);
                        if (badge) {
                            let current = parseInt(badge.innerText.replace('+', '')) || 0;
                            badge.innerText = current + 1 > 99 ? '99+' : current + 1;
                            badge.style.display = 'inline-block';
                        } else {
                            const titleDiv = convItem.querySelector('.flex.items-center.gap-1\\.5.truncate');
                            if(titleDiv) {
                                titleDiv.insertAdjacentHTML('beforeend', `<span id="floating-unread-badge-${msg.conversation_id}" class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm animate-pulse border border-white">1</span>`);
                            }
                        }
                        convItem.parentNode.prepend(convItem);
                    } else {
                        const list = document.getElementById('chat-list-container');
                        if(list.querySelector('.text-center.text-gray-400')) {
                            list.innerHTML = ''; 
                        }
                        
                        // ĐÃ SỬA: Xử lý an toàn trường hợp sender bị null
                        const senderName = msg.sender ? msg.sender.name : 'Người lạ';
                        const avatarHtml = (msg.sender && msg.sender.avatar) 
                            ? `<img src="/storage/${msg.sender.avatar}" class="w-full h-full object-cover">`
                            : `${senderName.charAt(0)}`;
                            
                        const newHtml = `
                            <div onclick="window.dispatchEvent(new CustomEvent('load-conversation', { detail: { convId: ${msg.conversation_id}, partnerName: '${senderName}' } }))" 
                                 id="gconv-${msg.conversation_id}"
                                 data-name="${senderName}"
                                 class="global-conv-item p-3 rounded-2xl border-2 border-transparent hover:border-emerald-100 hover:bg-emerald-50/50 cursor-pointer transition-all flex items-center gap-3 relative group">

                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-700 flex items-center justify-center font-black text-base shrink-0 border-2 border-white shadow-sm overflow-hidden uppercase">
                                    ${avatarHtml}
                                </div>

                                <div class="flex-1 overflow-hidden space-y-1">
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center gap-1.5 truncate pr-1">
                                            <h4 class="font-black text-gray-900 text-sm truncate">${senderName}</h4>
                                            <span id="floating-unread-badge-${msg.conversation_id}" class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm animate-pulse border border-white">1</span>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-blue-500 font-bold truncate">
                                        <i class="fa-solid fa-comments text-blue-400 mr-1"></i> Tin nhắn mới...
                                    </p>
                                </div>
                            </div>
                        `;
                        list.insertAdjacentHTML('afterbegin', newHtml);
                    }
                }
            },

            appendMsg(msg, isMe) {
                const box = document.getElementById('global-chat-box');
                const alignWrapper = isMe ? 'flex justify-end' : 'flex justify-start';
                const roundedClass = isMe ? 'rounded-2xl rounded-tr-sm' : 'rounded-2xl rounded-tl-sm';
                
                const bubbleClass = isMe 
                    ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-md shadow-emerald-200 border border-emerald-400' 
                    : 'bg-white text-gray-800 shadow-sm border-2 border-gray-100';
                
                let contentHTML = '';
                if (msg.image_path && msg.image_path.startsWith('data:image')) {
                    contentHTML += `<img src="${msg.image_path}" class="rounded-xl mb-1.5 max-w-[180px] object-cover border-2 border-white/20 shadow-sm">`;
                } else if (msg.image_path) {
                    contentHTML += `<a href="/storage/${msg.image_path}" target="_blank"><img src="/storage/${msg.image_path}" class="rounded-xl mb-1.5 max-w-[180px] object-cover border-2 border-white/20 shadow-sm hover:opacity-90 transition"></a>`;
                }
                if (msg.message) {
                    const safeMsg = msg.message.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, '<br>');
                    contentHTML += `<p class="text-[13px] font-medium leading-relaxed">${safeMsg}</p>`;
                }

                box.insertAdjacentHTML('beforeend', `<div class="${alignWrapper} mt-1.5 animate-fade-in-up"><div class="max-w-[80%] px-4 py-2.5 ${roundedClass} ${bubbleClass}">${contentHTML}</div></div>`);
                box.scrollTop = box.scrollHeight;
            },

            handleImageSelect(event) {
                const file = event.target.files[0];
                if(file) {
                    this.imageFile = file;
                    const reader = new FileReader();
                    reader.onload = (e) => { this.imagePreview = e.target.result; };
                    reader.readAsDataURL(file);
                }
            },

            removeImage() {
                this.imageFile = null;
                this.imagePreview = null;
                document.getElementById('global-image-input').value = '';
            },

            // ĐÃ SỬA LỖI MẤT CHỮ TRƯỚC KHI GỬI & THÊM HIỆU ỨNG LOADING
            sendMsg() {
                if((!this.text.trim() && !this.imageFile) || !this.activeConv || this.isLocked || this.isSending) return;
                
                let formData = new FormData();
                if(this.text.trim()) formData.append('message', this.text.trim());
                if(this.imageFile) formData.append('image', this.imageFile);

                // Khóa form hiển thị loading
                this.isSending = true;

                fetch(`/chat/${this.activeConv}/messages`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(async res => {
                    const data = await res.json();
                    if (!res.ok) throw data;
                    return data;
                })
                .then(data => {
                    this.isSending = false;
                    if (data.status === 'success' || data.success) {
                        const msgData = data.message || data.data;
                        if(msgData) this.appendMsg(msgData, true);
                        
                        // CHỈ XÓA NỘI DUNG KHI CHẮC CHẮN ĐÃ GỬI XONG
                        this.text = ''; 
                        this.removeImage();
                    } else {
                        alert('Lỗi gửi tin: ' + (data.error || data.message || 'Không rõ nguyên nhân'));
                    }
                })
                .catch(err => {
                    this.isSending = false;
                    let errMsg = 'Đường truyền gián đoạn hoặc tệp ảnh không đúng định dạng!';
                    if (err && err.errors) {
                        errMsg = Object.values(err.errors).flat().join('\n');
                    } else if (err && err.message) {
                        errMsg = err.message;
                    } else if (err && err.error) {
                        errMsg = err.error;
                    }
                    alert("Gửi thất bại: \n" + errMsg);
                });
            },

            deleteChat() {
                if(!this.activeConv) return;
                if(!confirm('Xóa vĩnh viễn đoạn chat này?')) return;
                
                fetch(`/chat/${this.activeConv}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    const item = document.getElementById('gconv-' + this.activeConv);
                    if(item) item.remove();
                    
                    const list = document.getElementById('chat-list-container');
                    if (list && list.children.length === 0) {
                        list.innerHTML = `<div class="text-center text-gray-400 h-full flex flex-col items-center justify-center"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 border-2 border-white shadow-sm"><i class="fa-regular fa-comments text-2xl text-gray-300"></i></div><p class="text-xs font-bold text-gray-500">Hộp thư trống</p></div>`;
                    }

                    this.activeConv = null;
                    this.partnerName = 'Hộp thư tin nhắn';
                    document.getElementById('global-chat-box').innerHTML = `<div class='h-full flex flex-col items-center justify-center text-gray-400'><div class='w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-sm'><i class='fa-regular fa-trash-can text-3xl text-gray-300'></i></div><p class='text-sm font-bold text-gray-500'>Đã xóa đoạn chat</p></div>`;
                });
            }
        }));
    });

    window.syncUnreadCount = function() {
        fetch('/chat/unread-count')
            .then(res => res.json())
            .then(data => {
                window.dispatchEvent(new CustomEvent('update-unread-count', { detail: data.count }));
            }).catch(err => console.log('Lỗi đồng bộ:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const myId = {{ auth()->id() ?? 'null' }};

        if (typeof window.Echo !== 'undefined' && myId) {
            window.Echo.private(`user.${myId}`)
                .listen('.message.sent', (e) => {
                    if (e.message.sender_id !== myId) {
                        window.syncUnreadCount();
                        window.dispatchEvent(new CustomEvent('message-arrived', { detail: e.message }));
                    }
                });
        }

        setInterval(() => { window.syncUnreadCount(); }, 10000);
    });
</script>
@endif
@endauth