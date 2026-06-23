@extends('layouts.app')

@section('title', 'Trang chủ - 2HAND')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6 md:py-10">

    {{-- ===== HERO BANNER ===== --}}
    {{-- Đã giảm chiều cao h-44, nới rộng px-16 để chữ dàn đều 1 dòng --}}
    <div class="bg-gradient-to-r from-emerald-500 via-emerald-500 to-green-600 rounded-[2rem] h-36 md:h-44 mb-12 flex items-center px-8 md:px-12 lg:px-16 relative overflow-hidden shadow-xl shadow-emerald-200/40 border border-white/20">
        
        {{-- LỚP ẢNH HỌA TIẾT BÊN PHẢI --}}
        <div class="absolute inset-y-0 right-0 w-1/2 opacity-30 mix-blend-overlay" 
             style="background-image: url('{{ asset('images/banner-pattern.png') }}'); background-size: cover; background-position: right center; background-repeat: no-repeat;">
        </div>

        {{-- LỚP ÁNH SÁNG MỜ TẠO ĐỘ SÂU --}}
        <div class="absolute top-0 right-[20%] w-64 h-64 bg-white opacity-20 rounded-full blur-3xl -translate-y-1/2"></div>
        <div class="absolute bottom-0 left-10 w-48 h-48 bg-yellow-300 opacity-15 rounded-full blur-2xl translate-y-1/2"></div>
        <div class="absolute top-[-20px] left-[50%] w-32 h-32 bg-emerald-100/30 rounded-full blur-xl"></div>

        <div class="relative z-10 w-full flex justify-between items-center">
            <div class="max-w-2xl">
                {{-- Tiêu đề đã được chỉnh size chuẩn để vừa 1 dòng --}}
                <h1 class="text-2xl md:text-3xl lg:text-[2.5rem] font-black text-white drop-shadow-sm tracking-tight mb-2 leading-tight">
                    Cũ người mới ta, mua bán mượt mà!
                </h1>
                <p class="text-emerald-50 font-medium text-sm md:text-base opacity-95 max-w-lg leading-relaxed">
                    Nền tảng thanh lý, trao đổi đồ cũ an toàn và tiện lợi nhất. Hãy khám phá ngay hàng ngàn sản phẩm giá hời!
                </p>
            </div>
        </div>
    </div>

    {{-- Thông báo --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 p-4 rounded-2xl shadow-sm flex items-center gap-3 font-bold mb-10 animate-fade-in-down">
        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-check"></i></div>
        {{ session('success') }}
    </div>
    @endif

    {{-- ===== KHÁM PHÁ DANH MỤC ===== --}}
    @if(!request()->hasAny(['search', 'category_id']))
    <div class="mb-14">
        <h2 class="text-2xl font-black text-gray-900 mb-7 flex items-center gap-3 tracking-tight">
            <i class="fa-solid fa-compass text-emerald-500"></i> Khám phá danh mục
        </h2>
        
        <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-6 gap-3 md:gap-4 lg:gap-5">
            @php
                $iconMap = [
                    'Thời trang' => 'fa-solid fa-shirt',
                    'Đồ gia dụng, Điện lạnh' => 'fa-solid fa-blender', 
                    'Sách & Truyện tranh' => 'fa-solid fa-book-open',
                    'Điện lạnh' => 'fa-solid fa-snowflake', 
                    'Xe cộ' => 'fa-solid fa-motorcycle',
                    'Đồ điện tử' => 'fa-solid fa-desktop',
                    'Thể thao' => 'fa-solid fa-basketball',
                    'Giải trí' => 'fa-solid fa-gamepad',
                    'Thú cưng' => 'fa-solid fa-paw',
                ];
            @endphp

            @foreach($rootCategories->take(6) as $cat)
                @php $icon = $iconMap[$cat->name] ?? 'fa-solid fa-box-open'; @endphp
                
                <a href="{{ url('/?category_id=' . $cat->id) }}" class="bg-white p-3.5 md:p-4 rounded-2xl border border-gray-100 hover:border-emerald-200 hover:shadow-[0_12px_30px_-5px_rgba(16,185,129,0.15)] transition-all duration-300 text-center group flex flex-col items-center justify-center h-full">
                    
                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gray-50 rounded-xl flex items-center justify-center text-gray-300 group-hover:bg-emerald-50 group-hover:text-emerald-500 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 mb-2 md:mb-3 shadow-sm border border-gray-100">
                        <i class="{{ $icon }} text-lg md:text-xl"></i>
                    </div>
                    
                    <h3 class="text-[10px] md:text-[11px] font-bold text-gray-700 group-hover:text-emerald-700 line-clamp-2 leading-snug">{{ $cat->name }}</h3>
                </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ===== LƯỚI SẢN PHẨM ===== --}}
    <div>
        <div class="flex justify-between items-end mb-7">
            <h2 class="text-2xl font-black text-gray-900 flex items-center gap-3 tracking-tight">
                @if(request()->hasAny(['search', 'category_id']))
                    <i class="fa-solid fa-magnifying-glass text-emerald-500"></i> Kết quả tìm kiếm
                    <a href="{{ url('/') }}" class="text-xs font-bold bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg transition-colors flex items-center ml-2 border border-red-100">
                        <i class="fa-solid fa-xmark mr-1"></i> Hủy lọc
                    </a>
                @else
                    <i class="fa-solid fa-fire text-orange-500"></i> Tin đăng mới nhất
                @endif
            </h2>
            
            @if(!request()->hasAny(['search', 'category_id']))
                <a href="#" class="text-xs md:text-sm bg-emerald-50 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-100 font-bold flex items-center gap-2 group transition-colors px-3 py-1.5 md:px-4 md:py-2 rounded-xl border border-emerald-100">
                    <span class="hidden sm:inline">Xem tất cả</span> <i class="fa-solid fa-arrow-right-long transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            @endif
        </div>

        @if($products->isEmpty())
        <div class="bg-white rounded-[2.5rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] p-16 text-center border border-gray-100 flex flex-col items-center">
            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-5xl mb-5 shadow-inner border-4 border-white">
                <i class="fa-solid fa-box-open"></i>
            </div>
            <p class="text-gray-500 font-bold text-lg">Không tìm thấy sản phẩm nào phù hợp.</p>
            @if(request()->hasAny(['search', 'category_id']))
                <a href="{{ url('/') }}" class="inline-block mt-5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold px-8 py-3 rounded-xl shadow-lg shadow-emerald-200/50 hover:-translate-y-0.5 transition-transform group">Xóa bộ lọc và khám phá <i class="fa-solid fa-arrow-right-long text-xs ml-1 transform group-hover:translate-x-1 transition-transform"></i></a>
            @endif
        </div>
        @else
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 lg:gap-5">
            @foreach($products as $product)
            <div class="bg-white rounded-[2rem] shadow-sm hover:shadow-[0_15px_40px_-10px_rgba(16,185,129,0.15)] hover:-translate-y-1.5 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group relative animate-fade-in-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                
                @if($product->pushed_until && $product->pushed_until > now())
                    <div class="absolute top-3 left-3 z-10 bg-gradient-to-r from-red-500 to-orange-500 text-white text-[10px] font-black px-2.5 py-1.5 rounded-lg shadow-md uppercase tracking-wide flex items-center gap-1.5 border border-white/20">
                        <i class="fa-solid fa-bolt text-yellow-200"></i> NỔI BẬT
                    </div>
                @endif

                @php
                    $isFavorited = Auth::check() ? Auth::user()->favorites->contains($product->id) : false;
                @endphp
                <button onclick="toggleFavorite({{ $product->id }}, this)" type="button" class="absolute top-3 right-3 z-10 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center shadow-sm hover:scale-110 hover:bg-white transition-all outline-none border border-gray-100 hover:border-red-200">
                    <i class="{{ $isFavorited ? 'fa-solid text-red-500' : 'fa-regular text-gray-400 hover:text-red-500' }} fa-heart text-base transition-colors duration-300"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}" class="block relative aspect-square bg-gray-50 flex items-center justify-center overflow-hidden">
                    @if($product->images && $product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    @else
                        <div class="text-center text-gray-300">
                            <i class="fa-solid fa-image text-5xl mb-2 block"></i>
                            <span class="text-xs font-bold">Chưa có hình</span>
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-black/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </a>

                <div class="p-4 md:p-5 flex flex-col flex-grow">
                    <h3 class="text-sm font-bold text-gray-800 line-clamp-2 h-10 mb-3 group-hover:text-emerald-600 transition-colors leading-snug" title="{{ $product->title }}">
                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
                    </h3>

                    <div class="mt-auto pt-3 border-t border-gray-50">
                        <p class="text-lg md:text-[19px] font-black text-red-600 mb-3 tracking-tight">{{ number_format($product->price, 0, ',', '.') }} <span class="text-sm md:text-base underline">đ</span></p>
                        
                        <div class="flex items-center justify-between text-[11px] font-medium">
                            <div class="flex items-center text-gray-400 truncate max-w-[60%]" title="{{ $product->location ?? 'Toàn quốc' }}">
                                <i class="fa-solid fa-location-dot text-gray-300 mr-1.5"></i> 
                                <span class="truncate">{{ $product->location ?? 'Toàn quốc' }}</span>
                            </div>
                            <span class="bg-emerald-50 border border-emerald-100 text-emerald-600 px-2 py-1 rounded-lg font-bold whitespace-nowrap">
                                {{ $product->condition_pct }}% Mới
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection