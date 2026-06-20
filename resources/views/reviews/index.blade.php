@extends('layouts.admin')

@section('title', 'Quản lý đánh giá - 2HAND')
@section('header_title', 'Đánh giá của tôi')

@section('content')
<div x-data="{ tab: 'received' }" class="pb-10">

    {{-- ========================================== --}}
    {{-- 1. KHỐI THỐNG KÊ ĐIỂM UY TÍN (MODERN UI)   --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-[2rem] p-8 mb-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
        {{-- Hiệu ứng nền --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-bl from-emerald-50 to-transparent rounded-full blur-3xl -z-10 translate-x-1/3 -translate-y-1/3"></div>

        <div class="text-center md:border-r-2 md:pr-12 border-gray-50 shrink-0">
            <p class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Điểm uy tín</p>
            <div class="flex items-center justify-center gap-3">
                <span class="text-6xl font-black text-gray-900 drop-shadow-sm">{{ number_format($averageRating, 1) }}</span>
                <div class="flex flex-col items-start gap-1 text-yellow-400">
                    <div class="flex text-lg">
                        @for($i=1; $i<=5; $i++)
                            <i class="{{ $i <= round($averageRating) ? 'fa-solid' : 'fa-regular' }} fa-star drop-shadow-sm"></i>
                        @endfor
                    </div>
                    <span class="text-[11px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md font-bold">{{ $totalReceived }} lượt đánh giá</span>
                </div>
            </div>
        </div>
        <div class="flex-1">
            <div class="bg-emerald-50/50 p-5 rounded-2xl border border-emerald-100/50">
                <p class="text-emerald-800/80 text-sm leading-relaxed font-medium">
                    <i class="fa-solid fa-quote-left text-emerald-300 text-lg mr-2 opacity-50"></i>
                    Đánh giá là yếu tố quan trọng nhất để người mua tin tưởng và lựa chọn sản phẩm của bạn. Hãy duy trì thái độ phục vụ tốt và chất lượng sản phẩm để nhận được nhiều 5 sao nhé!
                </p>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. THANH CHUYỂN TAB                        --}}
    {{-- ========================================== --}}
    <div class="flex p-1.5 space-x-2 bg-gray-100/80 rounded-2xl w-full md:w-fit mb-8 border border-gray-200/60 shadow-inner overflow-x-auto no-scrollbar">
        <button @click="tab = 'received'" 
                :class="tab === 'received' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-download"></i> Đánh giá nhận được
        </button>
        <button @click="tab = 'sent'" 
                :class="tab === 'sent' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'" 
                class="px-8 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
            <i class="fa-solid fa-paper-plane"></i> Đánh giá đã gửi
        </button>
    </div>

    {{-- ========================================== --}}
    {{-- 3. DANH SÁCH ĐÁNH GIÁ                      --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- TAB 1: ĐÁNH GIÁ NHẬN ĐƯỢC --}}
        <div x-show="tab === 'received'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            <div class="divide-y divide-gray-50">
                @forelse($receivedReviews as $review)
                    <div class="p-6 md:p-8 hover:bg-gray-50/50 transition-colors">
                        <div class="flex gap-4 md:gap-5">
                            {{-- Avatar --}}
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-lg shrink-0 border border-emerald-100 shadow-sm">
                                {{ substr($review->sender->name, 0, 1) }}
                            </div>
                            
                            {{-- Nội dung --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-900 text-base">{{ $review->sender->name }}</h4>
                                    <span class="text-xs text-gray-400 font-medium flex items-center gap-1"><i class="fa-regular fa-clock"></i> {{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                
                                {{-- Số sao --}}
                                <div class="flex text-yellow-400 text-xs mb-3 gap-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular text-gray-200' }} fa-star"></i>
                                    @endfor
                                </div>
                                
                                {{-- Lời nhắn --}}
                                @if($review->comment)
                                    <p class="text-gray-700 text-sm leading-relaxed mb-4">"{{ $review->comment }}"</p>
                                @else
                                    <p class="text-gray-400 text-sm italic mb-4">Người mua không để lại lời nhận xét.</p>
                                @endif
                                
                                {{-- BLOCK SẢN PHẨM HIỆN ĐẠI CÓ ẢNH --}}
                                @if($review->order && $review->order->product)
                                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 p-2.5 rounded-xl w-full max-w-md group hover:border-emerald-200 transition-colors">
                                        {{-- Ảnh thu nhỏ --}}
                                        <div class="w-10 h-10 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-100 flex items-center justify-center shadow-sm">
                                            @if($review->order->product->images && $review->order->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $review->order->product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <i class="fa-regular fa-image text-gray-300"></i>
                                            @endif
                                        </div>
                                        {{-- Info --}}
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Sản phẩm đã mua</p>
                                            <a href="{{ route('products.show', $review->order->product->slug) }}" class="text-xs font-bold text-gray-800 truncate block hover:text-emerald-600 transition-colors">
                                                {{ $review->order->product->title }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-100 px-3 py-2 rounded-lg">
                                        <i class="fa-solid fa-box-open text-gray-400"></i>
                                        <span class="text-xs text-gray-500 italic">Sản phẩm đã bị xóa hoặc ẩn</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                            <i class="fa-solid fa-star-half-stroke text-4xl text-yellow-300"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800 mb-1">Chưa có đánh giá</h3>
                        <p class="text-gray-400 font-medium text-sm">Bạn chưa nhận được đánh giá nào từ khách hàng.</p>
                    </div>
                @endempty
            </div>
            @if($receivedReviews->hasPages())
                <div class="p-5 border-t border-gray-100 bg-gray-50/50">{{ $receivedReviews->links() }}</div>
            @endif
        </div>

        {{-- TAB 2: ĐÁNH GIÁ ĐÃ GỬI --}}
        <div x-show="tab === 'sent'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            <div class="divide-y divide-gray-50">
                @forelse($sentReviews as $review)
                    <div class="p-6 md:p-8 hover:bg-gray-50/50 transition-colors">
                        <div class="flex gap-4 md:gap-5">
                            {{-- Avatar Shop --}}
                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg shrink-0 border border-blue-100 shadow-sm">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            
                            {{-- Nội dung --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-gray-900 text-base">Đánh giá shop: <a href="{{ route('shop.show', $review->receiver->id) }}" class="text-blue-600 hover:underline">{{ $review->receiver->name }}</a></h4>
                                    <span class="text-xs text-gray-400 font-medium flex items-center gap-1"><i class="fa-regular fa-clock"></i> {{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="flex text-yellow-400 text-xs mb-3 gap-0.5">
                                    @for($i=1; $i<=5; $i++)
                                        <i class="{{ $i <= $review->rating ? 'fa-solid' : 'fa-regular text-gray-200' }} fa-star"></i>
                                    @endfor
                                </div>
                                
                                @if($review->comment)
                                    <p class="text-gray-700 text-sm leading-relaxed mb-4">"{{ $review->comment }}"</p>
                                @else
                                    <p class="text-gray-400 text-sm italic mb-4">Bạn không để lại lời nhận xét.</p>
                                @endif
                                
                                {{-- BLOCK SẢN PHẨM HIỆN ĐẠI CÓ ẢNH --}}
                                @if($review->order && $review->order->product)
                                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 p-2.5 rounded-xl w-full max-w-md group hover:border-blue-200 transition-colors">
                                        <div class="w-10 h-10 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-100 flex items-center justify-center shadow-sm">
                                            @if($review->order->product->images && $review->order->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $review->order->product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            @else
                                                <i class="fa-regular fa-image text-gray-300"></i>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Sản phẩm đánh giá</p>
                                            <a href="{{ route('products.show', $review->order->product->slug) }}" class="text-xs font-bold text-gray-800 truncate block hover:text-blue-600 transition-colors">
                                                {{ $review->order->product->title }}
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="inline-flex items-center gap-2 bg-gray-50 border border-gray-100 px-3 py-2 rounded-lg">
                                        <i class="fa-solid fa-box-open text-gray-400"></i>
                                        <span class="text-xs text-gray-500 italic">Sản phẩm đã bị xóa hoặc ẩn</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
                            <i class="fa-regular fa-comment-dots text-4xl text-gray-300"></i>
                        </div>
                        <h3 class="text-lg font-black text-gray-800 mb-1">Chưa có đánh giá nào</h3>
                        <p class="text-gray-400 font-medium text-sm">Bạn chưa thực hiện đánh giá cho đơn hàng nào.</p>
                        <a href="{{ route('orders.index') }}" class="mt-4 inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-50 text-emerald-600 font-bold rounded-xl hover:bg-emerald-100 transition-colors">
                            Đến trang Đơn hàng <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                @endempty
            </div>
            @if($sentReviews->hasPages())
                <div class="p-5 border-t border-gray-100 bg-gray-50/50">{{ $sentReviews->links() }}</div>
            @endif
        </div>

    </div>
</div>
@endsection