@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm - Admin')

{{-- Tùy chỉnh Header --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản lý Sản phẩm
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1 flex items-center">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        <span class="text-gray-900 font-bold">Quản lý Sản phẩm</span>
    </p>
</div>
@endsection

@section('header_actions')
<button type="button" onclick="openModal('modal-export')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection

@section('content')
<div class="pb-24">

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

    {{-- ========================================== --}}
    {{-- 1. THỐNG KÊ TỔNG QUAN --}}
    {{-- ========================================== --}}
    @php
        $total = \App\Models\Product::count();
        $approved = \App\Models\Product::whereIn('status', ['approved', '1', 'Đang bán'])->count();
        $pending = \App\Models\Product::where('status', 'pending')->count();
        $sold = \App\Models\Product::whereIn('status', ['sold', 'Đã bán'])->count();
        $rejected = \App\Models\Product::whereIn('status', ['rejected', 'locked', 'hidden', '0'])->count();

        $last7Days = now()->subDays(7);
        $totalNew = \App\Models\Product::where('created_at', '>=', $last7Days)->count();
        $approvedNew = \App\Models\Product::whereIn('status', ['approved', '1'])->where('updated_at', '>=', $last7Days)->count();
    @endphp

    <div class="grid grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Tổng sản phẩm</p>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ number_format($total) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-blue-500 truncate">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +{{ $totalNew }} SP mới tuần này
            </div>
        </div>

        <a href="{{ route('admin.products.index', ['status' => 'approved']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'approved' ? 'border-emerald-300 bg-emerald-50/20' : 'border-gray-100' }} hover:shadow-lg hover:border-emerald-200 transition-all group block">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Đang bán</p>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ number_format($approved) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-emerald-500 truncate">
                <i class="fa-solid fa-check-double mr-1"></i> +{{ $approvedNew }} SP duyệt tuần này
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'pending' ? 'border-yellow-300 bg-yellow-50/20' : 'border-gray-100' }} hover:shadow-lg hover:border-yellow-200 transition-all group block">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Chờ duyệt</p>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ number_format($pending) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-yellow-600 truncate">
                @if($pending > 0)
                    <i class="fa-solid fa-bell mr-1 animate-pulse"></i> Cần xử lý ngay!
                @else
                    <i class="fa-solid fa-mug-hot mr-1"></i> Đã xử lý hết
                @endif
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'sold']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'sold' ? 'border-blue-300 bg-blue-50/20' : 'border-gray-100' }} hover:shadow-lg hover:border-blue-200 transition-all group block">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-handshake"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wide">Đã bán</p>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ number_format($sold) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-blue-500 truncate">
                <i class="fa-solid fa-boxes-packing mr-1"></i> Đơn hàng hoàn tất
            </div>
        </a>

        <a href="{{ route('admin.products.index', ['status' => 'hidden']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ in_array(request('status'), ['hidden', 'locked', '0', 'rejected']) ? 'border-red-300 bg-red-50/20' : 'border-red-50' }} hover:shadow-lg hover:border-red-200 transition-all group block bg-gradient-to-br from-white to-red-50/30">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform shrink-0">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-red-500 uppercase tracking-wide">Khóa/Từ chối</p>
                    <h3 class="text-xl font-black text-gray-900 leading-tight">{{ number_format($rejected) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-red-100 flex items-center text-[10px] font-bold text-red-500 truncate">
                <i class="fa-solid fa-triangle-exclamation mr-1"></i> SP Vi phạm
            </div>
        </a>
    </div>

    {{-- ========================================== --}}
    {{-- 2. THANH CÔNG CỤ (GỌN GÀNG ĐỒNG BỘ) --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row justify-between items-center gap-4 mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full xl:w-auto">
            
            {{-- Ô Search --}}
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên SP, người bán..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all font-medium">
            </div>

            {{-- Lọc Danh mục --}}
            <select name="category_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto outline-none font-medium cursor-pointer">
                <option value="">Tất cả danh mục</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            {{-- Lọc Trạng thái --}}
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto outline-none font-medium cursor-pointer">
                <option value="">Tất cả trạng thái</option>
                <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="approved" {{ request('status') === 'approved' || request('status') === '1' ? 'selected' : '' }}>Đang bán</option>
                <option value="sold"     {{ request('status') === 'sold'     ? 'selected' : '' }}>Đã bán</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                <option value="hidden"   {{ in_array(request('status'), ['hidden', 'locked', '0']) ? 'selected' : '' }}>Đã khóa / Tạm ẩn</option>
            </select>

            {{-- Nút Tìm & Hủy gom chung --}}
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-700 transition shadow-sm whitespace-nowrap">
                    Tìm
                </button>
                @if(request()->hasAny(['search', 'status', 'category_id']))
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 flex items-center justify-center whitespace-nowrap">
                        <i class="fa-solid fa-xmark mr-1"></i> Xoá lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ========================================== --}}
    {{-- 3. BẢNG DANH SÁCH SẢN PHẨM --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 text-center w-12">
                            <input type="checkbox" id="checkAll" class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                        </th>
                        <th class="p-4 font-black text-gray-400">#</th>
                        <th class="p-4">Sản phẩm</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4">Người bán</th>
                        <th class="p-4 text-right">Giá</th>
                        <th class="p-4 text-center">Trạng thái</th>
                        <th class="p-4 text-center">Ngày đăng</th>
                        <th class="p-4 text-center">Lượt xem</th>
                        <th class="p-4 text-center pr-6">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($products as $index => $product)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        
                        <td class="p-4 text-center">
                            {{-- Disable Checkbox nếu sản phẩm đã bán hoặc đang bị khóa/từ chối --}}
                            @if(in_array($product->status, ['sold', 'Đã bán', 'locked', 'rejected']))
                                <input type="checkbox" disabled class="w-4 h-4 rounded border-gray-200 bg-gray-100 cursor-not-allowed" title="Sản phẩm trạng thái này không thể xử lý hàng loạt">
                            @else
                                <input type="checkbox" value="{{ $product->id }}" class="product-checkbox w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                            @endif
                        </td>
                        
                        <td class="p-4 text-gray-400 font-bold">{{ $products->firstItem() + $index }}</td>
                        
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.products.show', $product->id) }}" class="w-12 h-12 rounded-xl bg-gray-100 border border-gray-200 overflow-hidden shrink-0 block">
                                    @if($product->images && $product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image"></i></div>
                                    @endif
                                </a>
                                <div class="max-w-[200px]">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition-colors truncate block" title="{{ $product->title }}">
                                        {{ $product->title }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    @if($product->pushed_until && $product->pushed_until > now())
                                        <span class="inline-flex items-center text-[9px] text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded mt-1 font-bold border border-purple-100"><i class="fa-solid fa-arrow-up mr-1"></i>Đẩy tin</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        <td class="p-4 text-gray-600 font-medium">{{ $product->category->name ?? 'N/A' }}</td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden shrink-0 border border-gray-100">
                                    @if($product->user && $product->user->avatar)
                                        <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-emerald-100 text-emerald-600 font-bold text-[10px]">{{ substr($product->user->name ?? 'U', 0, 1) }}</div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $product->user->name ?? 'N/A' }}</p>
                                    <p class="text-[10px] text-gray-400">ID: {{ $product->user->id ?? '---' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4 font-black text-gray-900 text-right">{{ number_format($product->price) }}<span class="text-xs font-normal underline ml-0.5 text-gray-500">đ</span></td>

                        <td class="p-4 text-center">
                            @php
                                $statusStr = (string) $product->status;
                                $statusStyles = [
                                    'pending'  => ['bg-yellow-50 text-yellow-600 border-yellow-100', 'Chờ duyệt', 'fa-clock'],
                                    'approved' => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Đang bán', 'fa-check'],
                                    '1'        => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Đang bán', 'fa-check'],
                                    'Đang bán' => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Đang bán', 'fa-check'],
                                    'rejected' => ['bg-red-50 text-red-600 border-red-100', 'Từ chối', 'fa-ban'],
                                    'sold'     => ['bg-blue-50 text-blue-600 border-blue-100', 'Đã bán', 'fa-handshake'],
                                    'Đã bán'   => ['bg-blue-50 text-blue-600 border-blue-100', 'Đã bán', 'fa-handshake'],
                                    'hidden'   => ['bg-gray-100 text-gray-500 border-gray-200', 'Tạm ẩn', 'fa-eye-slash'],
                                    '0'        => ['bg-gray-100 text-gray-500 border-gray-200', 'Tạm ẩn', 'fa-eye-slash'],
                                    'locked'   => ['bg-red-50 text-red-600 border-red-200', 'Đã khóa', 'fa-lock'],
                                ];
                                $style = $statusStyles[$statusStr] ?? ['bg-gray-100 text-gray-600 border-gray-200', $statusStr, 'fa-circle-info'];
                            @endphp
                            
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-bold border {{ $style[0] }} whitespace-nowrap">
                                <i class="fa-solid {{ $style[2] }} mr-1"></i> {{ $style[1] }}
                            </span>

                            @if($product->status === 'rejected' && $product->rejection_reason)
                                <p class="text-[9px] text-red-400 mt-1 max-w-[100px] truncate mx-auto cursor-help" title="{{ $product->rejection_reason }}"><i class="fa-solid fa-circle-exclamation"></i> Lỗi vi phạm</p>
                            @endif
                        </td>

                        <td class="p-4 text-center text-gray-500 font-medium text-xs">
                            <p>{{ $product->created_at->format('d/m/Y') }}</p>
                            <p class="text-gray-400">{{ $product->created_at->format('H:i') }}</p>
                        </td>

                        <td class="p-4 text-center font-bold text-gray-600">
                            {{ number_format($product->view_count ?? 0) }}
                        </td>

                        <td class="p-4 pr-6">
                            <div class="flex items-center justify-center space-x-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                <a href="{{ route('admin.products.show', $product->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-50 text-gray-600 border border-gray-100 hover:border-gray-300 hover:bg-gray-100 transition-all shadow-sm" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                @if(in_array($product->status, ['sold', 'Đã bán']))
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-50 text-gray-300 border border-gray-100 shadow-sm cursor-not-allowed" title="Sản phẩm đã bán không thể chỉnh sửa">
                                        <i class="fa-solid fa-lock text-[10px]"></i>
                                    </div>
                                @else
                                    @php $safeTitle = addslashes(htmlspecialchars($product->title)); @endphp

                                    @if(in_array($product->status, ['pending', 'rejected', 'locked', 'hidden', '0']))
                                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" onclick="return confirm('Duyệt hiển thị công khai tin đăng này?')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all shadow-sm" title="Duyệt bài">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    @if(in_array($product->status, ['approved', '1', 'Đang bán']))
                                    <button type="button" onclick="openPushModal({{ $product->id }}, '{{ $safeTitle }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-50 text-purple-600 hover:bg-purple-500 hover:text-white transition-all shadow-sm" title="Đẩy tin lên đầu">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>
                                    @endif

                                    @if(in_array($product->status, ['pending', 'approved', '1', 'Đang bán']))
                                    <button type="button" onclick="openRejectModal({{ $product->id }}, '{{ $safeTitle }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white transition-all shadow-sm" title="Từ chối / Tạm ẩn tin">
                                        <i class="fa-solid fa-ban"></i>
                                    </button>
                                    @endif

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('CẢNH BÁO: Xóa vĩnh viễn tin đăng này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all shadow-sm" title="Xóa vĩnh viễn">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-medium text-gray-500">Chưa có sản phẩm nào khớp với tìm kiếm.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($products->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <p>Hiển thị từ <span class="font-bold text-gray-900">{{ $products->firstItem() }}</span> đến <span class="font-bold text-gray-900">{{ $products->lastItem() }}</span> trong tổng số <span class="font-bold text-gray-900">{{ $products->total() }}</span> sản phẩm</p>
            {{ $products->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

{{-- ========================================== --}}
{{-- THANH CÔNG CỤ XỬ LÝ HÀNG LOẠT (BULK ACTION) --}}
{{-- ========================================== --}}
<div id="bulk-action-bar" class="hidden fixed bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-4 rounded-full shadow-2xl items-center gap-5 z-40 transition-all duration-300 scale-95 opacity-0">
    <div class="flex items-center gap-2">
        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center text-xs font-bold" id="selected-count">0</div>
        <span class="text-sm font-medium text-gray-200">sản phẩm đang chọn</span>
    </div>
    <div class="h-6 w-px bg-gray-700"></div>
    
    <form id="bulk-form" action="{{ route('admin.products.bulk') }}" method="POST" class="flex items-center gap-2">
        @csrf
        <input type="hidden" name="product_ids" id="bulk-ids">
        
        <button type="submit" name="action" value="approve" onclick="return confirm('Duyệt tất cả sản phẩm đã chọn?')" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 rounded-full text-xs font-bold transition-colors shadow-sm">
            <i class="fa-solid fa-check mr-1"></i> Duyệt tất cả
        </button>
        <button type="submit" name="action" value="reject" onclick="return confirm('Từ chối/Ẩn tất cả sản phẩm đã chọn?')" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 rounded-full text-xs font-bold transition-colors shadow-sm">
            <i class="fa-solid fa-ban mr-1"></i> Từ chối
        </button>
        <button type="submit" name="action" value="delete" onclick="return confirm('CẢNH BÁO: Xóa vĩnh viễn tất cả sản phẩm đã chọn?')" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-full text-xs font-bold transition-colors shadow-sm">
            <i class="fa-solid fa-trash-can mr-1"></i> Xóa
        </button>
    </form>
</div>

{{-- ========================================== --}}
{{-- MODALS CÁC CHỨC NĂNG --}}
{{-- ========================================== --}}

{{-- Modal: Từ chối tin --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-red-50/30 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-ban text-red-500"></i> Từ chối / Tạm ẩn tin</h3>
            <button type="button" onclick="closeModal('modal-reject')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-reject" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                <p class="text-[11px] text-gray-500 uppercase font-bold mb-1">Sản phẩm bị xử lý</p>
                <p id="reject-name" class="text-sm font-bold text-gray-900 truncate"></p>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Lý do từ chối (Mẫu)</label>
                <select class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-red-400 transition-all cursor-pointer appearance-none"
                    onchange="if(this.value) document.getElementById('rejection_reason').value = this.value">
                    <option value="">-- Chọn lý do vi phạm --</option>
                    <option value="Ảnh sản phẩm không rõ nét, thiếu ảnh thực tế hoặc ảnh copy từ mạng.">Ảnh không đạt yêu cầu</option>
                    <option value="Mô tả sản phẩm quá sơ sài, thiếu thông tin tình trạng, xuất xứ.">Mô tả không đầy đủ</option>
                    <option value="Giá bán không hợp lý hoặc có dấu hiệu gian lận giá.">Giá bán sai thực tế</option>
                    <option value="Sản phẩm thuộc danh mục hàng cấm bán trên nền tảng.">Hàng cấm / Vi phạm quy định</option>
                    <option value="Vui lòng chọn lại danh mục phù hợp hơn với sản phẩm.">Sai danh mục</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Chi tiết lý do gửi cho người bán <span class="text-red-500">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                    placeholder="Nhập lý do chi tiết..."
                    class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-50 transition-all resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-2">
                <button type="button" onclick="closeModal('modal-reject')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-red-200">Xác nhận Từ chối</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Đẩy tin --}}
<div id="modal-push" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-purple-50/30 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-arrow-up text-purple-600"></i> Đẩy tin lên đầu</h3>
            <button type="button" onclick="closeModal('modal-push')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form id="form-push" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <input type="hidden" name="days" id="push-days" value="3">
            
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center mb-2">
                <p id="push-name" class="text-sm font-bold text-gray-900 truncate"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 text-center">Thời gian duy trì hiển thị TOP</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([1 => '1 Ngày', 3 => '3 Ngày', 7 => '7 Ngày'] as $d => $label)
                    <button type="button" onclick="selectDays({{ $d }}, this)"
                        class="push-day-btn py-3 border-2 rounded-xl text-sm font-bold transition-all {{ $d === 3 ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm' : 'border-gray-100 text-gray-500 hover:border-purple-200 hover:bg-purple-50/50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>
            
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-2">
                <button type="button" onclick="closeModal('modal-push')" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-purple-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Bắt đầu đẩy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Xuất Báo Cáo --}}
<div id="modal-export" class="fixed inset-0 z-[100] hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-xl max-w-[420px] w-full overflow-hidden flex flex-col transform transition-transform">
        
        <div class="px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-file-export text-blue-600"></i> Xuất Báo Cáo Sản Phẩm
            </h3>
            <button type="button" onclick="closeModal('modal-export')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.export', ['type' => 'products']) }}" method="GET" class="p-6">
            
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Từ ngày</label>
                    <input type="date" name="from_date" value="{{ now()->subMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Đến ngày</label>
                    <input type="date" name="to_date" value="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Dữ liệu xuất</label>
                <select name="export_status" class="w-full px-3 py-2.5 border border-gray-200 bg-white rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">
                    <option value="all">Tất cả sản phẩm</option>
                    <option value="pending">Chỉ sản phẩm Chờ Duyệt</option>
                    <option value="approved">Chỉ sản phẩm Đang Bán</option>
                    <option value="sold">Chỉ sản phẩm Đã Bán</option>
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Định dạng file</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                        <input type="radio" name="format" value="excel" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mt-0.5">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-excel text-green-600 text-lg"></i>
                            <span class="font-bold text-gray-700 text-sm">Excel</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                        <input type="radio" name="format" value="pdf" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mt-0.5">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-pdf text-red-600 text-lg"></i>
                            <span class="font-bold text-gray-700 text-sm">PDF</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-between gap-3">
                <button type="button" onclick="closeModal('modal-export')" class="w-1/3 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition-colors">
                    Hủy
                </button>
                <button type="submit" onclick="setTimeout(() => closeModal('modal-export'), 800)" class="w-2/3 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Tải xuống
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
    }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
    }

    function openRejectModal(id, title) {
        document.getElementById('reject-name').textContent = title;
        document.getElementById('form-reject').action = '/admin/products/' + id + '/reject';
        document.getElementById('rejection_reason').value = '';
        openModal('modal-reject');
    }

    function openPushModal(id, title) {
        document.getElementById('push-name').textContent = title;
        document.getElementById('form-push').action = '/admin/products/' + id + '/push';
        openModal('modal-push');
    }

    function selectDays(days, btn) {
        document.getElementById('push-days').value = days;
        document.querySelectorAll('.push-day-btn').forEach(b => {
            b.className = b.className.replace('border-purple-500 bg-purple-50 text-purple-700 shadow-sm', '');
            b.classList.add('border-gray-100', 'text-gray-500');
        });
        btn.classList.remove('border-gray-100', 'text-gray-500');
        btn.classList.add('border-purple-500', 'bg-purple-50', 'text-purple-700', 'shadow-sm');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkAll = document.getElementById('checkAll');
        const checkboxes = document.querySelectorAll('.product-checkbox');
        const bulkBar = document.getElementById('bulk-action-bar');
        const selectedCount = document.getElementById('selected-count');
        const bulkIdsInput = document.getElementById('bulk-ids');

        function updateBulkBar() {
            const checkedBoxes = Array.from(checkboxes).filter(c => c.checked && !c.disabled);
            if(checkedBoxes.length > 0) {
                bulkBar.classList.remove('hidden', 'scale-95', 'opacity-0');
                bulkBar.classList.add('flex', 'scale-100', 'opacity-100');
                selectedCount.innerText = checkedBoxes.length;
                bulkIdsInput.value = checkedBoxes.map(c => c.value).join(',');
            } else {
                bulkBar.classList.add('scale-95', 'opacity-0');
                setTimeout(() => bulkBar.classList.add('hidden'), 300);
                bulkBar.classList.remove('flex', 'scale-100', 'opacity-100');
            }
            
            if(checkAll) {
                const enabledCheckboxes = Array.from(checkboxes).filter(c => !c.disabled);
                checkAll.checked = (checkedBoxes.length === enabledCheckboxes.length) && enabledCheckboxes.length > 0;
            }
        }

        if(checkAll) {
            checkAll.addEventListener('change', function() {
                checkboxes.forEach(c => {
                    if(!c.disabled) c.checked = checkAll.checked;
                });
                updateBulkBar();
            });
        }

        checkboxes.forEach(c => {
            if(!c.disabled) {
                c.addEventListener('change', updateBulkBar);
            }
        });
    });
</script>
@endsection