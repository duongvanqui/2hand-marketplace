@extends('layouts.admin')

@section('title', 'Bảng điều khiển cá nhân - 2HAND')

{{-- ĐÃ SỬA: Bổ sung Breadcrumb (Thanh điều hướng) --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight">
        Bảng điều khiển cá nhân
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <span class="text-gray-900 font-bold">Tổng quan</span>
    </div>
</div>
@endsection

@section('content')
{{-- XỬ LÝ DỮ LIỆU TỰ ĐỘNG TRỰC TIẾP TRÊN VIEW --}}
@php
    $userId = Auth::id();
    $totalApproved = \App\Models\Product::where('user_id', $userId)->where('status', 'approved')->count();
    $pendingCount = \App\Models\Product::where('user_id', $userId)->where('status', 'pending')->count();
    $soldCount = \App\Models\Product::where('user_id', $userId)->where('status', 'sold')->count();

    // Lấy số lượng đơn hàng mua
    $orderCount = \App\Models\Order::where('buyer_id', $userId)->count();
    
    // Đếm số tin nhắn chưa đọc
    $unreadMsgDashboard = \App\Models\Message::whereHas('conversation', function($q) use ($userId) {
        $q->where('buyer_id', $userId)->orWhere('seller_id', $userId);
    })->where('sender_id', '!=', $userId)->where('is_read', false)->count();

    // Lấy 4 sản phẩm Yêu thích mới nhất
    $favoriteProducts = Auth::user()->favorites()->latest('favorites.created_at')->take(4)->get();

    // Lấy 5 thông báo (hoạt động) gần nhất
    $recentActivities = Auth::user()->notifications()->take(5)->get();

    // Lấy 4 sản phẩm cá nhân mới đăng để làm danh sách thu gọn
    $myProducts = \App\Models\Product::where('user_id', $userId)->latest()->take(4)->get();
@endphp

<div class="pb-10">

    {{-- ========================================== --}}
    {{-- BANNER CHÀO MỪNG CỰC KỲ ẤN TƯỢNG --}}
    {{-- ========================================== --}}
    <div class="bg-gradient-to-r from-emerald-500 via-emerald-600 to-green-600 rounded-[2rem] p-8 md:p-10 mb-8 shadow-xl shadow-emerald-200/50 text-white relative overflow-hidden flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="absolute right-0 top-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute left-10 bottom-0 w-32 h-32 bg-yellow-300 opacity-20 rounded-full blur-2xl translate-y-1/2"></div>
        
        <div class="relative z-10 text-center md:text-left">
            <h2 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Xin chào, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-emerald-50 font-medium text-sm md:text-base max-w-xl leading-relaxed">Chào mừng bạn quay trở lại 2HAND. Hãy kiểm tra các hoạt động mới nhất và tiếp tục săn những món hời ngay hôm nay nhé!</p>
        </div>

        <div class="relative z-10 shrink-0">
            <a href="{{ route('products.create') }}" class="bg-white text-emerald-600 font-bold px-8 py-3.5 rounded-xl shadow-lg hover:bg-emerald-50 transition-colors flex items-center gap-2 transform hover:-translate-y-1 duration-300">
                <i class="fa-solid fa-plus"></i> Đăng tin bán ngay
            </a>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 1. KHỐI THỐNG KÊ TỔNG QUAN (5 CARDS) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mb-8">
        
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-store"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-900">{{ $totalApproved }}</h3>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Đang bán</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-regular fa-clock"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-900">{{ $pendingCount }}</h3>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Chờ duyệt</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-900">{{ $soldCount }}</h3>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Đã bán</p>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cart-arrow-down"></i>
            </div>
            <h3 class="text-3xl font-black text-gray-900">{{ $orderCount }}</h3>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mt-1">Đơn hàng mua</p>
        </div>

        {{-- Card 5: Tin nhắn mới (Interactive) --}}
        <div x-data="{ unreadCount: parseInt('{{ $unreadMsgDashboard }}') || 0 }"
             @update-unread-count.window="unreadCount = $event.detail === 'increment' ? unreadCount + 1 : parseInt($event.detail)"
             onclick="window.location.href='{{ route('chat.index') }}'"
             class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group cursor-pointer relative overflow-hidden">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform relative z-10 border border-red-100">
                <i class="fa-regular fa-comment-dots"></i>
            </div>
            <h3 class="text-3xl font-black text-red-600 relative z-10" x-text="unreadCount">0</h3>
            <p class="text-xs text-red-400 font-bold uppercase tracking-wider mt-1 relative z-10">Tin nhắn chưa đọc</p>
            <div class="absolute inset-0 bg-red-50/30 transform scale-y-0 origin-bottom group-hover:scale-y-100 transition-transform duration-300"></div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. KHU VỰC CHIA 2 CỘT --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">

        {{-- CỘT TRÁI: HOẠT ĐỘNG GẦN ĐÂY --}}
        <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col h-[500px]">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between shrink-0 bg-gray-50/30">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2"><i class="fa-solid fa-bolt text-yellow-500"></i> Hoạt động gần đây</h3>
                <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg transition-colors">Xem tất cả</a>
            </div>
            <div class="p-6 flex-1 overflow-y-auto no-scrollbar relative">
                @if($recentActivities->count() > 0)
                <div class="relative before:absolute before:inset-y-0 before:left-5 before:w-[2px] before:bg-gray-100 space-y-6">
                    @foreach($recentActivities as $activity)
                        @php
                            $type = $activity->data['type'] ?? 'info';
                            $icon = $activity->data['icon'] ?? 'fa-bell';
                            $message = $activity->data['message'] ?? 'Bạn có một thông báo mới.';
                            
                            $colorClass = match($type) {
                                'success'  => 'bg-emerald-100 text-emerald-600 border-emerald-200',
                                'danger'   => 'bg-red-100 text-red-600 border-red-200',
                                'warning'  => 'bg-yellow-100 text-yellow-600 border-yellow-200',
                                'favorite' => 'bg-pink-100 text-pink-600 border-pink-200', 
                                default    => 'bg-blue-100 text-blue-600 border-blue-200',
                            };

                            // BẢO MẬT LOGIC: Đổi link để chống lỗi chuyển hướng
                            $notiTitle = strtolower($activity->data['title'] ?? '');
                            $notiMsg = strtolower($activity->data['message'] ?? '');
                            $url = $activity->data['url'] ?? '#';

                            if (str_contains($notiMsg, 'khóa') || str_contains($notiMsg, 'vi phạm') || str_contains($notiTitle, 'từ chối') || str_contains($notiMsg, 'từ chối')) {
                                $url = route('my.products');
                            } elseif (str_contains($notiTitle, 'đẩy lên trang đầu') || str_contains($notiTitle, 'đẩy tin')) {
                                $url = url('/');
                            }
                        @endphp

                        <div class="relative flex items-start gap-4 group">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white {{ $colorClass }} shrink-0 relative z-10 shadow-sm">
                                <i class="fa-solid {{ $icon }} text-sm"></i>
                            </div>
                            
                            <div class="flex-1 pt-1">
                                <a href="{{ $url }}" class="block p-3 -mt-3 -ml-3 rounded-2xl hover:bg-gray-50 border border-transparent hover:border-gray-100 transition-colors">
                                    <p class="text-sm font-medium text-gray-800 leading-snug">{!! $message !!}</p>
                                    <p class="text-[11px] font-bold text-gray-400 mt-2 flex items-center gap-1.5"><i class="fa-regular fa-clock"></i> {{ $activity->created_at->diffForHumans() }}</p>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                @else
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-inner">
                        <i class="fa-regular fa-bell-slash text-4xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500">Chưa có hoạt động nào gần đây</p>
                </div>
                @endif
            </div>
        </div>

        {{-- CỘT PHẢI: SẢN PHẨM CỦA TÔI --}}
        <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col h-[500px]">
            <div class="p-6 border-b border-gray-50 flex items-center justify-between shrink-0 bg-gray-50/30">
                <h3 class="font-black text-gray-900 text-lg flex items-center gap-2"><i class="fa-solid fa-box-open text-blue-500"></i> Quản lý nhanh tin bán</h3>
                <a href="{{ route('my.products') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">Chi tiết</a>
            </div>
            <div class="p-3 flex-1 overflow-y-auto no-scrollbar space-y-2">
                @forelse($myProducts as $product)
                <a href="{{ route('products.show', $product->slug) }}" class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-2xl transition-colors border border-transparent hover:border-gray-100 group {{ $product->status === 'sold' ? 'opacity-60' : '' }}">
                    <div class="w-16 h-16 rounded-xl bg-gray-50 overflow-hidden shrink-0 border border-gray-200 shadow-sm relative">
                        @if($product->images && $product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image"></i></div>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 truncate group-hover:text-emerald-600 transition-colors">{{ $product->title }}</h4>
                        <p class="text-[15px] font-black text-red-600 mt-1">{{ number_format($product->price) }} đ</p>
                    </div>
                    <div class="text-right shrink-0">
                        @if($product->status === 'approved')
                            <span class="inline-block px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-md text-[10px] font-bold mb-1">Đang bán</span>
                        @elseif($product->status === 'pending')
                            <span class="inline-block px-2.5 py-1 bg-yellow-50 border border-yellow-100 text-yellow-700 rounded-md text-[10px] font-bold mb-1">Chờ duyệt</span>
                        @elseif($product->status === 'sold')
                            <span class="inline-block px-2.5 py-1 bg-gray-100 border border-gray-200 text-gray-600 rounded-md text-[10px] font-bold mb-1">Đã bán</span>
                        @else
                            <span class="inline-block px-2.5 py-1 bg-red-50 border border-red-100 text-red-600 rounded-md text-[10px] font-bold mb-1">Từ chối</span>
                        @endif
                        <p class="text-[10px] text-gray-400 font-bold mt-1"><i class="fa-solid fa-eye mr-1"></i>{{ number_format($product->view_count) }} lượt</p>
                    </div>
                </a>
                @empty
                <div class="h-full flex flex-col items-center justify-center text-gray-400">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-inner">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium mb-4 text-gray-500">Bạn chưa đăng bán sản phẩm nào</p>
                    <a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-bold border border-emerald-100 hover:bg-emerald-100 transition-colors shadow-sm">Bắt đầu đăng tin</a>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. SẢN PHẨM YÊU THÍCH --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-[2rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
            <h3 class="font-black text-gray-900 text-lg flex items-center gap-2"><i class="fa-solid fa-heart text-red-500"></i> Đang theo dõi (Yêu thích)</h3>
            <a href="{{ route('favorites.index') }}" class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 px-3 py-1.5 rounded-lg transition-colors">Xem tất cả</a>
        </div>
        
        <div class="p-6">
            @if($favoriteProducts->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5 gap-5">
                    @foreach($favoriteProducts as $fav)
                    <a href="{{ route('products.show', $fav->slug) }}" class="block bg-white rounded-2xl border border-gray-100 hover:border-red-200 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
                        
                        <div class="absolute top-2.5 right-2.5 z-10 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm text-red-500">
                            <i class="fa-solid fa-heart"></i>
                        </div>

                        <div class="w-full aspect-square bg-gray-50 flex items-center justify-center overflow-hidden border-b border-gray-50 relative">
                            @if($fav->images && $fav->images->count() > 0)
                                <img src="{{ asset('storage/' . $fav->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <i class="fa-regular fa-image text-4xl text-gray-300"></i>
                            @endif
                            <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>

                        <div class="p-4">
                            <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 leading-snug group-hover:text-red-600 transition-colors h-10">{{ $fav->title }}</h4>
                            <p class="text-base font-black text-red-600 mt-2">{{ number_format($fav->price) }} <span class="text-xs underline">đ</span></p>
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="text-center text-gray-400 py-12 flex flex-col items-center">
                    <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-inner">
                        <i class="fa-regular fa-heart text-4xl text-red-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-500 mb-4">Bạn chưa lưu bất kỳ sản phẩm nào</p>
                    <a href="{{ url('/') }}" class="bg-red-50 text-red-600 border border-red-100 font-bold px-6 py-2.5 rounded-xl hover:bg-red-100 transition-colors shadow-sm">Khám phá chợ tốt</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection