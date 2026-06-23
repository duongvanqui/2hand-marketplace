@extends('layouts.admin')

@section('title', 'Sản phẩm của tôi - 2HAND')

{{-- Thanh Breadcrumb (Thanh điều hướng) --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight">
        Sản phẩm của tôi
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition-colors">Quản lý cá nhân</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <span class="text-gray-900 font-bold">Sản phẩm của tôi</span>
    </div>
</div>
@endsection

@section('header_actions')
<a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition-all shadow-md shadow-emerald-200 transform hover:-translate-y-0.5 flex items-center gap-2">
    <i class="fa-solid fa-plus"></i> Đăng tin mới
</a>
@endsection

@section('content')
<div class="space-y-6 pb-10">

    {{-- KHỐI THỐNG KÊ (5 CARDS) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Tổng SP</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalProducts ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Đang bán</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $activeProducts ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Chờ duyệt</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $pendingProducts ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0 border border-blue-100 shadow-inner">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Đã bán</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $soldProducts ?? 0 }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-lock"></i>
            </div>
            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-0.5">Khóa/Từ chối</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $rejectedProducts ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- KHỐI TÌM KIẾM & LỌC --}}
    <div class="bg-white p-4 rounded-[1.25rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100">
        <form method="GET" action="{{ route('my.products') }}" class="flex flex-wrap lg:flex-nowrap gap-3 items-center">
            
            {{-- ĐÃ SỬA: Đổi từ flex-1 sang width cố định (md:w-96) để thu nhỏ độ rộng --}}
            <div class="relative w-full md:w-96 shrink-0">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên SP, mã SP..." 
                       class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium">
            </div>

            {{-- ĐÃ XÓA: Mục chọn Danh mục theo yêu cầu --}}

            <div class="w-full md:w-48 shrink-0">
                <select name="status" class="w-full px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all cursor-pointer">
                    <option value="">Tất cả trạng thái</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đang bán</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Đã bán</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Khóa/Từ chối</option>
                </select>
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto shrink-0">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-gray-800 transition-colors shadow-sm">
                    Tìm
                </button>
                @if(request()->hasAny(['search', 'status']) && (request('search') != '' || request('status') != ''))
                <a href="{{ route('my.products') }}" class="px-5 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl text-sm hover:bg-red-100 transition-colors flex items-center shadow-sm border border-red-100 whitespace-nowrap">
                    <i class="fa-solid fa-xmark mr-1.5"></i> Xóa lọc
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- BẢNG DANH SÁCH SẢN PHẨM --}}
    <div class="bg-white rounded-[1.5rem] shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-[11px] font-black uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Sản phẩm</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4 text-right">Giá</th>
                        <th class="p-4 text-center">Trạng thái</th>
                        <th class="p-4 text-center">Ngày đăng</th>
                        <th class="p-4 text-center">Lượt xem</th>
                        <th class="p-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($products as $product)
                    
                    <tr class="hover:bg-gray-50/80 transition-colors {{ $product->status === 'sold' ? 'opacity-60 bg-gray-50/30' : '' }}">
                        
                        <td class="p-4 text-center text-gray-400 font-medium">
                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('products.show', $product->slug) }}" class="w-12 h-12 rounded-lg bg-gray-50 overflow-hidden shrink-0 border border-gray-100 flex items-center justify-center">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <i class="fa-solid fa-image text-gray-300"></i>
                                    @endif
                                </a>
                                <div>
                                    <a href="{{ route('products.show', $product->slug) }}" class="font-bold text-gray-800 hover:text-emerald-600 transition-colors line-clamp-1 max-w-[250px]">
                                        {{ $product->title }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-bold mt-0.5 tracking-wider uppercase">
                                        SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4 text-gray-600 font-medium text-xs">
                            {{ $product->category->name ?? 'Không phân loại' }}
                        </td>

                        <td class="p-4 text-right">
                            <p class="text-gray-900 font-black">{{ number_format($product->price) }}<span class="text-xs ml-0.5 underline">đ</span></p>
                        </td>

                        <td class="p-4 text-center">
                            @if($product->status === 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[11px] font-bold inline-flex items-center gap-1 border border-emerald-100"><i class="fa-solid fa-check"></i> Đang bán</span>
                            @elseif($product->status === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-md text-[11px] font-bold inline-flex items-center gap-1 border border-yellow-100"><i class="fa-regular fa-clock"></i> Chờ duyệt</span>
                            @elseif($product->status === 'sold')
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-[11px] font-bold inline-flex items-center gap-1 border border-blue-100"><i class="fa-solid fa-handshake"></i> Đã bán</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md text-[11px] font-bold inline-flex items-center gap-1 border border-red-100"><i class="fa-solid fa-lock"></i> Khóa/Từ chối</span>
                            @endif
                        </td>

                        <td class="p-4 text-gray-500 text-xs text-center font-medium">
                            {{ $product->created_at->format('d/m/Y') }}
                            <br>
                            <span class="text-[10px] text-gray-400">{{ $product->created_at->format('H:i') }}</span>
                        </td>

                        <td class="p-4 text-center font-bold text-gray-600">
                            {{ number_format($product->view_count) }}
                        </td>

                        <td class="p-4">
                            <div class="flex items-center justify-center gap-1.5">
                                @if($product->status === 'sold')
                                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-300 cursor-not-allowed" title="Sản phẩm đã bán không thể xem">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </button>
                                    <button disabled class="w-8 h-8 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-300 cursor-not-allowed" title="Sản phẩm đã bán không thể thao tác">
                                        <i class="fa-solid fa-lock text-xs"></i>
                                    </button>
                                @else
                                    <a href="{{ route('products.show', $product->slug) }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors border border-transparent hover:border-gray-200" title="Xem chi tiết">
                                        <i class="fa-regular fa-eye text-xs"></i>
                                    </a>
                                    
                                    <a href="{{ route('products.edit', $product->id) }}" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-emerald-50 text-gray-500 hover:text-emerald-600 transition-colors border border-transparent hover:border-emerald-200" title="Sửa tin">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <form action="{{ Route::has('products.destroy') ? route('products.destroy', $product->id) : url('/products/'.$product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa tin này không?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors border border-transparent hover:border-red-200" title="Xóa tin">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-box-open text-2xl text-gray-300"></i>
                                </div>
                                <p class="font-bold text-gray-600 mb-1">Không tìm thấy sản phẩm nào</p>
                                <p class="text-sm font-medium">Thử thay đổi bộ lọc tìm kiếm hoặc đăng tin mới nhé!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-5 border-t border-gray-100 bg-gray-50/30">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection