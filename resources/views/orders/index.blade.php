@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - 2HAND')

{{-- THANH BREADCRUMB ĐIỀU HƯỚNG --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight">
        Quản lý đơn hàng
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition-colors">Quản lý cá nhân</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <span class="text-gray-900 font-bold">Đơn hàng</span>
    </div>
</div>
@endsection

@section('content')
{{-- LƯU TAB VÀO URL ĐỂ KHÔNG BỊ NHẢY KHI F5 --}}
<div x-data="{ tab: new URLSearchParams(location.search).get('tab') || 'buying' }" class="pb-10">

    {{-- HIỂN THỊ THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-check"></i></div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-xmark"></i></div>
            {{ session('error') }}
        </div>
    @endif

    {{-- THANH CHUYỂN TAB HIỆN ĐẠI --}}
    <div class="flex p-1.5 space-x-2 bg-gray-100/80 rounded-2xl w-fit mb-8 border border-gray-200/60 shadow-inner">
        <button @click="tab = 'buying'; window.history.replaceState(null, null, '?tab=buying')" 
                :class="tab === 'buying' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200/50 border-transparent'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 border">
            <i class="fa-solid fa-bag-shopping text-lg"></i> Đơn mua ({{ count($buyingOrders) }})
        </button>
        <button @click="tab = 'selling'; window.history.replaceState(null, null, '?tab=selling')" 
                :class="tab === 'selling' ? 'bg-white text-emerald-600 shadow-sm border-gray-100' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-200/50 border-transparent'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 border">
            <i class="fa-solid fa-store text-lg"></i> Đơn bán ({{ count($sellingOrders) }})
        </button>
    </div>

    {{-- ============================================== --}}
    {{-- TAB 1: ĐƠN HÀNG TÔI MUA --}}
    {{-- ============================================== --}}
    <div x-show="tab === 'buying'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        
        @forelse($buyingOrders as $order)
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden hover:shadow-[0_4px_25px_rgba(16,185,129,0.1)] hover:border-emerald-200 transition-all duration-300">
                
                {{-- Card Header: Thông tin Shop, Trạng thái & THỜI GIAN --}}
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-emerald-100 to-green-50 text-emerald-700 flex items-center justify-center font-black text-xs border border-white shadow-sm">
                            {{ substr($order->seller->name ?? 'S', 0, 1) }}
                        </div>
                        <span class="font-bold text-gray-800 text-sm">{{ $order->seller->name ?? 'Người bán ẩn danh' }}</span>
                        
                        @if($order->seller_id)
                        <a href="{{ route('shop.show', $order->seller_id) }}" class="text-[10px] font-semibold bg-white border border-gray-200 text-gray-600 px-2.5 py-1 rounded-lg hover:text-emerald-600 hover:border-emerald-300 transition flex items-center gap-1 shadow-sm">
                            <i class="fa-solid fa-store"></i> Xem shop
                        </a>
                        @endif
                    </div>
                    
                    <div class="flex flex-col items-end gap-1.5">
                        {{-- FIX LỖI TAILWIND CLASS ĐỘNG --}}
                        @php
                            $badgeClass = match($order->status) {
                                'pending_shipping', 'paid_escrow' => 'bg-orange-50 text-orange-600 border-orange-100',
                                'shipped'   => 'bg-blue-50 text-blue-600 border-blue-100',
                                'completed' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                'rejected'  => 'bg-red-50 text-red-600 border-red-100',
                                default     => 'bg-gray-50 text-gray-600 border-gray-100',
                            };
                        @endphp
                        <span class="{{ $badgeClass }} text-xs font-bold px-3 py-1.5 rounded-lg border flex items-center gap-1.5">
                            @if($order->status === 'pending_shipping' || $order->status === 'paid_escrow')
                                <i class="fa-solid fa-box-open"></i> Chờ đóng gói
                            @elseif($order->status === 'shipped')
                                <i class="fa-solid fa-truck-fast"></i> Đang giao hàng
                            @elseif($order->status === 'completed')
                                <i class="fa-solid fa-check-double"></i> Đã hoàn tất
                            @elseif($order->status === 'rejected')
                                <i class="fa-solid fa-xmark"></i> Đã từ chối nhận
                            @else
                                <i class="fa-solid fa-circle-info"></i> Không xác định
                            @endif
                        </span>
                        
                        {{-- Text Thời gian --}}
                        <div class="text-[11px] text-gray-500 font-medium flex items-center gap-2">
                            <span>Đặt hàng: {{ $order->created_at->format('H:i - d/m/Y') }}</span>
                            @if($order->status === 'shipped')
                                <span class="text-gray-300">•</span>
                                <span class="text-blue-500"><i class="fa-solid fa-truck-fast"></i> Đã gửi: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @elseif($order->status === 'completed')
                                <span class="text-gray-300">•</span>
                                <span class="text-emerald-500"><i class="fa-solid fa-box-open"></i> Nhận hàng: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @elseif($order->status === 'rejected')
                                <span class="text-gray-300">•</span>
                                <span class="text-red-500"><i class="fa-solid fa-rotate-left"></i> Từ chối: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Body: Thông tin Sản phẩm --}}
                <div class="p-6 flex flex-col md:flex-row gap-6">
                    <a href="{{ $order->product ? route('products.show', $order->product->slug) : '#' }}" class="w-full md:w-28 h-28 bg-gray-50 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-sm block group">
                        @if($order->product && $order->product->images && $order->product->images->count() > 0)
                            <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                    </a>
                    
                    <div class="flex-1 flex flex-col justify-center">
                        <a href="{{ $order->product ? route('products.show', $order->product->slug) : '#' }}" class="font-bold text-gray-900 text-lg hover:text-emerald-600 transition-colors line-clamp-2">
                            {{ $order->product ? $order->product->title : 'Sản phẩm đã bị xóa hoặc không tồn tại' }}
                        </a>
                        <div class="mt-3 flex items-center gap-3">
                            @if($order->payment_method === 'cod')
                                <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase border border-gray-200 flex items-center gap-1"><i class="fa-solid fa-hand-holding-dollar"></i> Thanh toán COD</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase border border-emerald-200 flex items-center gap-1"><i class="fa-solid fa-shield-halved"></i> Thanh toán an toàn</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Card Footer: Tổng tiền & Nút Hành động --}}
                <div class="px-6 py-5 bg-gray-50/30 border-t border-gray-50 flex flex-col md:flex-row justify-end items-center gap-6">
                    <div class="flex items-center gap-3 w-full md:w-auto justify-end">
                        <span class="text-sm text-gray-500 font-medium">Thành tiền:</span>
                        <span class="font-black text-red-500 text-2xl">{{ number_format($order->total_amount) }}<span class="text-lg underline underline-offset-4 ml-0.5">đ</span></span>
                    </div>

                    @if($order->status === 'shipped')
                    {{-- BỘ NÚT KHI ĐANG GIAO HÀNG --}}
                    <div class="w-full md:w-auto flex flex-col md:flex-row gap-3" x-data="{ openRejectModal: false, reasonType: '' }">
                        
                        {{-- Nút Từ chối nhận hàng --}}
                        <button @click="openRejectModal = true" type="button" class="w-full md:w-auto bg-white border-2 border-red-500 text-red-600 hover:bg-red-50 font-bold px-6 py-2.5 rounded-xl text-sm transition-all flex items-center justify-center gap-2 shadow-sm">
                            <i class="fa-solid fa-xmark"></i> TỪ CHỐI NHẬN
                        </button>

                        {{-- Nút Đã nhận hàng --}}
                        <form action="{{ route('orders.confirm', $order->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="submit" onclick="return confirm('Bạn xác nhận đã nhận đúng sản phẩm và hài lòng chứ?')" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold px-8 py-2.5 rounded-xl text-sm transition-all shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                <i class="fa-solid fa-box-open"></i> ĐÃ NHẬN ĐƯỢC HÀNG
                            </button>
                        </form>

                        {{-- MODAL CHỌN LÝ DO TỪ CHỐI --}}
                        <div x-show="openRejectModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" x-transition.opacity>
                            <div @click.away="openRejectModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all" x-show="openRejectModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                                
                                <div class="bg-red-50 px-6 py-4 border-b border-red-100 flex justify-between items-center">
                                    <h3 class="text-lg font-black text-red-700 flex items-center gap-2"><i class="fa-solid fa-triangle-exclamation"></i> Lý do từ chối nhận hàng</h3>
                                    <button @click="openRejectModal = false" type="button" class="text-red-400 hover:text-red-600 text-xl"><i class="fa-solid fa-xmark"></i></button>
                                </div>

                                <form action="{{ route('orders.reject', $order->id) }}" method="POST" class="p-6 space-y-4">
                                    @csrf
                                    
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="reject_reason" value="Sản phẩm bị hư hỏng, bể vỡ do vận chuyển" x-model="reasonType" required class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        <span class="font-medium text-gray-800">Sản phẩm bị hư hỏng, bể vỡ do vận chuyển</span>
                                    </label>
                                    
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="reject_reason" value="Hàng không đúng với mô tả / hình ảnh" x-model="reasonType" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        <span class="font-medium text-gray-800">Hàng không đúng với mô tả / hình ảnh</span>
                                    </label>
                                    
                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="reject_reason" value="Giao sai sản phẩm" x-model="reasonType" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        <span class="font-medium text-gray-800">Giao sai sản phẩm</span>
                                    </label>

                                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" value="other" x-model="reasonType" class="w-4 h-4 text-red-600 focus:ring-red-500">
                                        <span class="font-medium text-gray-800">Lý do khác...</span>
                                    </label>

                                    {{-- Ô nhập lý do khác --}}
                                    <div x-show="reasonType === 'other'" x-collapse>
                                        <textarea name="reject_reason" :disabled="reasonType !== 'other'" rows="3" placeholder="Vui lòng nhập rõ lý do bạn từ chối..." class="w-full px-4 py-3 bg-white border-2 border-gray-200 rounded-xl focus:outline-none focus:border-red-500 focus:ring-4 focus:ring-red-500/10 transition-all font-medium text-gray-900"></textarea>
                                    </div>

                                    <div class="pt-4 flex gap-3">
                                        <button type="button" @click="openRejectModal = false" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Quay lại</button>
                                        <button type="submit" class="flex-1 px-4 py-2.5 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition">Xác nhận Từ chối</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    @elseif($order->status === 'completed')
                    {{-- NÚT KHI ĐÃ HOÀN TẤT (POPUP ĐÁNH GIÁ 5 SAO) --}}
                    @php
                        $hasReviewed = \App\Models\Review::where('order_id', $order->id)->exists();
                    @endphp
                    
                    <div class="w-full md:w-auto flex flex-wrap gap-3 justify-end" x-data="{ openReviewModal: false, rating: 5, hoverRating: 0 }">
                        <a href="{{ $order->product ? route('products.show', $order->product->slug) : '#' }}" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-eye"></i> Xem lại tin
                        </a>
                        
                        @if($hasReviewed)
                            <button disabled class="px-6 py-2.5 bg-gray-100 border border-gray-200 text-gray-400 font-bold rounded-xl text-sm cursor-not-allowed flex items-center gap-2">
                                <i class="fa-solid fa-check"></i> Đã đánh giá
                            </button>
                        @else
                            <button @click="openReviewModal = true" type="button" class="px-6 py-2.5 bg-white border border-yellow-400 text-yellow-600 font-bold rounded-xl text-sm hover:bg-yellow-50 transition-colors shadow-sm flex items-center gap-2">
                                <i class="fa-solid fa-star"></i> Đánh giá
                            </button>

                            {{-- MODAL ĐÁNH GIÁ --}}
                            <div x-show="openReviewModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4 text-left" x-transition.opacity>
                                <div @click.away="openReviewModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all" x-show="openReviewModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100">
                                    
                                    <div class="bg-yellow-50 px-6 py-4 border-b border-yellow-100 flex justify-between items-center">
                                        <h3 class="text-lg font-black text-yellow-700 flex items-center gap-2"><i class="fa-solid fa-ranking-star"></i> Đánh giá người bán</h3>
                                        <button @click="openReviewModal = false" type="button" class="text-yellow-500 hover:text-yellow-700 text-xl"><i class="fa-solid fa-xmark"></i></button>
                                    </div>

                                    <form action="{{ route('orders.review', $order->id) }}" method="POST" class="p-6 space-y-5">
                                        @csrf
                                        
                                        {{-- KHU VỰC 5 NGÔI SAO --}}
                                        <div class="text-center">
                                            <p class="text-sm font-bold text-gray-700 mb-3">Mức độ hài lòng của bạn</p>
                                            <div class="flex justify-center gap-2 text-4xl mb-2">
                                                <i class="fa-star cursor-pointer transition-all duration-200 hover:scale-110"
                                                   :class="(hoverRating >= 1 || (!hoverRating && rating >= 1)) ? 'fa-solid text-yellow-400 drop-shadow' : 'fa-regular text-gray-300'"
                                                   @mouseenter="hoverRating = 1" @mouseleave="hoverRating = 0" @click="rating = 1"></i>
                                                
                                                <i class="fa-star cursor-pointer transition-all duration-200 hover:scale-110"
                                                   :class="(hoverRating >= 2 || (!hoverRating && rating >= 2)) ? 'fa-solid text-yellow-400 drop-shadow' : 'fa-regular text-gray-300'"
                                                   @mouseenter="hoverRating = 2" @mouseleave="hoverRating = 0" @click="rating = 2"></i>
                                                
                                                <i class="fa-star cursor-pointer transition-all duration-200 hover:scale-110"
                                                   :class="(hoverRating >= 3 || (!hoverRating && rating >= 3)) ? 'fa-solid text-yellow-400 drop-shadow' : 'fa-regular text-gray-300'"
                                                   @mouseenter="hoverRating = 3" @mouseleave="hoverRating = 0" @click="rating = 3"></i>
                                                
                                                <i class="fa-star cursor-pointer transition-all duration-200 hover:scale-110"
                                                   :class="(hoverRating >= 4 || (!hoverRating && rating >= 4)) ? 'fa-solid text-yellow-400 drop-shadow' : 'fa-regular text-gray-300'"
                                                   @mouseenter="hoverRating = 4" @mouseleave="hoverRating = 0" @click="rating = 4"></i>
                                                
                                                <i class="fa-star cursor-pointer transition-all duration-200 hover:scale-110"
                                                   :class="(hoverRating >= 5 || (!hoverRating && rating >= 5)) ? 'fa-solid text-yellow-400 drop-shadow' : 'fa-regular text-gray-300'"
                                                   @mouseenter="hoverRating = 5" @mouseleave="hoverRating = 0" @click="rating = 5"></i>
                                            </div>
                                            <input type="hidden" name="rating" :value="rating">
                                        </div>

                                        {{-- Ô nhập nhận xét --}}
                                        <div>
                                            <label class="block text-sm font-bold text-gray-700 mb-2">Nhận xét chi tiết (Không bắt buộc)</label>
                                            <textarea name="comment" rows="3" placeholder="Chia sẻ trải nghiệm của bạn về chất lượng sản phẩm, thái độ của người bán..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-yellow-400 focus:ring-4 focus:ring-yellow-50 transition-all font-medium text-gray-900 text-sm resize-none"></textarea>
                                        </div>

                                        <div class="pt-2 flex gap-3">
                                            <button type="button" @click="openReviewModal = false" class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Hủy</button>
                                            <button type="submit" class="flex-1 px-4 py-2.5 bg-yellow-400 text-yellow-900 font-black rounded-xl hover:bg-yellow-500 shadow-md shadow-yellow-200 transition">Gửi Đánh Giá</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>

                    @elseif($order->status === 'rejected')
                    {{-- NÚT KHI TỪ CHỐI --}}
                    <div class="w-full md:w-auto flex gap-3 justify-end">
                        <span class="text-sm font-bold text-red-600 flex items-center gap-2"><i class="fa-solid fa-circle-info"></i> Đơn hàng đã bị hủy</span>
                    </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center">
                <div class="w-28 h-28 bg-gray-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-inner">
                    <i class="fa-solid fa-bag-shopping text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Chưa có đơn mua</h3>
                <p class="text-gray-500 font-medium mb-6">Bạn chưa thực hiện giao dịch mua hàng nào trên 2HAND.</p>
                <a href="{{ url('/') }}" class="bg-emerald-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-200">
                    Bắt đầu mua sắm ngay
                </a>
            </div>
        @endforelse
    </div>

    {{-- ============================================== --}}
    {{-- TAB 2: ĐƠN HÀNG TÔI BÁN --}}
    {{-- ============================================== --}}
    <div x-show="tab === 'selling'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-6">
        
        @forelse($sellingOrders as $order)
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 overflow-hidden hover:shadow-[0_4px_25px_rgba(16,185,129,0.1)] hover:border-emerald-200 transition-all duration-300 flex flex-col">
                
                {{-- Header đơn bán & THỜI GIAN --}}
                <div class="px-6 py-4 border-b border-gray-50 bg-gray-50/50 flex flex-wrap gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-black text-xs border border-white shadow-sm">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <span class="text-gray-500 text-sm">Người mua:</span>
                        <span class="font-bold text-gray-900 text-sm">{{ $order->receiver_name }}</span>
                    </div>
                    
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-gray-500 text-xs">Mã đơn: <span class="font-bold text-gray-800">#2H{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span></span>
                        
                        {{-- Text Thời gian cho người bán --}}
                        <div class="text-[11px] text-gray-500 font-medium flex items-center gap-2 mt-0.5">
                            <span>Khách đặt: {{ $order->created_at->format('H:i - d/m/Y') }}</span>
                            
                            @if($order->status === 'shipped')
                                <span class="text-gray-300">•</span>
                                <span class="text-blue-500">Bạn gửi: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @elseif($order->status === 'completed')
                                <span class="text-gray-300">•</span>
                                <span class="text-emerald-500"><i class="fa-solid fa-check-double"></i> Hoàn tất: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @elseif($order->status === 'rejected')
                                <span class="text-gray-300">•</span>
                                <span class="text-red-500"><i class="fa-solid fa-xmark"></i> Bị từ chối: {{ $order->updated_at->format('H:i - d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Body đơn bán (Chia cột: Sản phẩm & Giao hàng) --}}
                <div class="p-6 flex flex-col xl:flex-row gap-8">
                    
                    {{-- Thông tin sản phẩm --}}
                    <div class="flex gap-5 items-start w-full xl:w-1/2">
                        <div class="w-24 h-24 bg-gray-50 rounded-2xl overflow-hidden shrink-0 border border-gray-100 shadow-sm">
                            @if($order->product && $order->product->images && $order->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
                            @endif
                        </div>
                        <div class="flex flex-col h-full justify-center">
                            <h4 class="font-bold text-gray-900 text-base line-clamp-2 leading-tight hover:text-emerald-600 transition-colors cursor-pointer">
                                {{ $order->product ? $order->product->title : 'Sản phẩm không tồn tại' }}
                            </h4>
                            <div class="mt-3 bg-emerald-50/50 border border-emerald-100 px-3 py-2 rounded-xl inline-block w-fit">
                                <p class="text-[11px] text-gray-500 font-medium">Thực nhận sau phí sàn:</p>
                                <p class="text-lg font-black text-emerald-600 leading-none mt-1">{{ number_format($order->seller_amount) }}đ</p>
                            </div>
                        </div>
                    </div>

                    {{-- Thông tin Vận chuyển (Hộp xám nổi bật) --}}
                    <div class="w-full xl:w-1/2 bg-gray-50/80 p-5 rounded-2xl border border-gray-100 shadow-inner relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-blue-400"></div>
                        <p class="font-bold text-gray-800 text-sm mb-3 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-red-500"></i> Địa chỉ giao hàng
                        </p>
                        <div class="space-y-1.5">
                            <p class="text-sm"><span class="text-gray-500">Người nhận:</span> <span class="font-bold text-gray-900">{{ $order->receiver_name }}</span></p>
                            <p class="text-sm"><span class="text-gray-500">Điện thoại:</span> <span class="font-bold text-gray-900">{{ $order->phone_number }}</span></p>
                            <p class="text-sm leading-relaxed"><span class="text-gray-500">Địa chỉ:</span> <span class="font-medium text-gray-800">{{ $order->shipping_address }}</span></p>
                        </div>
                    </div>
                </div>

                {{-- Footer đơn bán: Action --}}
                <div class="px-6 py-5 bg-gray-50/30 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-3">
                        @if($order->payment_method === 'cod')
                            <div class="bg-gray-100 text-gray-600 px-3 py-1.5 rounded-lg flex items-center gap-2 text-xs font-bold border border-gray-200">
                                <i class="fa-solid fa-hand-holding-dollar text-lg"></i> Thu tiền mặt khi giao (COD)
                            </div>
                        @else
                            <div class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg flex items-center gap-2 text-xs font-bold border border-blue-100">
                                <i class="fa-solid fa-shield-halved text-lg"></i> Người mua đã thanh toán an toàn
                            </div>
                        @endif
                    </div>

                    <div class="w-full md:w-auto flex justify-end">
                        @if($order->status === 'pending_shipping' || $order->status === 'paid_escrow')
                            <form action="{{ route('orders.ship', $order->id) }}" method="POST" class="w-full md:w-auto">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold px-8 py-3 rounded-xl text-sm transition-all shadow-md shadow-blue-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-truck-fast"></i> XÁC NHẬN ĐÃ GỬI HÀNG
                                </button>
                            </form>
                        @elseif($order->status === 'shipped')
                            <span class="bg-yellow-50 text-yellow-700 font-bold px-6 py-3 rounded-xl border border-yellow-200 flex items-center gap-2 text-sm shadow-sm">
                                <i class="fa-solid fa-box-open"></i> Đang chờ khách xác nhận nhận hàng
                            </span>
                        @elseif($order->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-700 font-bold px-6 py-3 rounded-xl border border-emerald-200 flex items-center gap-2 text-sm shadow-sm">
                                <i class="fa-solid fa-check-double"></i> Đơn hàng hoàn tất
                            </span>
                        @elseif($order->status === 'rejected')
                            <span class="bg-red-50 text-red-700 font-bold px-6 py-3 rounded-xl border border-red-200 flex items-center gap-2 text-sm shadow-sm">
                                <i class="fa-solid fa-rotate-left"></i> Khách đã từ chối nhận (Hoàn hàng)
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center">
                <div class="w-28 h-28 bg-gray-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-inner">
                    <i class="fa-solid fa-store-slash text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-black text-gray-800 mb-2">Chưa có đơn bán nào</h3>
                <p class="text-gray-500 font-medium mb-6">Bạn chưa phát sinh giao dịch bán hàng nào trên hệ thống.</p>
                <a href="{{ route('products.create') }}" class="bg-emerald-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-emerald-700 transition-colors shadow-md shadow-emerald-200">
                    Đăng tin bán ngay
                </a>
            </div>
        @endforelse
    </div>

</div>
@endsection