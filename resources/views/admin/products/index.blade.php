@extends('layouts.admin')

@section('title', 'Quản lý Sản phẩm - Admin')

{{-- Tùy chỉnh Header theo chuẩn thiết kế --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản lý sản phẩm
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1">Quản lý tất cả sản phẩm trên hệ thống</p>
</div>
@endsection

{{-- Nút Xuất Báo Cáo góc phải --}}
@section('header_actions')
<button onclick="window.dispatchEvent(new CustomEvent('open-export-modal'))" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection

@section('content')
<div class="pb-10">

    {{-- HIỂN THỊ THÔNG BÁO FLASH --}}
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
    {{-- 1. THỐNG KÊ TỔNG QUAN (4 CARDS THEO ẢNH) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        {{-- Card: Tổng sản phẩm --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-laptop-code"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Tổng sản phẩm</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($stats['total'] ?? 0) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +12.5% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Sản phẩm đang bán --}}
        <a href="{{ route('admin.products.index', ['status' => 'approved']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'approved' ? 'border-emerald-300' : 'border-gray-100' }} hover:shadow-lg hover:border-emerald-200 transition-all group block">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Sản phẩm đang bán</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($stats['approved'] ?? 0) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +15.3% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </a>

        {{-- Card: Chờ duyệt --}}
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'pending' ? 'border-yellow-300' : 'border-gray-100' }} hover:shadow-lg hover:border-yellow-200 transition-all group block">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Sản phẩm chờ duyệt</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($stats['pending'] ?? 0) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-yellow-600">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +5.8% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </a>

        {{-- Card: Bị từ chối --}}
        <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}" class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border {{ request('status') === 'rejected' ? 'border-red-300' : 'border-gray-100' }} hover:shadow-lg hover:border-red-200 transition-all group block bg-gradient-to-br from-white to-red-50/30">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-ban"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Sản phẩm bị từ chối</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($stats['rejected'] ?? 0) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-red-50 flex items-center text-[10px] font-bold text-red-500">
                <i class="fa-solid fa-arrow-trend-down mr-1"></i> -2.7% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </a>
    </div>

    {{-- ========================================== --}}
    {{-- 2. THANH CÔNG CỤ (TÌM KIẾM & BỘ LỌC) --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row justify-between items-center gap-4 mb-6">
        <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full">
            
            {{-- Ô Search --}}
            <div class="relative w-full md:w-64 xl:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm sản phẩm, người bán..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all font-medium">
            </div>

            {{-- Lọc Danh mục --}}
            <div class="w-full md:w-auto">
                <select name="category_id" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2 outline-none font-medium cursor-pointer">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc Trạng thái --}}
            <div class="w-full md:w-auto">
                <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2 outline-none font-medium cursor-pointer">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đang bán</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    <option value="sold"     {{ request('status') === 'sold'     ? 'selected' : '' }}>Đã bán</option>
                    <option value="hidden"   {{ request('status') === 'hidden'   ? 'selected' : '' }}>Đã ẩn</option>
                </select>
            </div>

            {{-- Nút Tìm / Hủy Lọc --}}
            <div class="flex gap-2 w-full md:w-auto">
                <button type="submit" class="px-5 py-2 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-700 transition shadow-sm w-full md:w-auto">
                    Tìm
                </button>
                @if(request()->hasAny(['search', 'status', 'category_id']))
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 flex items-center justify-center whitespace-nowrap">
                        <i class="fa-solid fa-xmark mr-1"></i> Xóa lọc
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ========================================== --}}
    {{-- 3. BẢNG DANH SÁCH SẢN PHẨM --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-4 text-center w-12"><input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"></th>
                        <th class="p-4 font-black text-gray-400">#</th>
                        <th class="p-4">Sản phẩm</th>
                        <th class="p-4">Danh mục</th>
                        <th class="p-4">Người bán</th>
                        <th class="p-4">Giá</th>
                        <th class="p-4 text-center">Trạng thái</th>
                        <th class="p-4 text-center">Ngày đăng</th>
                        <th class="p-4 text-center">Lượt xem</th>
                        <th class="p-4 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($products as $index => $product)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="p-4 text-center"><input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"></td>
                        <td class="p-4 text-gray-400 font-bold">{{ $products->firstItem() + $index }}</td>
                        
                        {{-- Cột Sản Phẩm (Ảnh + Tên + ID) --}}
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
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="font-bold text-gray-900 hover:text-blue-600 transition-colors truncate block">
                                        {{ $product->title }}
                                    </a>
                                    <p class="text-[10px] text-gray-400 font-mono mt-0.5">SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                                    @if($product->pushed_until && $product->pushed_until > now())
                                        <span class="inline-flex items-center text-[9px] text-purple-600 bg-purple-50 px-1.5 py-0.5 rounded mt-1 font-bold border border-purple-100"><i class="fa-solid fa-arrow-up mr-1"></i>Đẩy tin</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Danh mục --}}
                        <td class="p-4 text-gray-600 font-medium">{{ $product->category->name ?? 'N/A' }}</td>

                        {{-- Người bán (Avatar + Tên) --}}
                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-gray-200 overflow-hidden shrink-0">
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

                        {{-- Giá --}}
                        <td class="p-4 font-black text-gray-900">{{ number_format($product->price) }}<span class="text-xs font-normal underline ml-0.5">đ</span></td>

                        {{-- Trạng thái --}}
                        <td class="p-4 text-center">
                            @php
                                $statusStyles = [
                                    'pending'  => ['bg-yellow-50 text-yellow-600 border-yellow-100', 'Chờ duyệt'],
                                    'approved' => ['bg-emerald-50 text-emerald-600 border-emerald-100', 'Đang bán'],
                                    'rejected' => ['bg-red-50 text-red-600 border-red-100', 'Bị từ chối'],
                                    'sold'     => ['bg-blue-50 text-blue-600 border-blue-100', 'Đã bán'],
                                    'hidden'   => ['bg-gray-100 text-gray-500 border-gray-200', 'Đã ẩn'],
                                ];
                                $style = $statusStyles[$product->status] ?? ['bg-gray-100 text-gray-600', $product->status];
                            @endphp
                            <span class="px-2.5 py-1 rounded-md text-[11px] font-bold border {{ $style[0] }} whitespace-nowrap">
                                {{ $style[1] }}
                            </span>
                            @if($product->status === 'rejected' && $product->rejection_reason)
                                <p class="text-[9px] text-red-400 mt-1 max-w-[100px] truncate mx-auto" title="{{ $product->rejection_reason }}"><i class="fa-solid fa-circle-exclamation"></i> Lỗi vi phạm</p>
                            @endif
                        </td>

                        {{-- Ngày đăng --}}
                        <td class="p-4 text-center text-gray-500 font-medium text-xs">
                            <p>{{ $product->created_at->format('d/m/Y') }}</p>
                            <p class="text-gray-400">{{ $product->created_at->format('H:i') }}</p>
                        </td>

                        {{-- Lượt xem --}}
                        <td class="p-4 text-center font-bold text-gray-600">
                            {{ number_format($product->view_count ?? 0) }}
                        </td>

                        {{-- Thao tác --}}
                        <td class="p-4">
                            <div class="flex items-center justify-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                {{-- Xem --}}
                                <a href="{{ route('admin.products.show', $product->id) }}" class="w-8 h-8 rounded-lg flex items-center justify-center bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                                {{-- Duyệt --}}
                                @if(in_array($product->status, ['pending', 'rejected']))
                                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" onclick="return confirm('Duyệt hiển thị tin đăng này?')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white transition-all" title="Duyệt bài">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                @endif

                                {{-- Đẩy tin --}}
                                @if($product->status === 'approved')
                                <button onclick="openPushModal({{ $product->id }}, '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-purple-50 text-purple-600 hover:bg-purple-500 hover:text-white transition-all" title="Đẩy tin lên đầu">
                                    <i class="fa-solid fa-arrow-up"></i>
                                </button>
                                @endif

                                {{-- Từ chối / Ẩn --}}
                                @if(in_array($product->status, ['pending', 'approved']))
                                <button onclick="openRejectModal({{ $product->id }}, '{{ addslashes($product->title) }}')" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-500 hover:text-white transition-all" title="Từ chối / Khóa tin">
                                    <i class="fa-solid fa-ban"></i>
                                </button>
                                @endif

                                {{-- Menu More (Xóa) --}}
                                <div x-data="{ open: false }" class="relative">
                                    <button @click="open = !open" @click.away="open = false" class="w-8 h-8 rounded-lg flex items-center justify-center hover:bg-gray-100 text-gray-500 transition-all">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <div x-show="open" style="display: none;" class="absolute right-0 mt-1 w-32 bg-white rounded-xl shadow-lg border border-gray-100 z-10 overflow-hidden">
                                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Xóa vĩnh viễn tin đăng này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium flex items-center gap-2">
                                                <i class="fa-solid fa-trash-can"></i> Xóa tin
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <i class="fa-solid fa-box-open text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-medium text-gray-500">Không tìm thấy sản phẩm nào khớp với bộ lọc.</p>
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
{{-- MODALS CÁC CHỨC NĂNG (GIAO DIỆN CHUẨN MỚI) --}}
{{-- ========================================== --}}

{{-- Modal: Từ chối tin --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-red-50/30 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-ban text-red-500"></i> Từ chối tin đăng</h3>
        </div>
        <form id="form-reject" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                <p class="text-[11px] text-gray-500 uppercase font-bold mb-1">Sản phẩm</p>
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
                    placeholder="Nhập lý do để người bán biết cách khắc phục..."
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
        </div>
        <form id="form-push" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <input type="hidden" name="days" id="push-days" value="3">
            
            <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-center mb-2">
                <p id="push-name" class="text-sm font-bold text-gray-900 truncate"></p>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 text-center">Thời gian duy trì TOP</label>
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
                <button type="button" onclick="closeModal('modal-push')" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy bỏ</button>
                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-purple-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Bắt đầu đẩy
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL XUẤT BÁO CÁO (Kế thừa từ file Admin Dashboard) --}}
{{-- ========================================== --}}
<div x-data="{ open: false }" @open-export-modal.window="open = true">
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col m-4">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-black text-xl text-gray-800 flex items-center gap-2"><i class="fa-solid fa-file-export text-blue-600"></i> Xuất Báo Cáo Sản Phẩm</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Từ ngày</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Đến ngày</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Dữ liệu xuất</label>
                    <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition appearance-none cursor-pointer">
                        <option>Tất cả sản phẩm (Toàn bộ)</option>
                        <option>Chỉ sản phẩm đang bán (Approved)</option>
                        <option>Sản phẩm chờ duyệt (Pending)</option>
                        <option>Sản phẩm vi phạm bị từ chối</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Định dạng file</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="format" value="excel" class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                            <span class="font-bold text-sm text-gray-700 flex items-center gap-2"><i class="fa-solid fa-file-excel text-green-600 text-lg"></i> Excel</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="format" value="pdf" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="font-bold text-sm text-gray-700 flex items-center gap-2"><i class="fa-solid fa-file-pdf text-red-500 text-lg"></i> PDF</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex gap-3">
                <button @click="open = false" class="w-1/3 py-3 font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-sm">Hủy</button>
                <button @click="open = false; alert('Đang tạo báo cáo danh sách sản phẩm. File sẽ tự động tải xuống khi hoàn tất...')" class="w-2/3 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition text-sm shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Tải xuống
                </button>
            </div>
        </div>
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
</script>
@endsection