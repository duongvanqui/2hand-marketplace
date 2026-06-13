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

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif !important; }
        @keyframes float {
            0% { transform: translateY(0px) rotate(var(--rot)); }
            50% { transform: translateY(-8px) rotate(var(--rot)); }
            100% { transform: translateY(0px) rotate(var(--rot)); }
        }
        .icon-float { animation: float 4s ease-in-out infinite; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @yield('styles')
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-800 flex flex-col min-h-screen">

    {{-- CHUẨN BỊ DỮ LIỆU TẠI VIEW --}}
    @php
        $navRootCategories = \App\Models\Category::whereNull('parent_id')->with('children')->get();
        
        $iconMap = [
            'Đồ điện tử' => 'fa-laptop',
            'Xe cộ' => 'fa-motorcycle',
            'Đồ gia dụng, Điện lạnh' => 'fa-blender',
            'Thời trang' => 'fa-shirt',
            'Sách & Truyện tranh' => 'fa-book-open',
            'Thể thao' => 'fa-basketball',
            'Giải trí' => 'fa-gamepad',
            'Thú cưng' => 'fa-paw',
        ];

        // Lấy dữ liệu thông báo thật từ DB
        $unreadNotiCount = Auth::check() ? Auth::user()->unreadNotifications->count() : 0;
        $notifications = Auth::check() ? Auth::user()->notifications()->take(10)->get() : collect();
    @endphp

    {{-- ========================================== --}}
    {{-- NAVBAR HIỆN ĐẠI DÀNH CHO TRANG NGƯỜI DÙNG  --}}
    {{-- ========================================== --}}
    <nav class="bg-white/95 backdrop-blur-lg shadow-sm sticky top-0 z-50 border-b border-gray-100 transition-all duration-300">
        <div class="max-w-[1400px] mx-auto px-4 py-3 flex justify-between items-center gap-4 lg:gap-6">

            {{-- 1. Logo & Cửa sổ Danh mục --}}
            <div class="flex items-center gap-4 lg:gap-6 shrink-0" x-data="{ showMenu: false, activeCategory: {{ $navRootCategories->first()->id ?? 1 }} }">
                
                <button @click="showMenu = !showMenu" class="w-11 h-11 bg-green-50 text-green-700 rounded-xl flex items-center justify-center hover:bg-green-600 hover:text-white transition-all duration-300 shadow-sm outline-none group">
                    <i class="fa-solid fa-bars text-xl transition-transform group-hover:scale-110"></i>
                </button>

                <a href="{{ url('/') }}" class="flex flex-col justify-center leading-none group" title="Về trang chủ">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white text-xl shadow-md shadow-green-200 transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-6">
                            <i class="fa-solid fa-hand-holding-hand"></i>
                        </div>
                        <span class="text-3xl font-black text-gray-900 tracking-tighter uppercase transition-colors duration-300">2<span class="text-green-500">HAND</span></span>
                    </div>
                    <span class="text-[10px] text-gray-400 tracking-widest uppercase font-bold mt-1 transition-colors group-hover:text-green-500">Mua bán đồ cũ</span>
                </a>

                {{-- MEGA MENU --}}
                <div x-show="showMenu" @click.away="showMenu = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                    class="absolute top-full left-4 mt-3 w-[850px] bg-white rounded-3xl shadow-[0_20px_60px_rgba(0,0,0,0.08)] border border-gray-100 flex overflow-hidden z-50 h-[500px]" style="display: none;">
                    
                    <div class="w-[35%] bg-gray-50/50 overflow-y-auto border-r border-gray-100 no-scrollbar">
                        <div class="p-6 font-black text-lg border-b border-gray-100 text-green-700 tracking-tight sticky top-0 bg-gray-50/90 backdrop-blur z-10 uppercase">Khám phá</div>
                        <ul class="py-3">
                            @foreach($navRootCategories as $parentCat)
                                @php $icon = $iconMap[$parentCat->name] ?? 'fa-box'; @endphp
                                <li>
                                    <a href="{{ url('/?category_id=' . $parentCat->id) }}"
                                        @mouseenter="activeCategory = {{ $parentCat->id }}" 
                                        :class="activeCategory === {{ $parentCat->id }} ? 'bg-white text-green-700 shadow-sm border-r-4 border-green-500' : 'text-gray-600 hover:bg-gray-100 border-r-4 border-transparent'" 
                                        class="px-6 py-3 cursor-pointer flex items-center gap-4 transition-all block">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center transition-colors shadow-sm" :class="activeCategory === {{ $parentCat->id }} ? 'bg-green-100 text-green-600' : 'bg-white text-gray-400 border border-gray-100'">
                                            <i class="fa-solid {{ $icon }} text-sm"></i>
                                        </div>
                                        <span class="font-bold text-sm" :class="activeCategory === {{ $parentCat->id }} ? 'text-green-700' : 'text-gray-700'">{{ $parentCat->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="w-[65%] p-8 overflow-y-auto bg-white no-scrollbar relative">
                        @foreach($navRootCategories as $parentCat)
                            <div x-show="activeCategory === {{ $parentCat->id }}" class="h-full" style="display: none;">
                                @if($parentCat->children->count() > 0)
                                    <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-3">
                                        <h3 class="font-bold text-gray-800 text-lg">{{ $parentCat->name }}</h3>
                                        <a href="{{ url('/?category_id=' . $parentCat->id) }}" class="text-xs font-bold text-green-600 hover:text-green-700 bg-green-50 px-3 py-1.5 rounded-lg transition-colors">Xem tất cả <i class="fa-solid fa-arrow-right text-[10px] ml-1"></i></a>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        @foreach($parentCat->children as $childCat)
                                            <a href="{{ url('/?category_id=' . $childCat->id) }}" class="group flex items-center p-3 bg-gray-50 rounded-xl hover:bg-green-50 hover:shadow-sm border border-transparent hover:border-green-100 transition-all">
                                                <div class="w-2 h-2 rounded-full bg-gray-300 group-hover:bg-green-500 mr-3 transition-colors"></div>
                                                <span class="text-sm font-semibold text-gray-700 group-hover:text-green-700">{{ $childCat->name }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="flex flex-col items-center justify-center h-full text-center mt-[-20px]">
                                        <div class="w-32 h-32 bg-gradient-to-tr from-green-100 to-emerald-50 rounded-full flex items-center justify-center text-green-500 text-5xl mb-6 shadow-inner border-4 border-white">
                                            <i class="fa-solid {{ $iconMap[$parentCat->name] ?? 'fa-box-open' }} icon-float"></i>
                                        </div>
                                        <h3 class="font-black text-gray-800 text-xl mb-2">Tất cả {{ $parentCat->name }}</h3>
                                        <p class="text-sm text-gray-500 mb-8 max-w-sm">Nhấn vào bên dưới để khám phá ngay hàng ngàn sản phẩm cực chất lượng trong danh mục này.</p>
                                        <a href="{{ url('/?category_id=' . $parentCat->id) }}" class="bg-green-600 text-white px-8 py-3 rounded-full font-bold hover:bg-green-700 transition shadow-lg shadow-green-200 transform hover:-translate-y-1 flex items-center gap-2">
                                            Khám phá ngay <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 2. Thanh tìm kiếm --}}
            <form action="{{ url('/') }}" method="GET" class="flex-1 max-w-2xl hidden md:flex items-center bg-gray-100/80 rounded-full border border-transparent focus-within:border-green-400 focus-within:bg-white focus-within:ring-4 focus-within:ring-green-50 transition-all duration-300 h-11 overflow-hidden">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm hàng ngàn sản phẩm giá tốt..." class="w-full bg-transparent px-5 text-sm text-gray-700 placeholder-gray-400 border-none outline-none focus:ring-0 shadow-none h-full font-medium" autocomplete="off">
                @if(request()->has('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-7 h-full transition-colors font-bold text-sm flex items-center gap-2 outline-none cursor-pointer">
                    <i class="fa-solid fa-magnifying-glass"></i> Tìm
                </button>
            </form>

            {{-- 3. CỤM ICON CHỨC NĂNG CHÍNH --}}
            <div class="flex items-center gap-1 lg:gap-3 shrink-0">
                
                {{-- Giỏ hàng --}}
                <a href="{{ route('cart.index') }}" class="w-12 lg:w-14 h-14 flex flex-col justify-center items-center gap-1 text-gray-500 hover:text-green-600 rounded-xl hover:bg-green-50 transition-all group relative">
                    <div class="relative">
                        <i class="fa-solid fa-cart-shopping text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="absolute -top-2 -right-2.5 bg-red-500 text-white text-[10px] font-black rounded-full h-4 min-w-[16px] px-1 flex items-center justify-center border-2 border-white shadow-sm">
                            {{ Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->count() : 0 }}
                        </span>
                    </div>
                    <span class="text-[10px] font-bold hidden sm:block">Giỏ hàng</span>
                </a>

                @auth
                {{-- Đã lưu --}}
                <a href="#" class="w-12 lg:w-14 h-14 hidden lg:flex flex-col justify-center items-center gap-1 text-gray-500 hover:text-red-500 rounded-xl hover:bg-red-50 transition-all group">
                    <i class="fa-solid fa-heart text-xl transition-transform duration-300 group-hover:scale-110"></i>
                    <span class="text-[10px] font-bold">Đã lưu</span>
                </a>

                {{-- Nút Tin nhắn TỰ ĐỘNG NHẢY SỐ --}}
                @php
                    $initialUnread = \App\Models\Message::whereHas('conversation', function($q) {
                        $q->where('buyer_id', Auth::id())->orWhere('seller_id', Auth::id());
                    })->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();
                @endphp

                <button type="button" 
                   @click="$dispatch('toggle-chat')"
                   x-data="{ unreadCount: parseInt('{{ $initialUnread }}') || 0 }"
                   @update-unread-count.window="unreadCount = $event.detail === 'increment' ? unreadCount + 1 : parseInt($event.detail)"
                   class="w-12 lg:w-14 h-14 flex flex-col justify-center items-center gap-1 text-gray-500 hover:text-emerald-600 rounded-xl hover:bg-emerald-50 transition-all group relative bg-transparent border-none cursor-pointer outline-none">
                    
                    <div class="relative">
                        <i class="fa-solid fa-comment-dots text-xl transition-transform duration-300 group-hover:scale-110"></i>
                        <span x-show="unreadCount > 0" 
                              x-text="unreadCount > 99 ? '99+' : unreadCount" 
                              x-transition.scale 
                              class="absolute -top-1.5 -right-2.5 bg-red-500 text-white text-[10px] font-black rounded-full h-4 min-w-[16px] px-1 flex items-center justify-center border-2 border-white shadow-sm" 
                              style="display: none;">
                        </span>
                    </div>
                    <span class="text-[10px] font-bold hidden sm:block">Tin nhắn</span>
                </button>

                {{-- ========================================= --}}
                {{-- KHU VỰC THÔNG BÁO (DỮ LIỆU THẬT TỪ DB)    --}}
                {{-- ========================================= --}}
                <div x-data="{ 
                        openNotifications: false, 
                        notiCount: {{ $unreadNotiCount ?? 0 }},
                        markAllRead() {
                            if(this.notiCount === 0) return;
                            fetch('/notifications/mark-as-read', {
                                method: 'POST',
                                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content') }
                            }).then(() => {
                                this.notiCount = 0;
                                document.querySelectorAll('.noti-wrapper').forEach(el => {
                                    el.classList.remove('bg-blue-50/40', 'bg-blue-50/30', 'hover:bg-blue-50/60', 'hover:bg-blue-50/50');
                                    el.classList.add('bg-white', 'hover:bg-gray-50');
                                    const dot = el.querySelector('.noti-dot, .full-noti-bar');
                                    if(dot) dot.remove();
                                });
                            });
                        }
                    }" class="relative hidden xl:block z-[100]">
                    
                    <button @click="openNotifications = !openNotifications" @click.away="openNotifications = false" 
                       class="w-12 lg:w-14 h-14 flex flex-col justify-center items-center gap-1 text-gray-500 hover:text-orange-500 rounded-xl hover:bg-orange-50 transition-all group relative border-none outline-none cursor-pointer">
                        
                        <div class="relative">
                            <i class="fa-solid fa-bell text-xl transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12"></i>
                            
                            {{-- Chấm đỏ báo có thông báo mới --}}
                            <span x-show="notiCount > 0" x-text="notiCount > 99 ? '99+' : notiCount" 
                                  class="absolute -top-1.5 -right-1.5 bg-red-500 text-white text-[10px] font-black rounded-full h-4 min-w-[16px] px-1 flex items-center justify-center border-2 border-white shadow-sm" style="display: none;">
                            </span>
                        </div>
                        <span class="text-[10px] font-bold">Thông báo</span>
                    </button>

                    {{-- Ô Dropdown Danh sách Thông báo --}}
                    <div x-show="openNotifications" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-3 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-3 scale-95"
                         class="absolute top-[120%] right-0 w-[400px] bg-white rounded-3xl shadow-[0_15px_60px_rgba(0,0,0,0.15)] border border-gray-100 overflow-hidden z-[9999]" style="display: none;">
                        
                        {{-- Header --}}
                        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/80 backdrop-blur-sm">
                            <h3 class="font-black text-gray-900 text-base">Thông báo</h3>
                            <button @click="markAllRead()" type="button" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition-colors bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-100">Đánh dấu đã đọc</button>
                        </div>

                        {{-- Danh sách thông báo --}}
                        <div class="max-h-[420px] overflow-y-auto no-scrollbar bg-white">
                            @forelse($notifications as $noti)
                                @php
                                    $isRead = $noti->read_at !== null;
                                    $type = $noti->data['type'] ?? 'info';
                                    
                                    $colorClass = match($type) {
                                        'success' => 'bg-green-100 text-green-600 border-green-200/50',
                                        'warning' => 'bg-orange-100 text-orange-600 border-orange-200/50',
                                        'danger'  => 'bg-red-100 text-red-500 border-red-200/50',
                                        default   => 'bg-blue-100 text-blue-600 border-blue-200/50',
                                    };
                                @endphp

                                {{-- Đã bọc bằng thẻ div (noti-wrapper) để chứa cả nút Xóa --}}
                                <div class="noti-wrapper relative group border-b border-gray-50 transition-all duration-300 {{ $isRead ? 'bg-white hover:bg-gray-50' : 'bg-blue-50/40 hover:bg-blue-50/60' }}">
                                    
                                    <a href="{{ route('notifications.click', $noti->id) }}" class="flex gap-4 p-4 pr-12 cursor-pointer">
                                        <div class="w-11 h-11 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm border">
                                            <i class="fa-solid {{ $noti->data['icon'] ?? 'fa-bell' }}"></i>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <p class="text-sm {{ $isRead ? 'text-gray-600' : 'text-gray-800' }} leading-snug">{!! $noti->data['message'] ?? '' !!}</p>
                                            <p class="text-[11px] {{ $isRead ? 'text-gray-400' : 'text-blue-600' }} font-bold mt-1.5 flex items-center gap-1.5">
                                                <i class="fa-regular fa-clock"></i> {{ $noti->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        
                                        @if(!$isRead)
                                            <div class="noti-dot w-2 h-2 rounded-full bg-blue-500 mt-2 shrink-0 shadow-sm"></div>
                                        @endif
                                    </a>

                                    {{-- Nút xóa lơ lửng bên phải --}}
                                    <button onclick="deleteNotification('{{ $noti->id }}', this)" class="absolute right-3 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full opacity-0 group-hover:opacity-100 transition-all" title="Xóa thông báo">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="p-10 flex flex-col items-center justify-center text-center text-gray-400">
                                    <i class="fa-regular fa-bell-slash text-4xl mb-3 text-gray-200"></i>
                                    <p class="text-sm font-medium">Bạn chưa có thông báo nào.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Footer --}}
                        <div class="p-4 border-t border-gray-100 text-center bg-gray-50/50">
                            <a href="{{ route('notifications.index') }}" class="text-sm font-bold text-gray-500 hover:text-emerald-600 transition-colors">Xem tất cả thông báo</a>
                        </div>
                    </div>
                </div>
                @endauth

                <div class="w-px h-8 bg-gray-200 mx-1 hidden md:block"></div>

                {{-- Đăng tin --}}
                <a href="{{ route('products.create') }}" class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 lg:px-5 py-2.5 rounded-full font-bold hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg shadow-green-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
                    <i class="fa-solid fa-pen-to-square"></i> <span class="hidden sm:inline">Đăng tin</span>
                </a>

                {{-- Tài khoản User --}}
                @if (Route::has('login'))
                <div class="pl-1 lg:pl-2">
                    @auth
                    <div class="relative group py-2" x-data="{ open: false }">
                        <div class="flex items-center gap-3 cursor-pointer bg-white border border-gray-100 p-1.5 lg:pr-4 rounded-full shadow-sm hover:shadow transition-all" @click="open = !open" @click.away="open = false">
                            <div class="relative">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-9 h-9 rounded-full object-cover border-2 border-white">
                                @else
                                    <div class="w-9 h-9 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold text-lg border-2 border-white uppercase">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            <div class="hidden md:block">
                                <p class="font-bold text-gray-800 text-sm leading-tight max-w-[100px] truncate">{{ Auth::user()->name }}</p>
                            </div>
                            <i class="fa-solid fa-angle-down text-gray-400 text-xs hidden md:block"></i>
                        </div>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-2" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            class="absolute top-full right-0 mt-2 w-60 z-50" style="display: none;">
                            <div class="bg-white rounded-2xl shadow-[0_10px_40px_rgba(0,0,0,0.1)] border border-gray-100 overflow-hidden py-2">
                                <div class="px-5 py-3 border-b border-gray-50 bg-gray-50/50 mb-2">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 transition mx-2 rounded-xl group">
                                    <i class="fa-solid fa-chart-line w-5 text-center transition-transform group-hover:scale-110"></i> Bảng điều khiển
                                </a>
                                <a href="{{ url('/profile') }}" class="flex items-center gap-3 px-5 py-2.5 text-sm font-medium text-gray-600 hover:bg-green-50 hover:text-green-700 transition mx-2 rounded-xl group">
                                    <i class="fa-regular fa-circle-user w-5 text-center transition-transform group-hover:scale-110"></i> Cài đặt tài khoản
                                </a>
                                <div class="border-t border-gray-100 my-2 mx-4"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-[calc(100%-1rem)] mx-auto text-left flex items-center gap-3 px-5 py-2.5 text-sm font-bold text-red-500 hover:bg-red-50 hover:text-red-600 rounded-xl transition group">
                                        <i class="fa-solid fa-arrow-right-from-bracket w-5 text-center transition-transform group-hover:scale-110"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="flex items-center gap-3 pl-2">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-green-700 transition py-2 px-3 rounded-xl hover:bg-gray-50">Đăng nhập</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-green-50 text-green-600 px-5 py-2 rounded-xl hover:bg-green-100 transition shadow-sm border border-green-100">Đăng ký</a>
                        @endif
                    </div>
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- ===== VÙNG CHỨA NỘI DUNG THAY ĐỔI CỦA TỪNG TRANG ===== --}}
    <main class="flex-grow w-full">
        @yield('content')
    </main>

    {{-- ===== CHÂN TRANG DÙNG CHUNG ===== --}}
    <footer class="bg-white border-t border-gray-100 mt-16 pt-16 pb-8">
        <div class="max-w-[1400px] mx-auto px-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div>
                <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4 group inline-block">
                    <div class="text-green-700 text-3xl"><i class="fa-solid fa-hand-holding-hand"></i></div>
                    <span class="text-3xl font-black text-green-700 tracking-tighter uppercase">2HAND</span>
                </a>
                <p class="text-sm leading-relaxed text-gray-500 mb-6">Nền tảng mua bán, trao đổi đồ cũ uy tín hàng đầu. Giúp bạn thanh lý nhanh chóng, tìm kiếm dễ dàng và kết nối an toàn.</p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-pink-50 text-pink-600 flex items-center justify-center hover:bg-pink-600 hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-black/5 text-gray-800 flex items-center justify-center hover:bg-black hover:text-white transition-colors"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <div>
                <h4 class="font-black text-gray-900 mb-6 uppercase tracking-wider">Về 2HAND</h4>
                <ul class="space-y-3 text-sm text-gray-500 font-medium">
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Giới thiệu về chúng tôi</a></li>
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Quy chế hoạt động sàn</a></li>
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Chính sách bảo mật</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-black text-gray-900 mb-6 uppercase tracking-wider">Hỗ trợ khách hàng</h4>
                <ul class="space-y-3 text-sm text-gray-500 font-medium">
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Trung tâm trợ giúp</a></li>
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Hướng dẫn mua bán an toàn</a></li>
                    <li><a href="#" class="hover:text-green-600 transition-colors flex items-center gap-2"><i class="fa-solid fa-chevron-right text-[10px] text-green-500"></i> Hướng dẫn thanh toán COD</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-black text-gray-900 mb-6 uppercase tracking-wider">Liên hệ</h4>
                <ul class="space-y-4 text-sm text-gray-500 font-medium">
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-phone"></i></div>
                        <div><p class="text-xs text-gray-400">Hotline hỗ trợ</p><p class="text-gray-900 font-bold text-base">1900 1234</p></div>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-envelope"></i></div>
                        <div><p class="text-xs text-gray-400">Email chăm sóc</p><p class="text-gray-900 font-bold">support@2hand.vn</p></div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="max-w-[1400px] mx-auto px-4 border-t border-gray-100 pt-8 text-center text-xs text-gray-400 font-medium">
            <p>&copy; 2026 2HAND. Phát triển bởi Duong Van Qui.</p>
        </div>
    </footer>

    {{-- ===== KHUNG CHAT LƠ LỬNG DÙNG CHUNG ===== --}}
    @include('chat.floating')

    {{-- Hàm JS chung phục vụ việc xóa thông báo mượt mà bằng Ajax --}}
    <script>
        function deleteNotification(id, btnElement) {
            if(!confirm('Bạn có chắc chắn muốn xóa thông báo này?')) return;

            fetch(`/notifications/${id}`, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            }).then(res => res.json())
            .then(data => {
                if(data.success) {
                    const wrapper = btnElement.closest('.noti-wrapper');
                    wrapper.style.opacity = '0';
                    wrapper.style.transform = 'scale(0.95)';
                    setTimeout(() => wrapper.remove(), 300);
                }
            });
        }
    </script>

    @yield('scripts')
</body>
</html>