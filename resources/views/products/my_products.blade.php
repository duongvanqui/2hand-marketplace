@extends('layouts.admin')

@section('title', 'Sản phẩm của tôi - 2HAND')
@section('header_title', 'Quản lý sản phẩm')

@section('header_actions')
<a href="{{ route('products.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition-all shadow-md flex items-center gap-2">
    <i class="fa-solid fa-plus"></i> Đăng tin mới
</a>
@endsection

@section('content')
<div class="space-y-6">

    {{-- 1. KHỐI THỐNG KÊ (5 CARDS) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-5">
        
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Tổng</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $totalProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-check"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Đang bán</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $activeProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-regular fa-clock"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Chờ duyệt</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $pendingProducts }}</h3>
            </div>
        </div>

        {{-- Ô MỚI: ĐÃ BÁN --}}
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Đã bán</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $soldProducts }}</h3>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">Từ chối</p>
                <h3 class="text-2xl font-black text-gray-800">{{ $rejectedProducts }}</h3>
            </div>
        </div>

    </div>

    {{-- 2. KHỐI TÌM KIẾM & LỌC --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
        <form method="GET" action="{{ route('my.products') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tên sản phẩm..." class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
            </div>
            <div class="w-full md:w-48">
                <select name="status" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all cursor-pointer">
                    <option value="">Tất cả trạng thái</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đang hiển thị</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="sold" {{ request('status') === 'sold' ? 'selected' : '' }}>Đã bán / Ẩn</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white font-bold rounded-xl text-sm hover:bg-gray-800 transition-colors">
                Tìm
            </button>
            @if(request()->hasAny(['search', 'status']) && (request('search') != '' || request('status') != ''))
            <a href="{{ route('my.products') }}" class="px-4 py-2.5 bg-red-50 text-red-600 font-bold rounded-xl text-sm hover:bg-red-100 transition-colors flex items-center">
                <i class="fa-solid fa-rotate-left"></i>
            </a>
            @endif
        </form>
    </div>

    {{-- 3. BẢNG DANH SÁCH SẢN PHẨM --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-[11px] font-black uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 w-12 text-center">#</th>
                        <th class="p-4">Sản phẩm</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4">Giá</th>
                        <th class="p-4">Trạng thái</th>
                        <th class="p-4">Ngày đăng</th>
                        <th class="p-4 text-center">Lượt xem</th>
                        <th class="p-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        
                        {{-- Cột STT --}}
                        <td class="p-4 text-center text-gray-400 font-medium">
                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                        </td>

                        {{-- Cột Sản phẩm (Ảnh + Tên) --}}
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
                                    <a href="{{ route('products.show', $product->slug) }}" class="font-bold text-gray-800 hover:text-blue-600 transition-colors line-clamp-1 max-w-[200px]">
                                        {{ $product->title }}
                                    </a>
                                    <p class="text-[12px] text-gray-400 font-semibold mt-0.5 tracking-wide">
                                        SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}
                                    </p>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Danh mục --}}
                        <td class="p-4 text-gray-600 font-medium text-xs">
                            {{ $product->category->name ?? 'Không phân loại' }}
                        </td>

                        {{-- Cột Giá --}}
                        <td class="p-4">
                            <p class="text-gray-900 font-black">{{ number_format($product->price) }}đ</p>
                        </td>

                        {{-- Cột Trạng thái (Giống hệt Admin) --}}
                        <td class="p-4">
                            @if($product->status === 'approved')
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-md text-[11px] font-bold">Đang hiển thị</span>
                            @elseif($product->status === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-md text-[11px] font-bold">Chờ duyệt</span>
                            @elseif($product->status === 'sold')
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-md text-[11px] font-bold">Đã bán</span>
                            @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-md text-[11px] font-bold">Từ chối</span>
                            @endif
                        </td>

                        {{-- Cột Ngày đăng --}}
                        <td class="p-4 text-gray-500 text-xs">
                            {{ $product->created_at->format('d/m/Y') }}
                            <br>
                            <span class="text-[10px] text-gray-400">{{ $product->created_at->format('H:i') }}</span>
                        </td>

                        {{-- Cột Lượt xem --}}
                        <td class="p-4 text-center font-bold text-gray-600">
                            {{ number_format($product->view_count) }}
                        </td>

                        {{-- Cột Thao tác --}}
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                {{-- Nút Xem --}}
                                <a href="{{ route('products.show', $product->slug) }}" class="w-8 h-8 flex items-center justify-center rounded hover:bg-gray-100 text-gray-500 hover:text-gray-900 transition-colors" title="Xem chi tiết">
                                    <i class="fa-regular fa-eye text-sm"></i>
                                </a>
                                
                                {{-- Nút Sửa (Đã thêm logic khóa khi sản phẩm đã bán) --}}
                                @if($product->status !== 'sold')
                                    <a href="{{ route('products.edit', $product->id) }}" class="w-8 h-8 flex items-center justify-center rounded hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors" title="Sửa tin">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </a>
                                @else
                                    <button disabled class="w-8 h-8 flex items-center justify-center rounded text-gray-300 cursor-not-allowed" title="Sản phẩm đã bán không thể sửa">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </button>
                                @endif

                                {{-- Nút Xóa/Ẩn --}}
                                <form action="#" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa/ẩn tin này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded hover:bg-red-50 text-gray-500 hover:text-red-600 transition-colors" title="Xóa tin">
                                        <i class="fa-solid fa-ban text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-16 text-center text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300"></i>
                                <p class="font-medium">Không tìm thấy sản phẩm nào.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="p-4 border-t border-gray-100 bg-white">
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>
@endsection