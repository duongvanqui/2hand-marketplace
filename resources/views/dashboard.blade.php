@extends('layouts.admin')

@section('title', 'Bảng điều khiển cá nhân - 2HAND')

@section('header_title', 'Bảng điều khiển cá nhân')

@section('content')
{{-- ========================================== --}}
{{-- XỬ LÝ DỮ LIỆU TỰ ĐỘNG TRỰC TIẾP TRÊN VIEW --}}
{{-- ========================================== --}}
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

{{-- ========================================== --}}
{{-- 1. KHỐI THỐNG KÊ TỔNG QUAN (5 CARDS GỐC) --}}
{{-- ========================================== --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mb-8">
    
    {{-- Card 1: Đang bán --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800">{{ $totalApproved }}</h3>
        <p class="text-xs text-gray-500 font-medium mt-1">Sản phẩm đang bán</p>
    </div>

    {{-- Card 2: Chờ duyệt --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="w-10 h-10 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
            <i class="fa-regular fa-clock"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800">{{ $pendingCount }}</h3>
        <p class="text-xs text-gray-500 font-medium mt-1">Sản phẩm chờ duyệt</p>
    </div>

    {{-- Card 3: Đã bán --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-check"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800">{{ $soldCount }}</h3>
        <p class="text-xs text-gray-500 font-medium mt-1">Sản phẩm đã bán</p>
    </div>

    {{-- Card 4: Đơn hàng mua --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="w-10 h-10 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-cart-arrow-down"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800">{{ $orderCount }}</h3>
        <p class="text-xs text-gray-500 font-medium mt-1">Đơn hàng mua</p>
    </div>

    {{-- Card 5: Tin nhắn mới --}}
    <div x-data="{ unreadCount: parseInt('{{ $unreadMsgDashboard }}') || 0 }"
         @update-unread-count.window="unreadCount = $event.detail === 'increment' ? unreadCount + 1 : parseInt($event.detail)"
         onclick="window.location.href='{{ route('chat.index') }}'"
         class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group cursor-pointer relative overflow-hidden">

        <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform relative z-10">
            <i class="fa-regular fa-comment-dots"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800 relative z-10" x-text="unreadCount">0</h3>
        <p class="text-xs text-gray-500 font-medium mt-1 relative z-10">Tin nhắn mới</p>
        <div class="absolute inset-0 bg-red-50/20 transform scale-y-0 origin-bottom group-hover:scale-y-100 transition-transform duration-300"></div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. KHU VỰC CHIA 2 CỘT --}}
{{-- ========================================== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- CỘT TRÁI: HOẠT ĐỘNG GẦN ĐÂY --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Hoạt động gần đây</h3>
            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
        </div>
        <div class="p-5 flex-1">
            @if($recentActivities->count() > 0)
            
            <div class="relative before:absolute before:inset-y-0 before:left-5 before:w-[2px] before:bg-gray-100 space-y-6">
                
                @foreach($recentActivities as $activity)
                    @php
                        $type = $activity->data['type'] ?? 'info';
                        $icon = $activity->data['icon'] ?? 'fa-bell';
                        $message = $activity->data['message'] ?? 'Bạn có một thông báo mới.';
                        $url = $activity->data['url'] ?? '#';
                        
                        $colorClass = match($type) {
                            'success'  => 'bg-green-100 text-green-600',
                            'danger'   => 'bg-red-100 text-red-600',
                            'warning'  => 'bg-yellow-100 text-yellow-600',
                            'favorite' => 'bg-purple-100 text-purple-600', 
                            default    => 'bg-blue-100 text-blue-600',
                        };
                    @endphp

                    <div class="relative flex items-start gap-4 group">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white {{ $colorClass }} shrink-0 relative z-10 shadow-[0_2px_10px_rgba(0,0,0,0.05)]">
                            <i class="fa-solid {{ $icon }} text-xs"></i>
                        </div>
                        
                        <div class="flex-1 pt-1">
                            <a href="{{ $url }}" class="block p-2 -mt-2 -ml-2 rounded-xl hover:bg-gray-50 transition-colors">
                                <p class="text-sm font-medium text-gray-800 leading-snug">{!! $message !!}</p>
                                <p class="text-[11px] text-gray-400 mt-1.5">{{ $activity->created_at->diffForHumans() }}</p>
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>
            @else
            <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                <i class="fa-regular fa-bell-slash text-4xl mb-3"></i>
                <p class="text-sm font-medium">Chưa có hoạt động nào gần đây</p>
            </div>
            @endif
        </div>
    </div>

    {{-- CỘT PHẢI: SẢN PHẨM CỦA TÔI --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Sản phẩm của tôi</h3>
            {{-- ĐÃ SỬA LINK Ở ĐÂY NHƯ BẠN YÊU CẦU --}}
            <a href="{{ route('my.products') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
        </div>
        <div class="p-2 flex-1">
            @forelse($myProducts as $product)
            <a href="{{ route('products.show', $product->slug) }}" class="flex items-center gap-4 p-3 hover:bg-gray-50 rounded-xl transition group">
                <div class="w-16 h-16 rounded-xl bg-gray-100 overflow-hidden shrink-0 border border-gray-200/50">
                    @if($product->images && $product->images->count() > 0)
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-solid fa-image"></i></div>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-emerald-600 transition-colors">{{ $product->title }}</h4>
                    <p class="text-sm font-black text-gray-600 mt-0.5">{{ number_format($product->price) }} đ</p>
                </div>
                <div class="text-right shrink-0">
                    @if($product->status === 'approved')
                    <span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-bold mb-1">Đang bán</span>
                    @elseif($product->status === 'pending')
                    <span class="inline-block px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-[10px] font-bold mb-1">Chờ duyệt</span>
                    @elseif($product->status === 'sold')
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold mb-1">Đã ẩn/Bán</span>
                    @else
                    <span class="inline-block px-2.5 py-1 bg-red-100 text-red-600 rounded-lg text-[10px] font-bold mb-1">Từ chối</span>
                    @endif
                    <p class="text-[11px] text-gray-400 font-medium">{{ number_format($product->view_count) }} lượt xem</p>
                </div>
            </a>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                <p class="text-sm font-medium mb-3">Bạn chưa có sản phẩm nào</p>
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-bold hover:bg-emerald-200 transition">Đăng tin ngay</a>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 3. SẢN PHẨM YÊU THÍCH --}}
{{-- ========================================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-50 flex items-center justify-between">
        <h3 class="font-bold text-gray-800">Sản phẩm yêu thích</h3>
        <a href="{{ route('favorites.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
    </div>
    
    <div class="p-5">
        @if($favoriteProducts->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach($favoriteProducts as $fav)
                <a href="{{ route('products.show', $fav->slug) }}" class="block bg-white rounded-xl border border-gray-100 hover:border-red-200 hover:shadow-md transition-all duration-300 group relative overflow-hidden">
                    
                    {{-- Nút Tim --}}
                    <div class="absolute top-2 right-2 z-10 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm text-red-500">
                        <i class="fa-solid fa-heart"></i>
                    </div>

                    {{-- Ảnh --}}
                    <div class="w-full aspect-[4/3] bg-gray-50 flex items-center justify-center overflow-hidden border-b border-gray-50">
                        @if($fav->images && $fav->images->count() > 0)
                            <img src="{{ asset('storage/' . $fav->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <i class="fa-regular fa-image text-3xl text-gray-300"></i>
                        @endif
                    </div>

                    {{-- Text --}}
                    <div class="p-4">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-red-500 transition-colors">{{ $fav->title }}</h4>
                        <p class="text-sm font-black text-gray-900 mt-1">{{ number_format($fav->price) }} đ</p>
                    </div>
                </a>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-400 py-10 flex flex-col items-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                    <i class="fa-regular fa-heart text-2xl"></i>
                </div>
                <p class="text-sm font-medium">Bạn chưa lưu sản phẩm nào</p>
                <a href="{{ url('/') }}" class="mt-3 text-emerald-600 font-bold hover:underline">Khám phá ngay</a>
            </div>
        @endif
    </div>
</div>
@endsection