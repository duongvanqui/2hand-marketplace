@extends('layouts.admin')

@section('title', 'Chi tiết tin đăng - Admin')

{{-- Tùy chỉnh Header: Gắn nút Back và Thanh điều hướng (Breadcrumb) --}}
@section('header_title')
<div class="flex flex-col">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.products.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all shadow-sm group">
            <i class="fa-solid fa-arrow-left transition-transform group-hover:-translate-x-0.5"></i>
        </a>
        <span class="text-2xl font-black text-gray-900 tracking-tight">Chi tiết tin đăng</span>
    </div>
    
    {{-- Thanh Breadcrumb mờ mờ chuẩn style --}}
    <div class="text-sm text-gray-500 font-medium mt-1.5 flex items-center gap-2 pl-[3.25rem]">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-0.5"></i>
        <a href="{{ route('admin.products.index') }}" class="hover:text-emerald-600 transition-colors">Quản lý Sản phẩm</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-0.5"></i>
        <span class="text-gray-900 font-bold">#SP{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</span>
    </div>
</div>
@endsection

{{-- Nút Hành động nhanh trên Header --}}
@section('header_actions')
<div class="flex items-center gap-3">
    @if(in_array($product->status, ['pending', 'rejected']))
    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
        @csrf @method('PATCH')
        <button type="submit" onclick="return confirm('Bạn xác nhận nội dung tin này hợp lệ và cho phép hiển thị?')"
            class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 text-sm font-bold transition-all shadow-lg shadow-green-200 flex items-center gap-2 transform hover:-translate-y-0.5">
            <i class="fa-solid fa-circle-check"></i> Duyệt tin ngay
        </button>
    </form>
    @endif
    
    @if(in_array($product->status, ['pending', 'approved']))
    <button onclick="openModal('modal-reject')"
        class="px-5 py-2.5 bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 text-sm font-bold transition-all shadow-sm flex items-center gap-2">
        <i class="fa-solid fa-ban"></i> Từ chối
    </button>
    @endif
</div>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        {{-- ========================================== --}}
        {{-- CỘT TRÁI: Nội dung tin đăng (Chiếm 2/3) --}}
        {{-- ========================================== --}}
        <div class="xl:col-span-2 space-y-6">

            {{-- Khối 1: Header Sản phẩm & Thông số --}}
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6 md:p-8">
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                    <h2 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight flex-1">{{ $product->title }}</h2>
                    
                    @php
                        $statusStyles = [
                            'pending'  => ['bg-yellow-50 text-yellow-600 border-yellow-200', 'Chờ duyệt', 'fa-clock'],
                            'approved' => ['bg-emerald-50 text-emerald-600 border-emerald-200', 'Đang hiển thị', 'fa-circle-check'],
                            'rejected' => ['bg-red-50 text-red-600 border-red-200', 'Bị từ chối', 'fa-circle-xmark'],
                            'sold'     => ['bg-blue-50 text-blue-600 border-blue-200', 'Đã bán', 'fa-bag-shopping'],
                            'hidden'   => ['bg-gray-100 text-gray-600 border-gray-200', 'Đã ẩn', 'fa-eye-slash'],
                        ];
                        $style = $statusStyles[$product->status] ?? ['bg-gray-100 text-gray-600 border-gray-200', $product->status, 'fa-circle-info'];
                    @endphp
                    <span class="px-4 py-2 rounded-xl text-sm font-bold border flex items-center gap-2 whitespace-nowrap h-fit {{ $style[0] }}">
                        <i class="fa-solid {{ $style[2] }}"></i> {{ $style[1] }}
                    </span>
                </div>

                <div class="flex items-end gap-3 mb-8 pb-8 border-b border-gray-50">
                    <span class="text-4xl font-black text-red-500 tracking-tight">{{ number_format($product->price) }}<span class="text-2xl underline ml-1">đ</span></span>
                    @if($product->original_price && $product->original_price > $product->price)
                        <span class="text-lg text-gray-400 line-through mb-1">{{ number_format($product->original_price) }}đ</span>
                        <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-1 rounded-lg mb-1.5 ml-2 border border-red-100">
                            -{{ round((($product->original_price - $product->price) / $product->original_price) * 100) }}%
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div class="flex flex-col p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-layer-group text-blue-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Danh mục</p>
                        <p class="font-bold text-gray-900 truncate" title="{{ $product->category->name ?? 'N/A' }}">{{ $product->category->name ?? 'N/A' }}</p>
                    </div>
                    <div class="flex flex-col p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-location-dot text-red-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Khu vực</p>
                        <p class="font-bold text-gray-900 truncate" title="{{ $product->location ?? 'Chưa cập nhật' }}">{{ $product->location ?? 'Chưa cập nhật' }}</p>
                    </div>
                    <div class="flex flex-col p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-sparkles text-orange-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Tình trạng</p>
                        <p class="font-bold text-gray-900">Mới {{ $product->condition_pct }}%</p>
                    </div>
                    <div class="flex flex-col p-4 bg-gray-50/80 rounded-2xl border border-gray-100">
                        <i class="fa-solid fa-eye text-emerald-500 text-xl mb-2"></i>
                        <p class="text-xs text-gray-400 font-bold uppercase mb-0.5">Lượt xem</p>
                        <p class="font-bold text-gray-900">{{ number_format($product->view_count) }} lượt</p>
                    </div>
                </div>
            </div>

            {{-- Khối 2: Hình ảnh sản phẩm --}}
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6 md:p-8">
                <h3 class="text-base font-black text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fa-regular fa-images text-blue-500"></i> Thư viện hình ảnh
                </h3>
                
                @if($product->images && $product->images->count() > 0)
                    {{-- Grid hiển thị ảnh to/nhỏ --}}
                    <div class="relative w-full aspect-[4/3] md:aspect-[21/9] bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 flex items-center justify-center mb-3">
                        <img id="main-display" src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-contain">
                        
                        @if($product->images->count() > 1)
                        <button onclick="prevImg()" class="absolute left-3 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:text-emerald-600 hover:bg-emerald-50 transition outline-none w-10 h-10">
                            <i class="fa-solid fa-chevron-left"></i>
                        </button>
                        <button onclick="nextImg()" class="absolute right-3 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:text-emerald-600 hover:bg-emerald-50 transition outline-none w-10 h-10">
                            <i class="fa-solid fa-chevron-right"></i>
                        </button>
                        @endif
                    </div>

                    <div class="flex justify-start gap-2 overflow-x-auto py-1 no-scrollbar">
                        @foreach($product->images as $index => $img)
                            <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 cursor-pointer transition-all thumb-item {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-100 opacity-100' : 'border-transparent opacity-50 hover:opacity-100' }}"
                                onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this, {{ $index }})">
                                <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 flex flex-col items-center justify-center text-gray-400 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-3">
                            <i class="fa-solid fa-image-slash text-2xl"></i>
                        </div>
                        <p class="font-medium text-sm">Tin đăng này không có hình ảnh minh họa</p>
                    </div>
                @endif
            </div>

            {{-- Khối 3: Mô tả chi tiết --}}
            @if($product->description)
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6 md:p-8">
                <h3 class="text-base font-black text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-align-left text-blue-500"></i> Mô tả chi tiết
                </h3>
                <div class="p-5 bg-yellow-50/30 rounded-2xl border border-yellow-100/50">
                    <p class="text-sm text-gray-700 leading-loose whitespace-pre-line">{{ $product->description }}</p>
                </div>
            </div>
            @endif

            {{-- Khối 4: Thông số kỹ thuật --}}
            @if($product->specifications)
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6 md:p-8">
                <h3 class="text-base font-black text-gray-900 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-microchip text-emerald-500"></i> Thông số kỹ thuật
                </h3>
                <div class="p-5 bg-emerald-50/30 rounded-2xl border border-emerald-100/50">
                    <p class="text-sm text-gray-700 leading-loose whitespace-pre-line">{{ $product->specifications }}</p>
                </div>
            </div>
            @endif

        </div>

        {{-- ========================================== --}}
        {{-- CỘT PHẢI: Bảng điều khiển hệ thống (Chiếm 1/3) --}}
        {{-- ========================================== --}}
        <div class="space-y-6">

            {{-- 1. BẢNG ĐIỀU KHIỂN HÀNH ĐỘNG (Trạm kiểm soát) --}}
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest text-center">Bảng điều khiển</h3>
                
                <div class="space-y-3">
                    {{-- Nút Duyệt --}}
                    @if(in_array($product->status, ['pending', 'rejected']))
                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Duyệt hiển thị tin này?')"
                            class="w-full py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl hover:from-emerald-600 hover:to-green-700 text-sm font-bold transition-all shadow-md shadow-green-200 transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-check-double"></i> Duyệt hiển thị tin
                        </button>
                    </form>
                    @endif

                    {{-- Nút Đẩy tin --}}
                    @if($product->status === 'approved')
                    <button onclick="openModal('modal-push')"
                        class="w-full py-3 bg-purple-50 text-purple-700 border border-purple-200 rounded-xl hover:bg-purple-100 text-sm font-bold transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-up-right-dots"></i> Đẩy tin lên đầu (Top)
                    </button>
                    @endif

                    {{-- Nhóm Nút Phụ --}}
                    <div class="grid grid-cols-2 gap-3 pt-2">
                        {{-- Từ chối --}}
                        @if(in_array($product->status, ['pending', 'approved']))
                        <button onclick="openModal('modal-reject')"
                            class="w-full py-2.5 bg-red-50 text-red-600 border border-red-100 rounded-xl hover:bg-red-100 hover:border-red-200 text-sm font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-ban"></i> Từ chối
                        </button>
                        @endif

                        {{-- Ẩn/Hiện --}}
                        @if(in_array($product->status, ['approved', 'hidden']))
                        <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST" class="w-full">
                            @csrf @method('PATCH')
                            <button type="submit"
                                class="w-full py-2.5 bg-gray-50 text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-100 text-sm font-bold transition-all flex items-center justify-center gap-2">
                                <i class="fa-solid {{ $product->status === 'approved' ? 'fa-eye-slash text-yellow-500' : 'fa-eye text-blue-500' }}"></i>
                                {{ $product->status === 'approved' ? 'Ẩn tin' : 'Hiện tin' }}
                            </button>
                        </form>
                        @endif
                    </div>

                    {{-- Xóa tin --}}
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('CẢNH BÁO MỨC ĐỎ: Hành động này sẽ xóa vĩnh viễn tin đăng và toàn bộ ảnh khỏi hệ thống. Bạn chắc chắn chứ?')" class="pt-4 border-t border-gray-50 mt-4">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-white text-red-500 border border-red-100 rounded-xl hover:bg-red-50 text-sm font-bold transition-all flex items-center justify-center gap-2">
                            <i class="fa-solid fa-trash-can"></i> Xóa vĩnh viễn
                        </button>
                    </form>
                </div>
            </div>

            {{-- 2. THÔNG TIN NGƯỜI ĐĂNG --}}
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 mb-5 uppercase tracking-widest text-center">Người đăng tin</h3>
                
                <div class="flex flex-col items-center mb-6">
                    <div class="w-16 h-16 rounded-full border-4 border-gray-50 shadow-sm overflow-hidden bg-blue-50 flex items-center justify-center text-blue-600 font-black text-xl mb-3">
                        @if($product->user && $product->user->avatar)
                            <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($product->user->name ?? 'U', 0, 1) }}
                        @endif
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ $product->user->name ?? 'Tài khoản không tồn tại' }}</p>
                    <p class="text-xs text-gray-500 bg-gray-50 px-3 py-1 rounded-full mt-2 border border-gray-100">{{ $product->user->email ?? 'N/A' }}</p>
                </div>
                
                <div class="space-y-3 text-sm text-gray-600 p-4 bg-gray-50/50 rounded-2xl border border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400 font-medium">Tạo lúc:</span>
                        <span class="font-bold text-gray-800">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center border-t border-gray-100/60 pt-3">
                        <span class="text-gray-400 font-medium">Cập nhật:</span>
                        <span class="font-bold text-gray-800">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                    </div>
                    @if(isset($product->resubmit_count) && $product->resubmit_count > 0)
                    <div class="flex justify-between items-center border-t border-gray-100/60 pt-3">
                        <span class="text-gray-400 font-medium">Lượt gửi lại:</span>
                        <span class="font-black text-orange-500 bg-orange-50 px-2 py-0.5 rounded">{{ $product->resubmit_count }} lần</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- 3. LỊCH SỬ KIỂM DUYỆT --}}
            @if($product->reviewed_at)
            <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 p-6">
                <h3 class="text-xs font-bold text-gray-400 mb-5 uppercase tracking-widest text-center">Lịch sử kiểm duyệt</h3>
                
                <div class="relative border-l-2 border-gray-100 ml-3 space-y-6">
                    <div class="relative pl-5">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full border-2 border-white {{ $product->status === 'approved' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                        <p class="text-sm font-bold text-gray-900 mb-0.5">Đã xử lý tin</p>
                        <p class="text-xs text-gray-500 mb-2">{{ $product->reviewed_at->format('H:i - d/m/Y') }}</p>
                        
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100 text-xs text-gray-600 space-y-1.5">
                            <p><span class="text-gray-400">Người duyệt:</span> <span class="font-bold text-gray-800">{{ $product->reviewer->name ?? 'Admin hệ thống' }}</span></p>
                            <p><span class="text-gray-400">Kết quả:</span> <span class="font-bold {{ $product->status === 'approved' ? 'text-green-600' : 'text-red-600' }}">{{ $product->status === 'approved' ? 'Đã duyệt hiển thị' : 'Đã từ chối/Khóa' }}</span></p>
                        </div>
                    </div>
                </div>

                @if($product->rejection_reason)
                <div class="mt-4 p-4 bg-red-50 rounded-2xl border border-red-100 relative overflow-hidden">
                    <div class="absolute right-[-10px] bottom-[-10px] text-red-500/10 text-6xl">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <p class="text-[11px] font-black text-red-600 mb-1 uppercase tracking-wide">Lý do từ chối:</p>
                    <p class="text-sm text-red-800 font-medium leading-relaxed relative z-10">{{ $product->rejection_reason }}</p>
                </div>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- MODALS TƯƠNG TÁC --}}
{{-- ========================================== --}}

{{-- 1. Modal: Từ chối tin --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-red-50/30 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-ban text-red-500"></i> Từ chối tin đăng</h3>
            <button onclick="closeModal('modal-reject')" class="text-gray-400 hover:text-gray-700 transition outline-none"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Chọn mẫu lý do</label>
                <select class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-red-400 transition-all cursor-pointer appearance-none"
                    onchange="if(this.value) document.getElementById('rejection_reason').value = this.value">
                    <option value="">-- Chọn lỗi vi phạm thường gặp --</option>
                    <option value="Ảnh sản phẩm không rõ nét hoặc thiếu ảnh.">Ảnh không rõ nét / thiếu ảnh</option>
                    <option value="Mô tả sản phẩm không đầy đủ hoặc không chính xác.">Mô tả không đầy đủ</option>
                    <option value="Giá bán không hợp lý so với thực tế.">Giá không hợp lý</option>
                    <option value="Nội dung vi phạm quy định của sàn.">Vi phạm quy định</option>
                    <option value="Danh mục không phù hợp với sản phẩm.">Sai danh mục</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Lý do chi tiết <span class="text-red-500">*</span></label>
                <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                    placeholder="Nhập lý do chính xác để người bán biết đường sửa..."
                    class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-red-400 focus:ring-4 focus:ring-red-50 transition-all resize-none">{{ $product->rejection_reason ?? '' }}</textarea>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-reject')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy bỏ</button>
                <button type="submit" class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-red-200">Xác nhận Từ chối</button>
            </div>
        </form>
    </div>
</div>

{{-- 2. Modal: Đẩy tin --}}
<div id="modal-push" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-sm w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-purple-50/30 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-arrow-up text-purple-600"></i> Cấu hình Đẩy tin</h3>
            <button onclick="closeModal('modal-push')" class="text-gray-400 hover:text-gray-700 transition outline-none"><i class="fa-solid fa-xmark text-xl"></i></button>
        </div>
        <form action="{{ route('admin.products.push', $product->id) }}" method="POST" class="p-6 space-y-4">
            @csrf @method('PATCH')
            <input type="hidden" name="days" id="push-days" value="3">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3 text-center">Thời gian hiển thị TOP</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([1 => '1 ngày', 3 => '3 ngày', 7 => '7 ngày'] as $d => $label)
                    <button type="button" onclick="selectDays({{ $d }}, this)"
                        class="push-day-btn py-3 border-2 rounded-xl text-sm font-bold transition-all {{ $d === 3 ? 'border-purple-500 bg-purple-50 text-purple-700 shadow-sm' : 'border-gray-100 text-gray-500 hover:border-purple-200 hover:bg-purple-50' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            @if($product->pushed_until && \Carbon\Carbon::parse($product->pushed_until)->isFuture())
            <div class="bg-purple-50 border border-purple-100 p-3 rounded-xl flex items-start gap-2 mt-4">
                <i class="fa-solid fa-circle-info text-purple-500 mt-0.5"></i>
                <div>
                    <p class="text-xs font-bold text-purple-700">Tin đang được đẩy!</p>
                    <p class="text-[11px] text-purple-600 mt-0.5">Sẽ hết hạn vào lúc: {{ \Carbon\Carbon::parse($product->pushed_until)->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            @endif

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-push')" class="w-full py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="w-full py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-purple-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-rocket"></i> Bắt đầu Đẩy
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Bật/tắt Modal mượt mà
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto';
    }

    function selectDays(days, btn) {
        document.getElementById('push-days').value = days;
        document.querySelectorAll('.push-day-btn').forEach(b => {
            b.classList.remove('border-purple-500', 'bg-purple-50', 'text-purple-700', 'shadow-sm');
            b.classList.add('border-gray-100', 'text-gray-500');
        });
        btn.classList.remove('border-gray-100', 'text-gray-500');
        btn.classList.add('border-purple-500', 'bg-purple-50', 'text-purple-700', 'shadow-sm');
    }

    // JS CHUYỂN ẢNH SẢN PHẨM
    const images = [
        @if($product->images && $product->images->count() > 0)
            @foreach($product->images as $img)
            "{{ asset('storage/' . $img->image_path) }}",
            @endforeach
        @endif
    ];
    let currentIndex = 0;

    function changeImage(src, element, index) {
        currentIndex = index;
        document.getElementById('main-display').src = src;
        
        document.querySelectorAll('.thumb-item').forEach(el => {
            el.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-100', 'opacity-100');
            el.classList.add('border-transparent', 'opacity-50');
        });
        
        if (element) {
            element.classList.remove('border-transparent', 'opacity-50');
            element.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-100', 'opacity-100');
        }
    }

    function prevImg() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
        updateSlider();
    }

    function nextImg() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
        updateSlider();
    }

    function updateSlider() {
        const thumbElements = document.querySelectorAll('.thumb-item');
        if (thumbElements.length > 0) {
            changeImage(images[currentIndex], thumbElements[currentIndex], currentIndex);
        }
    }
</script>
@endsection