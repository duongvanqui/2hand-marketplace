<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->title }} - 2HAND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-gray-50 font-sans text-gray-900 flex flex-col min-h-screen">

    {{-- ===== NAVBAR ĐỒNG BỘ ===== --}}
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
                            <li @mouseenter="activeCategory = 1" :class="activeCategory === 1 ? 'bg-white text-green-700 border-l-4 border-green-600 font-bold' : 'hover:bg-gray-100 border-l-4 border-transparent'" class="px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors"><i class="fa-solid fa-desktop w-5 text-center text-gray-400"></i> Đồ điện tử</li>
                            <li @mouseenter="activeCategory = 2" :class="activeCategory === 2 ? 'bg-white text-green-700 border-l-4 border-green-600 font-bold' : 'hover:bg-gray-100 border-l-4 border-transparent'" class="px-4 py-3 cursor-pointer flex items-center gap-3 transition-colors"><i class="fa-solid fa-motorcycle w-5 text-center text-gray-400"></i> Xe cộ</li>
                        </ul>
                    </div>
                    <div class="w-2/3 p-6 overflow-y-auto bg-white">
                        <div x-show="activeCategory === 1" class="grid grid-cols-2 gap-4">
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Điện thoại</a>
                            <a href="#" class="text-gray-600 hover:text-green-600 block py-1">Laptop</a>
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
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-green-700 text-sm flex flex-col items-center gap-1 transition relative">
                    <div class="relative">
                        <i class="fa-solid fa-cart-shopping text-lg"></i>
                        <span class="cart-counter absolute -top-1.5 -right-2 bg-red-500 text-white text-[9px] font-bold rounded-full h-4 w-4 flex items-center justify-center border border-white">
                            {{ Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->count() : 0 }}
                        </span>
                    </div>
                    <span class="hidden lg:inline text-[11px] font-medium">Giỏ hàng</span>
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
                            <div class="w-9 h-9 bg-green-100 text-green-700 rounded-full flex items-center justify-center font-bold border border-green-200 shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            @endif

                            <div class="hidden md:block text-sm">
                                <p class="font-bold text-gray-700 leading-tight">{{ Auth::user()->name }}</p>
                                <p class="text-[11px] text-green-600">Tài khoản <i class="fa-solid fa-angle-down ml-1"></i></p>
                            </div>
                        </div>

                        <div class="absolute top-full right-0 mt-0 hidden group-hover:block w-56 pt-2 z-50">
                            <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                                <div class="p-2 border-b border-gray-50">
                                    <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition"><i class="fa-solid fa-chart-line w-4"></i> Trang quản lý</a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-green-50 hover:text-green-700 rounded-lg transition"><i class="fa-regular fa-id-card w-4"></i> Hồ sơ cá nhân</a>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="p-2">
                                    @csrf
                                    <button type="submit" class="w-full text-left flex items-center gap-2 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 rounded-lg transition"><i class="fa-solid fa-arrow-right-from-bracket w-4"></i> Đăng xuất</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-green-700 transition">Đăng nhập</a>
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 pb-12 flex-grow w-full">

        {{-- BREADCRUMB (Điều hướng) --}}
        <nav class="flex text-sm text-gray-500 mb-6 font-medium mt-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-green-600 transition"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                        <a href="#" class="hover:text-green-600 ml-1 transition">{{ $product->category->name ?? 'Danh mục' }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                        <span class="text-gray-400 ml-1 truncate max-w-[200px]">{{ $product->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        {{-- HIỂN THỊ THÔNG BÁO GIỎ HÀNG BÊN TRÊN SẢN PHẨM --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center justify-between">
            <div class="flex items-center gap-3 font-medium">
                <i class="fa-solid fa-circle-check text-green-500 text-xl"></i> {{ session('success') }}
            </div>
            <a href="{{ route('cart.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">Xem giỏ hàng</a>
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center gap-3 font-medium">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i> {{ session('error') }}
        </div>
        @endif

        {{-- KHỐI CHI TIẾT SẢN PHẨM --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">

            {{-- 1. CỘT TRÁI: ẢNH SẢN PHẨM --}}
            <div class="lg:col-span-5 space-y-4">
                <div class="relative aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 group flex items-center justify-center">
                    @if($product->images && $product->images->count() > 0)
                    <img id="main-display" src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-contain transition-all duration-500 transform scale-95 group-hover:scale-100" alt="{{ $product->title }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100 font-medium">
                        <div class="text-center">
                            <i class="fa-regular fa-image text-4xl mb-2 block text-gray-300"></i>
                            Chưa có hình ảnh
                        </div>
                    </div>
                    @endif

                    @if($product->images && $product->images->count() > 1)
                    <button onclick="prevImg()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-white hover:text-green-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <button onclick="nextImg()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-white hover:text-green-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                    @endif
                </div>

                {{-- Ảnh thu nhỏ (Thumbnails) --}}
                <div class="flex justify-start gap-2 overflow-x-auto py-1 no-scrollbar">
                    @if($product->images && $product->images->count() > 0)
                    @foreach($product->images as $index => $img)
                    <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 cursor-pointer transition-all thumb-item {{ $index === 0 ? 'border-green-500 ring-2 ring-green-100' : 'border-transparent opacity-60 hover:opacity-100' }}"
                        onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this, {{ $index }})">
                        <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                    @endif
                </div>

                {{-- Công cụ chia sẻ / Báo cáo --}}
                <div class="flex justify-between items-center pt-2 text-sm text-gray-500 font-medium">
                    <div class="flex gap-4">
                        <button class="hover:text-green-600 transition flex items-center gap-1"><i class="fa-regular fa-heart"></i> Lưu tin</button>
                        <button class="hover:text-green-600 transition flex items-center gap-1"><i class="fa-solid fa-share-nodes"></i> Chia sẻ</button>
                    </div>
                    <button class="hover:text-red-500 transition flex items-center gap-1 text-xs"><i class="fa-solid fa-flag"></i> Báo cáo tin</button>
                </div>
            </div>

            {{-- 2. CỘT PHẢI: THÔNG TIN SẢN PHẨM & NGƯỜI BÁN --}}
            <div class="lg:col-span-7 flex flex-col">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">{{ $product->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500 font-medium">
                        <span class="flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded text-gray-700">
                            <i class="fa-regular fa-clock"></i> Đăng {{ $product->created_at->diffForHumans() }}
                        </span>
                        <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $product->location }}</span>
                        <span class="flex items-center gap-1"><i class="fa-regular fa-eye"></i> {{ $product->view_count }} lượt xem</span>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl mt-6 border border-green-100">
                    <p class="text-gray-600 text-sm font-medium mb-1">Giá thanh lý:</p>
                    <div class="flex items-end gap-3">
                        <p class="text-4xl font-black text-red-600">{{ number_format($product->price) }} <span class="text-2xl underline decoration-2">đ</span></p>
                        @if($product->original_price && $product->original_price > $product->price)
                        <p class="text-lg text-gray-400 line-through font-semibold mb-1">{{ number_format($product->original_price) }} đ</p>
                        @endif
                    </div>
                </div>

                {{-- THẺ NGƯỜI BÁN --}}
                <div class="flex items-center justify-between p-4 mt-6 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-4">
                        @if($product->user && $product->user->avatar)
                        <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-green-200">
                        @else
                        <div class="w-12 h-12 bg-green-100 text-green-700 font-bold text-xl rounded-full flex items-center justify-center border border-green-200 shrink-0">
                            {{ substr($product->user->name ?? 'U', 0, 1) }}
                        </div>
                        @endif

                        <div>
                            <p class="font-bold text-gray-900">{{ $product->user->name ?? 'Người bán ẩn danh' }}</p>
                            <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                                <span class="flex items-center text-yellow-500"><i class="fa-solid fa-star mr-1"></i> 5.0</span>
                                <span>•</span>
                                <span>Hoạt động 5 phút trước</span>
                            </div>
                        </div>
                    </div>
                    <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition">
                        Xem trang
                    </a>
                </div>

                <div class="grid grid-cols-2 gap-4 mt-6">
                    <div class="border border-gray-200 p-4 rounded-xl flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-medal"></i></div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Tình trạng</p>
                            <p class="font-bold text-gray-800">Độ mới {{ $product->condition_pct }}%</p>
                        </div>
                    </div>
                    <div class="border border-gray-200 p-4 rounded-xl flex items-center gap-3">
                        <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-truck-fast"></i></div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold">Giao hàng</p>
                            <p class="font-bold text-gray-800">Thỏa thuận</p>
                        </div>
                    </div>
                </div>

                {{-- Nút Hành động --}}
                <div class="flex gap-4 mt-8 mt-auto">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1 ajax-add-to-cart">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                    </form>
                    <button type="button" onclick="openGlobalChat({{ $product->id }})" class="px-6 py-3.5 border-2 border-green-600 text-green-700 font-bold rounded-xl hover:bg-green-50 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-comment-dots"></i> NHẮN TIN
                    </button>
                </div>
            </div>
        </div>

        {{-- 3. KHỐI BẢNG THÔNG SỐ KỸ THUẬT --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-green-500 pl-3">Thông số kỹ thuật</h3>

            <div class="border-t border-gray-200">
                @if(!empty($product->specifications))
                @php
                $lines = explode("\n", str_replace("\r", "", $product->specifications));
                @endphp

                @foreach($lines as $line)
                @if(str_contains($line, ':'))
                @php
                [$label, $value] = explode(':', $line, 2);
                @endphp
                <div class="grid grid-cols-1 md:grid-cols-4 py-3 border-b border-gray-100 text-[15px] items-center">
                    <span class="text-gray-500 font-medium md:col-span-1 mb-1 md:mb-0">{{ trim($label) }}</span>
                    <span class="text-gray-900 font-medium md:col-span-3">{{ trim($value) }}</span>
                </div>
                @endif
                @endforeach
                @else
                <p class="text-gray-500 text-sm py-4 italic">Người bán chưa cung cấp bảng thông số kỹ thuật.</p>
                @endif
            </div>
        </div>

        {{-- 4. KHỐI MÔ TẢ CHI TIẾT --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 mt-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-green-500 pl-3">Mô tả sản phẩm</h3>
            <div class="prose prose-green max-w-none">
                <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
            </div>
        </div>

    </main>

    {{-- ===== CHÂN TRANG ===== --}}
    <footer class="bg-white border-t border-gray-100 mt-12 py-12 text-sm text-gray-500">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4 text-green-700 font-black text-xl">
                    <i class="fa-solid fa-hand-holding-hand"></i> <span>2HAND</span>
                </div>
                <p class="text-xs leading-relaxed text-gray-400">Nền tảng mua bán đồ cũ uy tín, giúp bạn dễ dàng tìm kiếm món đồ phù hợp với giá tốt.</p>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Về chúng tôi</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#" class="hover:text-green-700 transition">Giới thiệu</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">Quy chế hoạt động</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Hỗ trợ khách hàng</h4>
                <ul class="space-y-2.5 text-xs">
                    <li><a href="#" class="hover:text-green-700 transition">Trung tâm trợ giúp</a></li>
                    <li><a href="#" class="hover:text-green-700 transition">An toàn giao dịch</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-gray-800 mb-4 uppercase text-xs tracking-wider">Liên hệ</h4>
                <ul class="space-y-2.5 text-xs mb-4">
                    <li>Hotline: <span class="font-bold text-green-600">1900 1234</span></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 border-t border-gray-50 mt-10 pt-6 text-center text-xs text-gray-400">
            &copy; 2026 2HAND. All rights reserved.
        </div>
    </footer>

    {{-- SCRIPT SLIDER ẢNH --}}
    <script>
        const images = [
            @foreach($product->images as $img)
            "{{ asset('storage/' . $img->image_path) }}",
            @endforeach
        ];

        let currentIndex = 0;

        function changeImage(src, element, index) {
            currentIndex = index;
            document.getElementById('main-display').src = src;

            document.querySelectorAll('.thumb-item').forEach(el => {
                el.classList.remove('border-green-500', 'ring-2', 'ring-green-100', 'opacity-100');
                el.classList.add('border-transparent', 'opacity-60');
            });

            if (element) {
                element.classList.remove('border-transparent', 'opacity-60');
                element.classList.add('border-green-500', 'ring-2', 'ring-green-100', 'opacity-100');
            }
        }

        function prevImg() {
            if (images.length <= 1) return;
            currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
            updateSlider();
        }

        function nextImg() {
            if (images.length <= 1) return;
            currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
            updateSlider();
        }

        function updateSlider() {
            const thumbElements = document.querySelectorAll('.thumb-item');
            if (thumbElements.length > 0) {
                changeImage(images[currentIndex], thumbElements[currentIndex], currentIndex);
            }
        }
    </script>

    {{-- KHỐI SCRIPT LOGIC AJAX (GIỎ HÀNG & NHẮN TIN) --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Logic Giỏ hàng
            $('.ajax-add-to-cart').on('submit', function(e) {
                e.preventDefault();
                let form = $(this);
                let url = form.attr('action');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: form.serialize(),
                    success: function(response) {
                        if (response.success) {
                            alert(response.message);
                            $('.cart-counter').text(response.cartCount);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            });
        });

        // Logic Nhắn tin nổi bằng jQuery AJAX
        function openGlobalChat(productId) {
            console.log("Đang gọi cổng chat cho sản phẩm: " + productId);
            $.ajax({
                type: "POST",
                url: `/chat/start/${productId}`,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        console.log("Nhận kết quả phòng chat ID từ server: " + response.conversation_id);
                        // Bắn sự kiện mở widget chat lơ lửng tại app.blade.php
                        window.dispatchEvent(new CustomEvent('open-chat', { 
                            detail: response.conversation_id 
                        }));
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        alert("Vui lòng đăng nhập để thực hiện nhắn tin!");
                        window.location.href = "{{ route('login') }}";
                    } else {
                        alert("Không thể thực hiện tác vụ (Bạn không thể tự chat với chính mình).");
                    }
                }
            });
        }
    </script>

    {{-- ========================================== --}}
    {{-- KHUNG CHAT LƠ LỬNG TRÊN TRANG CHI TIẾT     --}}
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