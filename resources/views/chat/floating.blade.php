@auth
@if(!request()->routeIs('chat.index'))
@php
    $myConversations = \App\Models\Conversation::where(function($q) {
            $q->where('buyer_id', Auth::id())
              ->orWhere('seller_id', Auth::id());
        })
        ->has('messages')
        ->with(['product', 'buyer', 'seller'])
        ->orderBy('updated_at', 'desc')
        ->get();
@endphp

<div x-data="{
    isChatOpen: false,
    activeConv: null,
    text: '',
    imageFile: null,
    imagePreview: null,
    myId: {{ auth()->id() }},
    partnerName: 'Hộp thư tin nhắn',

    initChat(convId) {
        this.isChatOpen = true;
        
        // Tắt chấm đỏ ở Navbar
        const dot = document.getElementById('chat-notification-dot');
        if(dot) dot.classList.add('hidden');
        
        if(!convId || this.activeConv === convId) return;
        this.activeConv = convId;
        
        const box = document.getElementById('global-chat-box');
        box.innerHTML = `<div class='text-center text-xs text-gray-400 mt-10'><i class='fa-solid fa-spinner fa-spin text-2xl mb-2'></i><br>Đang tải tin nhắn...</div>`;

        // Làm mới màu sắc danh sách
        document.querySelectorAll('.global-conv-item').forEach(el => {
            el.classList.remove('bg-emerald-50');
            const bar = el.querySelector('.active-bar');
            if(bar) bar.classList.replace('scale-y-100', 'scale-y-0');
        });
        
        // Tô màu mục đang chọn
        const activeItem = document.getElementById('gconv-' + convId);
        if(activeItem) {
            activeItem.classList.add('bg-emerald-50');
            const activeBar = activeItem.querySelector('.active-bar');
            if(activeBar) activeBar.classList.replace('scale-y-0', 'scale-y-100');
            this.partnerName = activeItem.getAttribute('data-name');
        }

        // Tự động ẩn huy hiệu đỏ của cuộc trò chuyện này
        const badge = document.getElementById('floating-unread-badge-' + convId);
        if(badge) badge.style.display = 'none';

        // Tải dữ liệu tin nhắn
        fetch(`/chat/${convId}/messages`)
            .then(res => res.json())
            .then(messages => {
                box.innerHTML = `<div class='text-center my-4'><span class='text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wider border border-gray-200 shadow-inner'>Bắt đầu cuộc trò chuyện</span></div>`;
                messages.forEach(msg => this.appendMsg(msg, msg.sender_id === this.myId));
                
                setTimeout(() => { if(window.syncUnreadCount) window.syncUnreadCount(); }, 500);

                if (window.Echo) {
                    window.Echo.private(`chat.${convId}`)
                        .listen('.message.sent', (e) => {
                            if (e.message.sender_id !== this.myId) {
                                this.appendMsg(e.message, false);
                            }
                        });
                }
            })
            .catch(err => {
                box.innerHTML = `<div class='text-center text-red-500 mt-10'>Lỗi tải tin nhắn!</div>`;
            });
    },

    openDirect(data) {
        this.isChatOpen = true;
        this.activeConv = data.convId;
        this.partnerName = data.partnerName;
        
        const dot = document.getElementById('chat-notification-dot');
        if(dot) dot.classList.add('hidden');

        const box = document.getElementById('global-chat-box');
        box.innerHTML = `<div class='text-center text-xs text-gray-400 mt-10'><i class='fa-solid fa-spinner fa-spin text-2xl mb-2'></i><br>Đang chuẩn bị phòng chat...</div>`;

        document.querySelectorAll('.global-conv-item').forEach(el => {
            el.classList.remove('bg-emerald-50');
            if(el.querySelector('.active-bar')) el.querySelector('.active-bar').classList.replace('scale-y-100', 'scale-y-0');
        });
        
        const activeItem = document.getElementById('gconv-' + data.convId);
        if(activeItem) {
            activeItem.classList.add('bg-emerald-50');
            activeItem.querySelector('.active-bar').classList.replace('scale-y-0', 'scale-y-100');
        }

        const badge = document.getElementById('floating-unread-badge-' + data.convId);
        if(badge) badge.style.display = 'none';

        fetch(`/chat/${data.convId}/messages`)
            .then(res => res.json())
            .then(messages => {
                box.innerHTML = `<div class='text-center my-4'><span class='text-[10px] font-bold text-gray-400 bg-gray-100 px-3 py-1 rounded-full uppercase tracking-wider border border-gray-200 shadow-inner'>Hãy gửi lời chào đến người bán</span></div>`;
                messages.forEach(msg => this.appendMsg(msg, msg.sender_id === this.myId));

                setTimeout(() => { if(window.syncUnreadCount) window.syncUnreadCount(); }, 500);

                if (window.Echo) {
                    window.Echo.private(`chat.${data.convId}`)
                        .listen('.message.sent', (e) => {
                            if (e.message.sender_id !== this.myId) {
                                this.appendMsg(e.message, false);
                            }
                        });
                }
            });
    },

    appendMsg(msg, isMe) {
        const box = document.getElementById('global-chat-box');
        const alignWrapper = isMe ? 'flex justify-end' : 'flex justify-start';
        const bubbleClass = isMe ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white rounded-2xl rounded-tr-sm shadow-md' : 'bg-white border border-gray-100 text-gray-800 rounded-2xl rounded-tl-sm shadow-sm';
        
        let contentHTML = '';
        if (msg.image_path && msg.image_path.startsWith('data:image')) {
            contentHTML += `<img src='${msg.image_path}' class='rounded-xl mb-1 max-w-[150px] object-cover border border-black/5'>`;
        } else if (msg.image_path) {
            contentHTML += `<a href='/storage/${msg.image_path}' target='_blank'><img src='/storage/${msg.image_path}' class='rounded-xl mb-1 max-w-[150px] object-cover border border-black/5 hover:opacity-90'></a>`;
        }
        if (msg.message) {
            contentHTML += `<p class='text-[13px] leading-relaxed'>${msg.message.replace(/\n/g, '<br>')}</p>`;
        }

        box.insertAdjacentHTML('beforeend', `
            <div class='${alignWrapper} mt-1.5 animate-fade-in-up'>
                <div class='max-w-[80%] px-3.5 py-2 ${bubbleClass}'>${contentHTML}</div>
            </div>
        `);
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

    sendMsg() {
        if((!this.text.trim() && !this.imageFile) || !this.activeConv) return;
        
        let formData = new FormData();
        if(this.text.trim()) formData.append('message', this.text.trim());
        if(this.imageFile) formData.append('image', this.imageFile);

        let tempMsg = { message: this.text, image_path: this.imagePreview };
        this.text = ''; 
        this.removeImage();
        this.appendMsg(tempMsg, true); 

        fetch(`/chat/${this.activeConv}/messages`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: formData
        }).catch(() => alert('Lỗi gửi tin nhắn hoặc ảnh!'));
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
            this.activeConv = null;
            this.partnerName = 'Hộp thư tin nhắn';
            document.getElementById('global-chat-box').innerHTML = `<div class='h-full flex flex-col items-center justify-center text-gray-400'><i class='fa-regular fa-paper-plane text-4xl mb-3 text-emerald-100'></i><p class='text-sm'>Đã xóa cuộc trò chuyện</p></div>`;
        });
    }
}"
@toggle-chat.window="isChatOpen = !isChatOpen; const dot = document.getElementById('chat-notification-dot'); if(isChatOpen && dot) dot.classList.add('hidden');"
@open-direct-chat.window="openDirect($event.detail)"
x-show="isChatOpen"
style="display: none;"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0 translate-y-10 scale-95"
x-transition:enter-end="opacity-100 translate-y-0 scale-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100 translate-y-0 scale-100"
x-transition:leave-end="opacity-0 translate-y-10 scale-95"
class="fixed bottom-4 right-4 md:right-8 w-[360px] sm:w-[750px] h-[550px] bg-white shadow-[0_10px_60px_rgba(0,0,0,0.15)] rounded-2xl flex border border-gray-200 z-[99999] overflow-hidden pointer-events-auto"
>
    {{-- CỘT TRÁI: DANH SÁCH CHAT --}}
    <div class="w-1/3 bg-gray-50/80 border-r border-gray-100 flex-col hidden sm:flex h-full relative z-10">
        
        <div class="p-4 bg-white border-b border-gray-100 shrink-0 flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shadow-inner border border-emerald-200">
                <i class="fa-solid fa-hand-holding-hand"></i>
            </div>
            <h3 class="font-black text-gray-900 text-base">Hộp thư</h3>
        </div>
        
        <div class="flex-1 overflow-y-auto no-scrollbar py-2">
            @if($myConversations->isEmpty())
                <div class="p-6 text-center text-gray-400 h-full flex flex-col items-center justify-center">
                    <i class="fa-regular fa-comments text-3xl mb-2 text-gray-300"></i>
                    <p class="text-xs font-semibold">Chưa có tin nhắn</p>
                </div>
            @else
                @foreach($myConversations as $conv)
                    @php
                        $isBuyer = $conv->buyer_id === Auth::id();
                        $partner = $isBuyer ? $conv->seller : $conv->buyer;
                        
                        // Đếm số tin nhắn chưa đọc của người này
                        $unreadInConv = \App\Models\Message::where('conversation_id', $conv->id)
                            ->where('sender_id', '!=', Auth::id())
                            ->where('is_read', false)
                            ->count();
                    @endphp
                    
                    {{-- CHUẨN ALPINE.JS: Click mượt mà, không bị chặn --}}
                    <div @click="initChat({{ $conv->id }})" 
                         id="gconv-{{ $conv->id }}"
                         data-name="{{ $partner->name }}"
                         class="global-conv-item p-3 border-b border-gray-50 hover:bg-emerald-50/50 cursor-pointer transition-colors flex items-center gap-3 relative group">
                        
                        <div class="active-bar absolute inset-y-0 left-0 w-1 bg-emerald-500 rounded-r-full transform scale-y-0 transition-transform duration-300"></div>

                        <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0 overflow-hidden border border-white shadow-sm ml-1">
                            @if($partner->avatar)
                                <img src="{{ asset('storage/' . $partner->avatar) }}" class="w-full h-full object-cover">
                            @else
                                {{ substr($partner->name, 0, 1) }}
                            @endif
                        </div>

                        <div class="flex-1 overflow-hidden space-y-0.5">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1.5 truncate pr-2">
                                    <h4 class="font-bold text-gray-900 text-sm truncate">{{ $partner->name }}</h4>
                                    
                                    {{-- HUY HIỆU ĐỎ CHO TIN NHẮN MỚI --}}
                                    @if($unreadInConv > 0)
                                        <span id="floating-unread-badge-{{ $conv->id }}" class="bg-red-500 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm">
                                            {{ $unreadInConv > 99 ? '99+' : $unreadInConv }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <p class="text-[10px] text-emerald-600 font-medium truncate inline-block">
                                <i class="fa-solid fa-box-open mr-1"></i> {{ $conv->product->title }}
                            </p>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- CỘT PHẢI: KHUNG TRÒ CHUYỆN --}}
    <div class="flex-1 flex flex-col bg-white h-full relative z-20">
        <div class="p-4 border-b border-gray-100 bg-white flex justify-between items-center z-10 h-[65px] shrink-0">
            <div>
                <span x-text="partnerName" class="font-bold text-gray-900 text-base leading-tight ml-2"></span>
                <div class="flex items-center gap-1.5 mt-0.5 ml-2" x-show="activeConv">
                    <span class="flex items-center gap-1 text-[10px] text-emerald-500 font-medium">
                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online
                    </span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="deleteChat()" x-show="activeConv" class="text-gray-400 hover:text-red-500 w-8 h-8 rounded-full flex items-center justify-center transition" title="Xóa đoạn chat">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button @click="isChatOpen = false" class="text-gray-400 hover:text-gray-700 w-8 h-8 rounded-full flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </div>

        <div id="global-chat-box" class="flex-1 overflow-y-auto p-5 bg-white flex flex-col gap-3">
            <div x-show="!activeConv" class="h-full flex flex-col items-center justify-center text-gray-400">
                <i class="fa-regular fa-paper-plane text-5xl text-emerald-100 mb-3"></i>
                <p class="text-sm font-medium text-gray-500">Chọn cuộc trò chuyện để bắt đầu</p>
            </div>
        </div>

        <div x-show="imagePreview" class="p-3 bg-white border-t border-gray-100" style="display: none;">
            <div class="relative inline-block">
                <img :src="imagePreview" class="h-14 rounded-lg border border-gray-200 object-cover shadow-sm">
                <button @click="removeImage()" type="button" class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-[10px] flex items-center justify-center hover:bg-red-600 shadow-md transition-transform hover:scale-110">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <form @submit.prevent="sendMsg" enctype="multipart/form-data" class="p-3 border-t border-gray-100 flex items-center gap-2 bg-white shrink-0">
            <label class="p-2 text-gray-400 hover:text-emerald-500 cursor-pointer transition-colors" :class="{'opacity-50 pointer-events-none': !activeConv}" title="Gửi ảnh">
                <i class="fa-regular fa-image text-xl"></i>
                <input type="file" id="global-image-input" accept="image/png, image/jpeg, image/webp" class="hidden" @change="handleImageSelect" :disabled="!activeConv">
            </label>

            <input type="text" x-model="text" :disabled="!activeConv" class="flex-grow bg-gray-100 border-none rounded-full px-4 py-2.5 text-sm focus:ring-1 focus:ring-emerald-400 outline-none disabled:opacity-50 transition-all" placeholder="Nhập tin nhắn..." autocomplete="off">
            
            {{-- Nút Gửi (Hình máy bay giấy) --}}
            <button type="submit" :disabled="!activeConv || (!text.trim() && !imageFile)" class="p-2 text-emerald-500 hover:text-emerald-600 transition-colors disabled:opacity-50 disabled:text-gray-300">
                <i class="fa-solid fa-paper-plane text-xl"></i>
            </button>
        </form>
    </div>
</div>
@endif

<script>
    window.syncUnreadCount = function() {
        fetch('/chat/unread-count')
            .then(res => res.json())
            .then(data => {
                window.dispatchEvent(new CustomEvent('update-unread-count', { detail: data.count }));
            }).catch(err => console.log('Lỗi đồng bộ:', err));
    }

    document.addEventListener('DOMContentLoaded', () => {
        const myId = {{ auth()->id() }};
        const myConvIds = @json(\App\Models\Conversation::where('buyer_id', auth()->id())->orWhere('seller_id', auth()->id())->pluck('id'));

        if (typeof window.Echo !== 'undefined') {
            myConvIds.forEach(id => {
                window.Echo.private(`chat.${id}`)
                    .listen('.message.sent', (e) => {
                        if (e.message.sender_id !== myId) {
                            window.syncUnreadCount();
                        }
                    });
            });
        }

        setInterval(() => { window.syncUnreadCount(); }, 10000);
    });
</script>
@endauth