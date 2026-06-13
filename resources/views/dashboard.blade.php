@extends('layouts.admin')

@section('title', 'Bảng điều khiển cá nhân - 2HAND')

@section('header_title', 'Bảng điều khiển cá nhân')

@section('header_actions')
<a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl text-sm hover:from-emerald-600 hover:to-green-700 transition-all shadow-lg shadow-emerald-200 flex items-center gap-2 transform hover:-translate-y-0.5">
    <i class="fa-solid fa-plus"></i> Đăng tin mới
</a>
@endsection

@section('content')
{{-- Tự động tính toán các con số thống kê an toàn --}}
@php
    $userId = Auth::id();
    $pendingCount = \App\Models\Product::where('user_id', $userId)->where('status', 'pending')->count();
    $soldCount = \App\Models\Product::where('user_id', $userId)->where('status', 'sold')->count();

    // Tránh lỗi nếu bảng Order/Message chưa hoàn thiện
    $orderCount = 0;
    
    // Đếm số tin nhắn chưa đọc
    $unreadMsgDashboard = \App\Models\Message::whereHas('conversation', function($q) {
        $q->where('buyer_id', Auth::id())->orWhere('seller_id', Auth::id());
    })->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();
@endphp

{{-- ========================================== --}}
{{-- 1. KHỐI THỐNG KÊ (5 CARDS) --}}
{{-- ========================================== --}}
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5 mb-8">
    
    {{-- Card 1: Đang bán --}}
    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform">
            <i class="fa-solid fa-bag-shopping"></i>
        </div>
        <h3 class="text-2xl font-black text-gray-800">{{ $totalApproved ?? 0 }}</h3>
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
        <h3 class="text-2xl font-black text-gray-800">{{ $orderCount = \App\Models\Order::where('buyer_id', Auth::id())->count(); }}</h3>
        <p class="text-xs text-gray-500 font-medium mt-1">Đơn hàng mua</p>
    </div>

    {{-- Card 5: Tin nhắn (Đã tích hợp Alpine.js Real-time) --}}
    <div x-data="{ unreadCount: parseInt('{{ $unreadMsgDashboard }}') || 0 }"
         @update-unread-count.window="unreadCount = $event.detail === 'increment' ? unreadCount + 1 : parseInt($event.detail)"
         onclick="window.location.href='{{ route('chat.index') }}'"
         class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group cursor-pointer relative overflow-hidden">

        <div class="w-10 h-10 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-lg mb-3 group-hover:scale-110 transition-transform relative z-10">
            <i class="fa-regular fa-comment-dots"></i>
        </div>

        {{-- Chỗ này số sẽ tự nhảy bằng Alpine.js --}}
        <h3 class="text-2xl font-black text-gray-800 relative z-10" x-text="unreadCount">0</h3>
        <p class="text-xs text-gray-500 font-medium mt-1 relative z-10">Tin nhắn mới</p>

        {{-- Hiệu ứng nền nhẹ khi hover --}}
        <div class="absolute inset-0 bg-red-50/20 transform scale-y-0 origin-bottom group-hover:scale-y-100 transition-transform duration-300"></div>
    </div>

</div> {{-- ĐÂY LÀ THẺ DIV QUAN TRỌNG ĐÃ CỨU VỚT GIAO DIỆN CỦA BẠN KHỎI BỊ MÓP --}}


{{-- ========================================== --}}
{{-- 2. KHU VỰC CHIA 2 CỘT (HOẠT ĐỘNG & SẢN PHẨM) --}}
{{-- ========================================== --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

    {{-- Cột trái: Hoạt động gần đây (UI Mockup) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Hoạt động gần đây</h3>
            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
        </div>
        <div class="p-5 flex-1">
            <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-100 before:to-transparent">

                {{-- Item 1 --}}
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-green-100 text-green-600 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10">
                        <i class="fa-solid fa-check text-xs"></i>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-3 rounded-xl hover:bg-gray-50 transition">
                        <p class="text-sm font-medium text-gray-800">Sản phẩm <span class="font-bold text-green-600">"iPhone 13 128GB"</span> của bạn đã được duyệt.</p>
                        <p class="text-[11px] text-gray-400 mt-1">10 phút trước</p>
                    </div>
                </div>

                {{-- Item 2 --}}
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-blue-100 text-blue-600 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10">
                        <i class="fa-regular fa-comment text-xs"></i>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-3 rounded-xl hover:bg-gray-50 transition">
                        <p class="text-sm font-medium text-gray-800">Bạn có tin nhắn mới từ <span class="font-bold">Nguyễn Minh Đức</span></p>
                        <p class="text-[11px] text-gray-400 mt-1">25 phút trước</p>
                    </div>
                </div>

                {{-- Item 3 --}}
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-yellow-100 text-yellow-600 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10">
                        <i class="fa-solid fa-truck-fast text-xs"></i>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-3 rounded-xl hover:bg-gray-50 transition">
                        <p class="text-sm font-medium text-gray-800">Đơn hàng <span class="font-bold">#DH1023</span> đã giao thành công.</p>
                        <p class="text-[11px] text-gray-400 mt-1">1 giờ trước</p>
                    </div>
                </div>

                {{-- Item 4 --}}
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-red-100 text-red-600 shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 shadow-sm z-10">
                        <i class="fa-regular fa-eye text-xs"></i>
                    </div>
                    <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2.5rem)] p-3 rounded-xl hover:bg-gray-50 transition">
                        <p class="text-sm font-medium text-gray-800">Sản phẩm <span class="font-bold">"Laptop Dell XPS 13"</span> có 15 lượt xem mới.</p>
                        <p class="text-[11px] text-gray-400 mt-1">3 giờ trước</p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Cột phải: Sản phẩm của tôi (Compact List) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col">
        <div class="p-5 border-b border-gray-50 flex items-center justify-between">
            <h3 class="font-bold text-gray-800">Sản phẩm của tôi</h3>
            <a href="{{ route('products.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
        </div>
        <div class="p-2 flex-1">
            @forelse($products->take(4) as $product)
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
                    <p class="text-sm font-black text-red-600 mt-0.5">{{ number_format($product->price) }} đ</p>
                </div>
                <div class="text-right shrink-0">
                    @if($product->status === 'approved')
                    <span class="inline-block px-2.5 py-1 bg-green-100 text-green-700 rounded-lg text-[10px] font-bold mb-1">Đang bán</span>
                    @elseif($product->status === 'pending')
                    <span class="inline-block px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-[10px] font-bold mb-1">Chờ duyệt</span>
                    @else
                    <span class="inline-block px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[10px] font-bold mb-1">Đã ẩn/Bán</span>
                    @endif
                    <p class="text-[11px] text-gray-400 font-medium">{{ number_format($product->view_count) }} lượt xem</p>
                </div>
            </a>
            @empty
            <div class="h-full flex flex-col items-center justify-center text-gray-400 py-10">
                <i class="fa-solid fa-box-open text-4xl mb-3"></i>
                <p class="text-sm">Chưa có sản phẩm nào</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 3. BẢNG SẢN PHẨM CHI TIẾT --}}
{{-- ========================================== --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-list-check text-emerald-500 mr-2"></i>Quản lý toàn bộ tin đăng</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                    <th class="p-5">Sản phẩm</th>
                    <th class="p-5">Giá bán</th>
                    <th class="p-5">Ngày đăng</th>
                    <th class="p-5">Trạng thái</th>
                    <th class="p-5 text-center">Hành động</th>
                </tr>
            </thead>
            <tbody class="text-sm divide-y divide-gray-50">
                @forelse($products as $product)
                <tr class="hover:bg-gray-50/80 transition-colors group">
                    <td class="p-5 font-semibold text-gray-800">
                        <a href="{{ route('products.show', $product->slug) }}" class="hover:text-emerald-500 transition-colors block max-w-xs truncate">
                            {{ $product->title }}
                        </a>
                    </td>
                    <td class="p-5 text-emerald-600 font-black">{{ number_format($product->price) }}đ</td>
                    <td class="p-5 text-gray-500 font-medium">{{ $product->created_at->format('d/m/Y') }}</td>
                    <td class="p-5">
                        @if($product->status === 'approved')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-md text-xs font-bold border border-green-200"><i class="fa-solid fa-check mr-1"></i> Hiển thị</span>
                        @elseif($product->status === 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-md text-xs font-bold border border-yellow-200"><i class="fa-regular fa-clock mr-1"></i> Chờ duyệt</span>
                        @else
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-md text-xs font-bold border border-red-200"><i class="fa-solid fa-xmark mr-1"></i> Từ chối</span>
                        @endif
                    </td>
                    <td class="p-5 flex items-center justify-center space-x-3 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="#" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all shadow-sm" title="Sửa">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                        <button class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-all shadow-sm" title="Xóa">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-16 text-center text-gray-400">
                        <p class="font-medium text-gray-500">Bạn chưa đăng tải tin thanh lý nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(isset($products) && $products->hasPages())
    <div class="p-5 border-t border-gray-100 bg-gray-50/50">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection