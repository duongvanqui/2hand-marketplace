<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Quản trị - 2HAND')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif !important;
        }

        /* Ẩn thanh cuộn nhưng vẫn cuộn được */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    @yield('styles')
</head>

<body class="font-sans antialiased text-gray-800 bg-gray-50">

    <div class="flex min-h-screen overflow-hidden" x-data="{ sidebarOpen: true }">

        {{-- ========================================== --}}
        {{-- SIDEBAR HIỆN ĐẠI (CÓ ANIMATION & LOGO) --}}
        {{-- ========================================== --}}
        <aside :class="sidebarOpen ? 'w-72' : 'w-20'" class="bg-white shadow-[10px_0_40px_rgba(0,0,0,0.03)] h-screen transition-all duration-500 ease-in-out flex flex-col shrink-0 sticky top-0 z-40 border-r border-gray-100">

            {{-- 1. LOGO TRANG WEB --}}
            <div class="h-20 flex items-center justify-center border-b border-gray-50 shrink-0 transition-all duration-300">
                <a href="{{ url('/') }}" class="flex items-center gap-3" title="Về trang chủ mua sắm">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-200 transform transition-transform hover:scale-110 hover:rotate-3">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span x-show="sidebarOpen" x-transition.opacity.duration.300ms class="text-2xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">
                        2<span class="text-emerald-500">HAND</span>
                    </span>
                </a>
            </div>

            {{-- 2. THÔNG TIN USER --}}
            <div class="p-5 flex items-center gap-4 shrink-0 mt-2">
                <div class="relative group cursor-pointer">
                    @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-md group-hover:border-emerald-400 transition-colors">
                    @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-emerald-100 to-green-50 text-emerald-600 flex items-center justify-center font-bold text-lg border-2 border-white shadow-md group-hover:border-emerald-400 transition-colors">
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    </div>
                    @endif
                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-white rounded-full"></div>
                </div>

                <div x-show="sidebarOpen" x-transition.opacity.duration.300ms class="flex flex-col justify-center whitespace-nowrap overflow-hidden">
                    <h2 class="text-sm font-bold text-gray-800 truncate leading-tight hover:text-emerald-600 cursor-pointer transition-colors">{{ Auth::user()->name ?? 'Thành viên' }}</h2>
                    <span class="text-[11px] font-semibold text-emerald-500 bg-emerald-50 px-2 py-0.5 rounded-full mt-1 w-fit">
                        {{ (Auth::user()->role ?? '') === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                    </span>
                </div>
            </div>

            {{-- 3. MENU ĐIỀU HƯỚNG CÓ ANIMATION MƯỢT MÀ --}}
            <nav class="flex-1 px-4 pb-6 space-y-1.5 overflow-y-auto no-scrollbar">

                <p x-show="sidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2 mb-2 mt-4 whitespace-nowrap">Quản lý cá nhân</p>

                <a href="{{ route('dashboard') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-chart-pie text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Tổng quan</span>
                </a>

                <a href="{{ route('orders.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('orders.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-clipboard-list text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Quản lý Đơn hàng</span>
                </a>

                <a href="{{ route('wallet.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('wallet.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-wallet text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Ví 2HAND</span>
                </a>

                {{-- Logic đếm số TIN NHẮN CHƯA ĐỌC --}}
                @php
                $unreadCount = \App\Models\Message::whereHas('conversation', function($q) {
                    $q->where('buyer_id', Auth::id())->orWhere('seller_id', Auth::id());
                })
                ->where('sender_id', '!=', Auth::id())
                ->where('is_read', false)
                ->count();
                @endphp

                <a href="{{ route('chat.index') }}"
                    x-data="{ unreadCount: parseInt('{{ $unreadCount }}') || 0 }"
                    @update-unread-count.window="unreadCount = $event.detail === 'increment' ? unreadCount + 1 : parseInt($event.detail)"
                    class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('chat.index') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">

                    <i class="fa-solid fa-comment-dots text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity flex-1">Tin nhắn</span>

                    <span x-show="sidebarOpen && unreadCount > 0"
                        x-text="unreadCount > 99 ? '99+' : unreadCount"
                        x-transition.scale
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm"
                        style="display: none;">
                    </span>
                </a>

                {{-- ========================================== --}}
                {{-- NÚT THÔNG BÁO (VỪA ĐƯỢC THÊM VÀO Ở ĐÂY)    --}}
                {{-- ========================================== --}}
                @php
                    $unreadNotiCount = Auth::check() ? Auth::user()->unreadNotifications->count() : 0;
                @endphp
                <a href="{{ route('notifications.index') }}"
                    x-data="{ notiCount: parseInt('{{ $unreadNotiCount }}') || 0 }"
                    @update-noti-count.window="notiCount = parseInt($event.detail)"
                    class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('notifications.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">

                    <i class="fa-solid fa-bell text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110 group-hover:-rotate-12"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity flex-1">Thông báo</span>

                    <span x-show="sidebarOpen && notiCount > 0"
                        x-text="notiCount > 99 ? '99+' : notiCount"
                        x-transition.scale
                        class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full shadow-sm"
                        style="display: none;">
                    </span>
                </a>

                <a href="#" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden text-gray-500 hover:bg-gray-50 hover:text-red-500 hover:translate-x-1">
                    <i class="fa-solid fa-heart text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110 group-hover:text-red-500"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Tin đã lưu (Yêu thích)</span>
                </a>

                <a href="#" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden text-gray-500 hover:bg-gray-50 hover:text-yellow-500 hover:translate-x-1">
                    <i class="fa-solid fa-star text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110 group-hover:text-yellow-500"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Đánh giá của tôi</span>
                </a>

                <a href="{{ route('profile.edit') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('profile.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-emerald-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-user-gear text-lg w-8 text-center transition-transform duration-300 group-hover:rotate-45"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Cài đặt tài khoản</span>
                </a>

                {{-- KHOẢNG TRỐNG CHO ADMIN --}}
                @if((Auth::user()->role ?? '') === 'admin')
                <p x-show="sidebarOpen" class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2 mb-2 mt-8 whitespace-nowrap">Hệ thống (Admin)</p>

                <a href="{{ route('admin.dashboard') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-chart-line text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Tổng quan Admin</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('admin.users.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-users text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Quản lý Tài khoản</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-list-check text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Quản lý Danh mục</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('admin.products.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-boxes-stacked text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Quản lý Sản phẩm</span>
                </a>

                <a href="{{ route('admin.wallet.index') }}" class="relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden {{ request()->routeIs('admin.wallet.*') ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-200/50' : 'text-gray-500 hover:bg-gray-50 hover:text-indigo-600 hover:translate-x-1' }}">
                    <i class="fa-solid fa-vault text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                    <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Quản trị Tài chính</span>
                </a>
                @endif
            </nav>

            {{-- Nút Đăng xuất đặt dưới cùng --}}
            <div class="p-4 border-t border-gray-100">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full relative flex items-center px-3 py-3 rounded-xl font-medium transition-all duration-300 group overflow-hidden text-red-500 hover:bg-red-50 hover:translate-x-1">
                        <i class="fa-solid fa-arrow-right-from-bracket text-lg w-8 text-center transition-transform duration-300 group-hover:scale-110"></i>
                        <span x-show="sidebarOpen" class="whitespace-nowrap transition-opacity">Đăng xuất</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== KHU VỰC HIỂN THỊ NỘI DUNG CHÍNH ===== --}}
        <main class="flex-1 h-screen overflow-y-auto relative">

            {{-- THANH HEADER TRÊN CÙNG --}}
            <div class="sticky top-0 z-30 bg-gray-50/80 backdrop-blur-lg border-b border-gray-200/50 px-8 py-5 mb-8 flex items-center justify-between">
                <div class="flex items-center space-x-4">

                    {{-- Nút Hamburger --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-200 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all flex items-center justify-center outline-none group">
                        <i class="fa-solid fa-bars-staggered transition-transform group-hover:scale-110"></i>
                    </button>

                    {{-- NÚT QUAY LẠI TỰ ĐỘNG --}}
                    @hasSection('back_button')
                    @yield('back_button')
                    @endif

                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">
                        @yield('header_title', 'Bảng điều khiển')
                    </h1>
                </div>

                <div class="flex items-center gap-4">
                    {{-- Các Action Buttons --}}
                    @yield('header_actions')
                </div>
            </div>

            {{-- Đổ nội dung của các trang con vào đây --}}
            <div class="px-8 pb-12">
                @yield('content')
            </div>

        </main>
    </div>

    @include('chat.floating')

    @yield('scripts')
</body>

</html>