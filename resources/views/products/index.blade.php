@extends('layouts.app')

@section('title', 'Trang chủ - 2HAND')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-6">

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

    {{-- ===== KHÁM PHÁ DANH MỤC ===== --}}
    @if(!request()->hasAny(['search', 'category_id']))
    <div class="mb-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Khám phá danh mục</h2>
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            
            @php
                $iconMap = [
                    'Đồ điện tử' => 'fa-desktop',
                    'Xe cộ' => 'fa-motorcycle',
                    'Đồ gia dụng, Điện lạnh' => 'fa-blender',
                    'Thời trang' => 'fa-shirt',
                    'Sách & Truyện tranh' => 'fa-book',
                    'Thể thao' => 'fa-basketball',
                    'Giải trí' => 'fa-gamepad',
                    'Thú cưng' => 'fa-paw',
                ];
            @endphp

            @foreach($rootCategories->take(6) as $cat)
                @php $icon = $iconMap[$cat->name] ?? 'fa-box-open'; @endphp
                
                <a href="{{ url('/?category_id=' . $cat->id) }}" class="bg-white p-4 rounded-xl border border-gray-100 hover:border-green-400 hover:shadow-md transition text-center group">
                    <div class="w-12 h-12 mx-auto bg-green-50 rounded-full flex items-center justify-center text-green-600 group-hover:bg-green-600 group-hover:text-white transition mb-2">
                        <i class="fa-solid {{ $icon }} text-xl"></i>
                    </div>
                    <h3 class="text-xs font-bold text-gray-700 group-hover:text-green-700">{{ $cat->name }}</h3>
                </a>
            @endforeach
            
        </div>
    </div>
    @endif

    {{-- ===== LƯỚI SẢN PHẨM ===== --}}
    <div>
        <div class="flex justify-between items-end mb-5">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                @if(request()->hasAny(['search', 'category_id']))
                    <span>Kết quả tìm kiếm</span>
                    <a href="{{ url('/') }}" class="text-xs font-medium bg-gray-100 hover:bg-red-50 text-gray-600 hover:text-red-500 px-3 py-1.5 rounded-lg transition-colors flex items-center">
                        <i class="fa-solid fa-xmark mr-1"></i> Hủy lọc
                    </a>
                @else
                    Tin đăng mới nhất
                @endif
            </h2>
            
            @if(!request()->hasAny(['search', 'category_id']))
                <a href="#" class="text-sm text-green-700 hover:underline font-bold">Xem tất cả <i class="fa-solid fa-angle-right"></i></a>
            @endif
        </div>

        @if($products->isEmpty())
        <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-100">
            <div class="text-gray-300 text-5xl mb-3"><i class="fa-solid fa-box-open"></i></div>
            <p class="text-gray-500 font-medium">Không tìm thấy sản phẩm nào phù hợp.</p>
            @if(request()->hasAny(['search', 'category_id']))
                <a href="{{ url('/') }}" class="inline-block mt-4 bg-green-100 text-green-700 font-bold px-4 py-2 rounded-lg hover:bg-green-200 transition">Xóa bộ lọc và thử lại</a>
            @endif
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
            @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group relative">
                
                {{-- Badge Đẩy Tin (Nổi bật) --}}
                @if($product->pushed_until && $product->pushed_until > now())
                    <div class="absolute top-2 left-2 z-10 bg-gradient-to-r from-yellow-400 to-orange-500 text-white text-[9px] font-black px-2 py-1 rounded shadow-md uppercase tracking-wider flex items-center gap-1">
                        <i class="fa-solid fa-fire text-yellow-100"></i> Nổi bật
                    </div>
                @endif

                {{-- Nút Thả Tim (Đã sửa lại class cho icon để JS có thể nhận diện và đổi màu) --}}
                @php
                    $isFavorited = Auth::check() ? Auth::user()->favorites->contains($product->id) : false;
                @endphp
                <button onclick="toggleFavorite({{ $product->id }}, this)" type="button" class="absolute top-2 right-2 z-10 w-8 h-8 bg-white/80 backdrop-blur rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform outline-none">
                    <i class="{{ $isFavorited ? 'fa-solid text-red-500' : 'fa-regular text-gray-400' }} fa-heart text-lg transition-colors duration-300"></i>
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
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection