<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2HAND - Mua Bán & Trao Đổi Đồ Cũ</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Hiệu ứng bay lơ lửng cho icon ở Banner */
        @keyframes float {
            0% {
                transform: translateY(0px) rotate(var(--rot));
            }

            50% {
                transform: translateY(-8px) rotate(var(--rot));
            }

            100% {
                transform: translateY(0px) rotate(var(--rot));
            }
        }

        .icon-float {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    {{-- ===== NAVBAR ===== --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center gap-4">

            <div class="flex items-center gap-5 shrink-0" x-data="{ showMenu: false, activeCategory: 1 }">
                <button @click="showMenu = !showMenu" class="w-10 h-10 bg-green-50 text-green-700 rounded-xl flex items-center justify-center hover:bg-green-100 transition shadow-sm">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <a href="{{ url('/') }}" class="flex flex-col items-center justify-center leading-none">
                    <div class="flex items-center gap-2">
                        <div class="text-green-700 text-3xl">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                        </div>
                        <span class="text-3xl font-black text-green-700 tracking-tighter uppercase">2HAND</span>
                    </div>
                    <span class="text-[10px] text-gray-500 tracking-widest uppercase font-medium mt-1">Mua bán & Trao đổi đồ cũ</span>
                </a>

                <div x-show="showMenu" @click.away="showMenu = false" x-transition
                    class="absolute top-full left-4 mt-2 w-[800px] bg-white rounded-2xl shadow-2xl border border-gray-100 flex overflow-hidden z-50 h-[500px]" style="display: none;">

                    <div class="w-1/3 bg-gray-50 overflow-y-auto border-r border-gray-100">
                        <div class="p-4 font-bold text-lg border-b border-gray-100 text-green-700">Danh mục</div>
                        <ul class="py-2 text-sm text-gray-700 font-medium">
                            <li @mouseenter="activeCategory = 1" :class="activeCategory === 1 ? 'bg-white text-green-700 border-l-4 border-green-600 font-bold' : 'hover:bg-gray-100 border-l-4 border-transparent'" class="px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors">
                                <i class="fa-solid fa-desktop w-5 text-center text-gray-400"></i> Đồ điện tử
                            </li>
                            <li @mouseenter="activeCategory = 2" :class="activeCategory === 2 ? 'bg-white text-green-700 border-l-4 border-green-600 font-bold' : 'hover:bg-gray-100 border-l-4 border-transparent'" class="px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors">
                                <i class="fa-solid fa-motorcycle w-5 text-center text-gray-400"></i> Xe cộ
                            </li>
                            <li @mouseenter="activeCategory = 3" :class="activeCategory === 3 ? 'bg-white text-green-700 border-l-4 border-green-600 font-bold' : 'hover:bg-gray-100 border-l-4 border-transparent'" class="px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors">
                                <i class="fa-solid fa-couch w-5 text-center text-gray-400"></i> Đồ gia dụng, nội thất
                            </li>
                        </ul>
                    </div>

                    <div class="w-2/3 p-6 overflow-y-auto bg-white">
                        <div x-show="activeCategory === 1" class="grid grid-cols-2 gap-4">
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Điện thoại</a>
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Máy tính bảng</a>
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Laptop</a>
                        </div>
                        <div x-show="activeCategory === 2" class="grid grid-cols-2 gap-4" style="display: none;">
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Xe máy</a>
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Ô tô</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex-1 max-w-sm hidden md:flex items-center bg-gray-100 rounded-full overflow-hidden border border-gray-200 focus-within:border-green-500 focus-within:bg-white focus-within:ring-2 focus-within:ring-green-100 transition-all">
                <input type="text" placeholder="Tìm sản phẩm..." class="w-full bg-transparent px-4 py-2 outline-none text-sm">
                <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 transition-colors font-medium text-sm flex items-center gap-1">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm
                </button>
            </div>

            <div class="flex items-center space-x-5 shrink-0">
                {{-- Đoạn code Giỏ hàng trên thanh Navbar --}}
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-green-700 text-sm flex flex-col items-center gap-1 transition relative">
                    <div class="relative">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span class="absolute -top-1.5 -right-2 bg-red-500 text-white text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center border border-white">
                            {{ Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->count() : 0 }}
                        </span>
                    </div>
                    <span class="hidden lg:inline text-[11px] font-medium">Giỏ hàng</span>
                </a>

                <a href="#" class="text-gray-500 hover:text-green-700 text-sm flex flex-col items-center gap-1 transition">
                    <i class="fa-regular fa-bell text-lg"></i> <span class="hidden lg:inline text-[11px] font-medium">Thông báo</span>
                </a>
@auth
                <a href="javascript:void(0)" onclick="window.dispatchEvent(new CustomEvent('toggle-chat'))" class="text-gray-500 hover:text-green-700 text-sm flex flex-col items-center gap-1 transition relative">
                    <div class="relative">
                        <i class="fa-solid fa-comment-dots text-lg"></i>
                        <span id="chat-notification-dot" class="hidden absolute -top-1 -right-1 bg-red-500 rounded-full h-2.5 w-2.5 border border-white animate-pulse"></span>
                    </div>
                    <span class="hidden lg:inline text-[11px] font-medium">Tin nhắn</span>
                </a>
                @endauth

                <a href="{{ route('products.create') }}" class="bg-green-600 text-white px-5 py-2.5 rounded-full font-bold hover:bg-green-700 transition shadow-md flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> <span class="hidden sm:inline">Đăng tin</span>
                </a>

                @if (Route::has('login'))
                <div class="flex items-center border-l pl-5 ml-2">
                    @auth
                    <div class="relative group py-2">
                        <div class="flex items-center gap-3 cursor-pointer">
                            @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-9 h-9 rounded-full object-cover border border-green-200 shadow-sm shrink-0">
                            @else
                            <div class="w-9 h-9 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold border border-green-200 shadow-sm shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            @endif
                            <div class="hidden md:block text-sm">
                                <p class="font-bold text-gray-700 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-green-600">Quản lý tài khoản <i class="fa-solid fa-angle-down ml-1"></i></p>
                            </div>
                        </div>

                        <div class="absolute top-full right-0 mt-0 hidden group-hover:block w-56 pt-2 z-50">
                            <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                <div class="p-2 border-b border-gray-50">
                                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                        <i class="fa-solid fa-chart-line w-4"></i> Trang quản lý
                                    </a>
                                    <a href="{{ url('/profile') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition">
                                        <i class="fa-regular fa-circle-user w-4"></i> Thông tin cá nhân
                                    </a>
                                </div>

                                <form method="POST" action="{{ route('logout') }}" class="p-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-green-700 transition">Đăng nhập</a>
                    @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="ml-4 text-sm font-bold bg-green-50 text-green-700 px-4 py-2 rounded-full hover:bg-green-100 transition border border-green-200">Đăng ký</a>
                    @endif
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- ===== NỘI DUNG CHÍNH ===== --}}
    <main class="max-w-7xl mx-auto px-4 py-6 flex-grow w-full">

        {{-- ===== HERO BANNER ===== --}}
        <div class="bg-gradient-to-r from-green-50 via-emerald-50 to-green-100 border border-green-200/60 rounded-2xl h-28 md:h-36 mb-8 flex items-center justify-center relative overflow-hidden shadow-sm">

            <h1 class="text-2xl md:text-4xl font-black text-green-900 z-10 drop-shadow-sm tracking-tight">
                Cũ người mới ta, mua bán mượt mà!
            </h1>

            <div class="absolute top-4 left-10 md:left-24 text-green-600/30 icon-float" style="--rot: -15deg;">
                <i class="fa-solid fa-laptop text-4xl md:text-5xl"></i>
            </div>
            <div class="absolute -bottom-4 left-1/4 text-emerald-700/20 icon-float animate-delay-1000" style="--rot: 10deg; animation-delay: 1s;">
                <i class="fa-solid fa-couch text-5xl md:text-7xl"></i>
            </div>
            <div class="absolute top-6 right-10 md:right-32 text-green-700/40 icon-float" style="--rot: 20deg; animation-delay: 0.5s;">
                <i class="fa-solid fa-motorcycle text-4xl md:text-6xl"></i>
            </div>
            <div class="absolute bottom-2 right-1/4 text-emerald-600/30 icon-float" style="--rot: -20deg; animation-delay: 1.5s;">
                <i class="fa-solid fa-shirt text-3xl md:text-4xl"></i>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl shadow-sm flex items-center gap-3 font-medium mb-6">
            <i class="fa-solid fa-circle-check text-xl text-green-500"></i> {{ session('success') }}
        </div>
        @endif

        <div class="mb-10">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Khám phá danh mục</h2>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-desktop text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Đồ điện tử</h3>
                </a>
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-blender text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Đồ gia dụng</h3>
                </a>
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-motorcycle text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Xe cộ</h3>
                </a>
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-shirt text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Thời trang</h3>
                </a>
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-book text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Sách, truyện</h3>
                </a>
                <a href="#" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid fa-basketball text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">Thể thao</h3>
                </a>
            </div>
        </div>

        <div>
            <div class="flex justify-between items-end mb-5">
                <h2 class="text-xl font-bold text-gray-800">Tin đăng mới nhất</h2>
                <a href="#" class="text-sm text-green-700 hover:underline font-bold">Xem tất cả <i class="fa-solid fa-angle-right"></i></a>
            </div>

            @if($products->isEmpty())
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
                <div class="text-gray-300 text-5xl mb-3"><i class="fa-solid fa-box-open"></i></div>
                <p class="text-gray-500 font-medium">Chưa có sản phẩm nào được rao bán hôm nay.</p>
            </div>
            @else
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group relative">
                    <button class="absolute top-2 right-2 z-10 w-8 h-8 bg-white/80 backdrop-blur rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition shadow-sm">
                        <i class="fa-regular fa-heart"></i>
                    </button>

                    <a href="{{ route('products.show', $product->slug) }}" class="block">
                        <div class="w-full aspect-[4/3] bg-gray-100 flex items-center justify-center overflow-hidden">
                            @if($product->images && $product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                            @else
                            <div class="text-center text-gray-400">
                                <i class="fa-regular fa-image text-3xl mb-1 block"></i>
                                <span class="text-[10px]">Chưa có hình</span>
                            </div>
                            @endif
                        </div>
                    </a>

                    <div class="p-4 flex flex-col flex-grow">
                        <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-green-700 transition">
                            <a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
                        </h3>

                        <div class="mt-auto">
                            <p class="text-base font-black text-red-600 mb-2">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                            <div class="flex items-center justify-between text-[11px] text-gray-500 font-medium">
                                <div class="flex items-center truncate max-w-[70%]">
                                    <i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> {{ $product->location ?? 'Toàn quốc' }}
                                </div>
                                <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">Mới {{ $product->condition_pct }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $products->links() }}
            </div>
            @endif
        </div>

    </main>

    {{-- ===== CHÂN TRANG ===== --}}
    <footer class="bg-white border-t border-gray-100 mt-16 py-12 text-sm text-gray-500">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4 text-green-700 font-black text-xl">
                    <i class="fa-solid fa-hand-holding-hand"></i> <span>2HAND</span>
                </div>
                <p class="text-xs leading-relaxed text-gray-400">
                    Nền tảng mua bán đồ cũ uy tín, giúp bạn dễ dàng tìm kiếm món đồ phù hợp với giá tốt và kết nối người bán gần bạn một cách nhanh chóng.
                </p>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Về chúng tôi</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#" class="hover:text-green-700 transition">Giới thiệu</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">Quy chế hoạt động</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Hỗ trợ khách hàng</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#" class="hover:text-green-700 transition">Trung tâm trợ giúp</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">Hướng dẫn mua bán</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">An toàn giao dịch</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Liên hệ</h4>
                <ul class="space-y-2.5 text-xs mb-4">
                    <li>Hotline: <span class="font-bold text-green-600">1900 1234</span></li>
                    <li>Email: <span class="text-gray-600">support@2hand.vn</span></li>
                    <li>Địa chỉ: Cần Thơ, Việt Nam</li>
                </ul>
                <div class="flex space-x-3">
                    <a href="#" class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center hover:opacity-90 transition shadow-sm"><i class="fa-brands fa-facebook-f text-sm"></i></a>
                    <a href="#" class="w-8 h-8 rounded-full bg-black text-white flex items-center justify-center hover:opacity-90 transition shadow-sm"><i class="fa-brands fa-tiktok text-sm"></i></a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 border-t border-gray-50 mt-10 pt-6 text-center text-xs text-gray-400">
            &copy; 2026 2HAND. All rights reserved.
        </div>
    </footer>

    {{-- DÁN TOÀN BỘ KHỐI CODE CHAT LƠ LỬNG VÀO ĐÂY --}}
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

</body>

</html>