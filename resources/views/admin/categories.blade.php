@extends('layouts.admin')

@section('title', 'Quản lý Danh mục - Admin')

{{-- Tùy chỉnh Header theo chuẩn thiết kế mới --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản lý danh mục
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1 flex items-center">
        {{-- Link về Trang chủ --}}
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        
        {{-- Trang hiện tại thì in đậm và không cần link --}}
        <span class="text-gray-900 font-bold">Danh mục</span>
    </p>
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
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-xmark"></i></div>
            {{ session('error') }}
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- 1. THỐNG KÊ TỔNG QUAN (3 CARDS) --}}
    {{-- ========================================== --}}
    @php
        // Tính toán nhanh số liệu danh mục
        $totalCats = \App\Models\Category::count();
        $rootCats = \App\Models\Category::whereNull('parent_id')->count();
        $subCats = $totalCats - $rootCats;
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
        {{-- Card 1: Tổng số danh mục --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tổng danh mục</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalCats) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>

        {{-- Card 2: Danh mục gốc --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Danh mục gốc (Cha)</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($rootCats) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                <i class="fa-solid fa-folder-tree"></i>
            </div>
        </div>

        {{-- Card 3: Danh mục con --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Danh mục con</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($subCats) }}</h3>
            </div>
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 group-hover:-rotate-6 transition-transform">
                <i class="fa-solid fa-code-branch"></i>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. THANH CÔNG CỤ (TÌM KIẾM & THÊM MỚI) --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
        
        {{-- Tìm kiếm Form --}}
        <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full md:w-auto">
            <div class="relative w-full md:w-80">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tên danh mục..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition-all font-medium">
            </div>
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <button type="submit" class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold rounded-xl transition-colors shadow-sm whitespace-nowrap">
                    Tìm
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 whitespace-nowrap">
                        <i class="fa-solid fa-xmark mr-1"></i> Xóa
                    </a>
                @endif
            </div>
        </form>

        {{-- Nhóm Phải: Nút Thêm mới --}}
        <div class="flex items-center w-full md:w-auto justify-end">
            <button onclick="openModal('modal-add-category')" class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200 flex items-center gap-2 transform hover:-translate-y-0.5 whitespace-nowrap">
                <i class="fa-solid fa-plus"></i> Thêm danh mục
            </button>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. BẢNG QUẢN LÝ DANH MỤC --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-5 font-black text-gray-400 w-16 text-center">#</th>
                        <th class="p-5">Tên Danh Mục</th>
                        <th class="p-5">Cấp Bậc (Phân loại)</th>
                        <th class="p-5">Đường dẫn (Slug)</th>
                        <th class="p-5">Ngày tạo</th>
                        <th class="p-5 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($categories as $index => $category)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="p-5 text-gray-400 font-bold text-center">{{ $categories->firstItem() + $index }}</td>
                        
                        {{-- Tên danh mục (Kèm Icon giả lập) --}}
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border shadow-sm {{ $category->parent_id ? 'bg-white border-gray-200 text-gray-400' : 'bg-blue-50 border-blue-100 text-blue-600' }}">
                                    <i class="fa-solid {{ $category->parent_id ? 'fa-tag' : 'fa-folder-open' }}"></i>
                                </div>
                                <span class="font-bold text-gray-900 text-base">{{ $category->name }}</span>
                            </div>
                        </td>

                        {{-- Cấp Bậc (Cha/Con) --}}
                        <td class="p-5">
                            @if($category->parent)
                                <div class="flex items-center gap-2">
                                    <i class="fa-solid fa-turn-up text-gray-300 rotate-90"></i>
                                    <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-bold border border-gray-200">
                                        Thuộc: {{ $category->parent->name }}
                                    </span>
                                </div>
                            @else
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-md text-xs font-bold border border-purple-100">
                                    <i class="fa-solid fa-crown mr-1"></i> Danh mục gốc
                                </span>
                            @endif
                        </td>

                        {{-- Slug --}}
                        <td class="p-5">
                            <span class="font-mono text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $category->slug }}</span>
                        </td>

                        {{-- Ngày tạo --}}
                        <td class="p-5 text-gray-500 font-medium text-xs">
                            {{ $category->created_at ? $category->created_at->format('d/m/Y') : '---' }}
                        </td>

                        {{-- Thao tác --}}
                        <td class="p-5">
                            <div class="flex items-center justify-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                {{-- Nút Sửa --}}
                                <button type="button" onclick="openEditModal({{ json_encode($category) }})" class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Sửa danh mục">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                {{-- Nút Xóa --}}
                                <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('CẢNH BÁO: Xóa danh mục này có thể ảnh hưởng đến các sản phẩm và danh mục con thuộc về nó. Bạn chắc chắn chứ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Xóa danh mục">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <i class="fa-solid fa-folder-open text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-medium text-gray-500">Chưa có danh mục nào được tạo hoặc tìm thấy.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang --}}
        @if($categories->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <p>Hiển thị từ <span class="font-bold text-gray-900">{{ $categories->firstItem() }}</span> đến <span class="font-bold text-gray-900">{{ $categories->lastItem() }}</span></p>
            {{ $categories->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- MODALS THÊM / SỬA CHUẨN UI --}}
{{-- ========================================== --}}

{{-- Modal Thêm Danh Mục --}}
<div id="modal-add-category" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-layer-group text-blue-600"></i> Thêm Danh Mục Mới</h3>
        </div>
        <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tên Danh Mục <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Đồ điện tử..." 
                    class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all {{ $errors->has('name') && !old('category_id') ? 'border-red-400' : '' }}">
                @if($errors->has('name') && !old('category_id'))
                    <p class="text-red-500 text-xs mt-1 font-bold"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cấp bậc (Thuộc danh mục nào?)</label>
                <div class="relative">
                    <select name="parent_id" class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all cursor-pointer appearance-none">
                        <option value="" class="font-bold">👑 Không chọn (Tạo danh mục gốc)</option>
                        <optgroup label="Chọn làm danh mục con của:">
                            @foreach(\App\Models\Category::whereNull('parent_id')->get() as $parent)
                                <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>↳ {{ $parent->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-angle-down"></i></div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-add-category')" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-blue-200">Lưu danh mục</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa Danh Mục --}}
<div id="modal-edit-category" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-indigo-600"></i> Chỉnh Sửa Danh Mục</h3>
        </div>
        <form id="form-edit-category" action="" method="POST" class="p-6 space-y-5">
            @csrf @method('PUT')
            <input type="hidden" id="edit-category-id" name="category_id" value="{{ old('category_id') }}">
            
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Tên Danh Mục <span class="text-red-500">*</span></label>
                <input type="text" id="edit-category-name" name="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all {{ $errors->has('name') && old('category_id') ? 'border-red-400' : '' }}">
                @if($errors->has('name') && old('category_id'))
                    <p class="text-red-500 text-xs mt-1 font-bold"><i class="fa-solid fa-circle-exclamation"></i> {{ $errors->first('name') }}</p>
                @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Cấp bậc (Thuộc danh mục nào?)</label>
                <div class="relative">
                    <select id="edit-category-parent-id" name="parent_id" class="w-full px-4 py-3 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all cursor-pointer appearance-none">
                        <option value="" class="font-bold">👑 Trở thành Danh mục gốc</option>
                        <optgroup label="Đổi thành danh mục con của:">
                            @foreach(\App\Models\Category::all() as $parent)
                                <option value="{{ $parent->id }}" class="edit-parent-option">↳ {{ $parent->name }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400"><i class="fa-solid fa-angle-down"></i></div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-edit-category')" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-indigo-200">Cập nhật thay đổi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Logic bật tắt Modal cực mượt, có khóa cuộn trang
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto';
    }

    // Logic đổ dữ liệu Sửa
    function openEditModal(category) {
        document.getElementById('edit-category-id').value   = category.id;
        document.getElementById('edit-category-name').value = category.name;
        
        const parentSelect = document.getElementById('edit-category-parent-id');
        parentSelect.value = category.parent_id || '';

        // Tắt (Disable) lựa chọn chính nó làm cha của nó để tránh lỗi logic đệ quy
        const options = parentSelect.querySelectorAll('.edit-parent-option');
        options.forEach(option => {
            if (parseInt(option.value) === category.id) {
                option.disabled = true;
                option.classList.add('bg-gray-200', 'text-gray-400');
                option.innerHTML = `↳ ${category.name} (Không thể chọn chính nó)`;
            } else {
                option.disabled = false;
                option.classList.remove('bg-gray-200', 'text-gray-400');
                // Trả lại tên gốc nếu trước đó bị disable
                if(option.innerHTML.includes('(Không thể chọn chính nó)')) {
                    option.innerHTML = option.innerHTML.replace(' (Không thể chọn chính nó)', '');
                }
            }
        });

        document.getElementById('form-edit-category').action = '/admin/categories/update/' + category.id;
        openModal('modal-edit-category');
    }

    // Tự động mở lại modal nếu có lỗi Validate từ Server (Tránh khách phải bấm lại)
    @if($errors->any())
        @if(old('category_id'))
            document.getElementById('form-edit-category').action = '/admin/categories/update/{{ old('category_id') }}';
            document.getElementById('edit-category-parent-id').value = '{{ old('parent_id') }}';
            
            const selectEl = document.getElementById('edit-category-parent-id');
            const opts = selectEl.querySelectorAll('.edit-parent-option');
            opts.forEach(opt => {
                if (parseInt(opt.value) === {{ old('category_id') }}) {
                    opt.disabled = true;
                    opt.classList.add('bg-gray-200', 'text-gray-400');
                }
            });
            openModal('modal-edit-category');
        @else
            openModal('modal-add-category');
        @endif
    @endif
</script>
@endsection