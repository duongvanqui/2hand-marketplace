<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', '2HAND - Mua Bán & Trao Đổi Đồ Cũ')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif !important; }
    </style>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50">
    
    {{-- 
       LƯU Ý: Nếu bạn có thanh Navbar ở đây, hãy sửa nút Tin nhắn thành cấu trúc này:
       @auth
       <a href="javascript:void(0)" onclick="window.dispatchEvent(new CustomEvent('toggle-chat'))" class="...">
           <i class="fa-solid fa-comment-dots text-lg"></i>
           <span id="chat-notification-dot" class="hidden absolute ..."></span>
       </a>
       @endauth
    --}}

    <main>
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </main>

    {{-- ========================================== --}}
    {{-- 2. HỘP THƯ CHAT LƠ LỬNG TOÀN CỤC            --}}
    {{-- ========================================== --}}
    @auth
    @php
        $myConversations = \App\Models\Conversation::where('buyer_id', Auth::id())
            ->orWhere('seller_id', Auth::id())
            ->with(['product', 'buyer', 'seller'])
            ->orderBy('updated_at', 'desc')
            ->get();
    @endphp

    <div x-data="{
        isChatOpen: false,
        activeConv: null,
        text: '',
        myId: {{ auth()->id() }},
        partnerName: 'Hộp thư tin nhắn',

        initChat(convId) {
            this.isChatOpen = true;
            document.getElementById('chat-notification-dot').classList.add('hidden');
            
            if(!convId || this.activeConv === convId) return;
            this.activeConv = convId;
            
            const box = document.getElementById('global-chat-box');
            box.innerHTML = '<div class=\'text-center text-xs text-gray-400 mt-4\'><i class=\'fa-solid fa-spinner fa-spin\'></i> Đang tải...</div>';

            document.querySelectorAll('.global-conv-item').forEach(el => el.classList.remove('bg-green-50', 'border-l-4', 'border-green-500'));
            const activeItem = document.getElementById('gconv-' + convId);
            if(activeItem) {
                activeItem.classList.add('bg-green-50', 'border-l-4', 'border-green-500');
                this.partnerName = activeItem.getAttribute('data-name');
            }

            fetch(`/chat/${convId}/messages`)
                .then(res => res.json())
                .then(messages => {
                    box.innerHTML = '';
                    messages.forEach(msg => this.appendMsg(msg, msg.sender_id === this.myId));
                    
                    if (window.Echo) {
                        window.Echo.private(`chat.${convId}`)
                            .listen('.message.sent', (e) => {
                                if (e.message.sender_id !== this.myId) {
                                    this.appendMsg(e.message, false);
                                    if(!this.isChatOpen) document.getElementById('chat-notification-dot').classList.remove('hidden');
                                }
                            });
                    }
                });
        },
        appendMsg(msg, isMe) {
            const box = document.getElementById('global-chat-box');
            const align = isMe ? 'self-end bg-green-600 text-white rounded-bl-xl' : 'self-start bg-white border border-gray-200 text-gray-800 rounded-br-xl';
            box.insertAdjacentHTML('beforeend', `<div class='max-w-[80%] px-3 py-2 mt-2 text-sm shadow-sm rounded-t-xl ${align}'>${msg.message}</div>`);
            box.scrollTop = box.scrollHeight;
        },
        sendMsg() {
            if(!this.text.trim() || !this.activeConv) return;
            let msgText = this.text;
            this.text = ''; 
            this.appendMsg({message: msgText}, true); 

            fetch(`/chat/${this.activeConv}/messages`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']').getAttribute('content')
                },
                body: JSON.stringify({ message: msgText })
            }).catch(() => alert('Lỗi gửi tin nhắn!'));
        }
    }"
    @toggle-chat.window="isChatOpen = !isChatOpen; if(isChatOpen) document.getElementById('chat-notification-dot').classList.add('hidden');"
    @open-chat.window="initChat($event.detail)"
    x-show="isChatOpen"
    style="display: none;"
    x-transition
    class="fixed bottom-0 right-10 w-[360px] sm:w-[700px] h-[500px] bg-white shadow-[0_0_40px_rgba(0,0,0,0.2)] rounded-t-2xl flex border border-gray-200 z-[9999] overflow-hidden"
    >
        <div class="w-1/3 bg-gray-50 border-r border-gray-200 flex-col hidden sm:flex h-full">
            <div class="p-3 bg-white border-b border-gray-200 shadow-sm shrink-0">
                <h3 class="font-bold text-gray-800 text-sm"><i class="fa-brands fa-facebook-messenger text-green-600 mr-1"></i> Hộp thư</h3>
            </div>
            <div class="flex-1 overflow-y-auto">
                @if($myConversations->isEmpty())
                    <div class="p-4 text-center text-gray-400 text-xs mt-4">Chưa có tin nhắn.</div>
                @else
                    @foreach($myConversations as $conv)
                        @php
                            $isBuyer = $conv->buyer_id === Auth::id();
                            $partner = $isBuyer ? $conv->seller : $conv->buyer;
                        @endphp
                        <div @click="initChat({{ $conv->id }})" 
                             id="gconv-{{ $conv->id }}"
                             data-name="{{ $partner->name }}"
                             class="global-conv-item p-3 border-b border-gray-100 hover:bg-gray-100 cursor-pointer transition flex gap-2 items-center">
                            <div class="w-10 h-10 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold shrink-0 text-sm">
                                {{ substr($partner->name, 0, 1) }}
                            </div>
                            <div class="overflow-hidden flex-1">
                                <h4 class="font-bold text-gray-800 text-xs truncate">{{ $partner->name }}</h4>
                                <p class="text-[10px] text-gray-500 truncate">{{ $conv->product->title }}</p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="flex-1 flex flex-col bg-white h-full relative">
            <div class="p-3 border-b border-gray-200 bg-white flex justify-between items-center shadow-sm shrink-0 z-10">
                <div class="font-bold text-sm text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-user-circle text-green-600 text-lg"></i> <span x-text="partnerName"></span>
                </div>
                <button @click="isChatOpen = false" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div id="global-chat-box" class="flex-1 overflow-y-auto p-4 bg-gray-50/50 flex flex-col">
                <div x-show="!activeConv" class="h-full flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-regular fa-paper-plane text-4xl mb-3 text-gray-200"></i>
                    <p class="text-xs">Chọn người bên trái để bắt đầu chat</p>
                </div>
            </div>

            <form @submit.prevent="sendMsg" class="p-3 border-t border-gray-100 flex gap-2 bg-white shrink-0">
                <input type="text" x-model="text" :disabled="!activeConv" class="flex-grow bg-gray-100 border-none rounded-xl px-4 py-2 text-sm focus:ring-2 focus:ring-green-500 outline-none disabled:opacity-50" placeholder="Nhập tin nhắn..." autocomplete="off">
                <button type="submit" :disabled="!activeConv" class="text-green-600 hover:text-green-700 p-2 transition disabled:opacity-50">
                    <i class="fa-solid fa-paper-plane text-base"></i>
                </button>
            </form>
        </div>
    </div>
    @endauth
</body>
</html>