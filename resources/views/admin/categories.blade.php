@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- ===== SIDEBAR ===== --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">

        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="transition-opacity duration-300 overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-green-500 font-medium">
                    {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                </span>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1">
            <a href="{{ route('products.index') }}"
                class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            <a href="{{ route('dashboard') }}"
                class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>

            <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-clipboard-list text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-wallet text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Ví 2HAND</span>

            <a href="{{ route('profile.edit') }}"
                class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quản trị hệ thống</p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Tổng quan Admin</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Tài khoản</span>
                </a>

                <a href="{{ route('admin.categories.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 font-semibold rounded-xl transition mt-1">
                    <i class="fa-solid fa-list text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Danh mục</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-box text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý sản phẩm</span>
                </a>

                <a href="{{ route('admin.wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-vault text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản trị tài chính</span>
                </a>
            </div>
            @endif
        </nav>
    </aside>

    {{-- ===== MAIN ===== --}}
    <main class="flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Quản Lý Danh Mục</h1>
            </div>
            <button onclick="openModal('modal-add-category')"
                class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition font-medium text-sm shadow-sm">
                + Thêm danh mục mới
            </button>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 font-medium">
                <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 font-medium">
                <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
            </div>
        @endif

        {{-- Search --}}
        <div class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex gap-2 w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm tên danh mục..."
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-xl hover:bg-gray-700 transition">Tìm</button>
                @if(request('search'))
                    <a href="{{ route('admin.categories.index') }}"
                        class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 flex items-center transition">Hủy</a>
                @endif
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase border-b border-gray-100">
                            <th class="p-4">STT</th>
                            <th class="p-4">Tên Danh Mục</th>
                            <th class="p-4">Danh Mục Cha</th>
                            <th class="p-4">Đường dẫn URL (Slug)</th>
                            <th class="p-4">Ngày tạo</th>
                            <th class="p-4 text-center">Hành Động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($categories as $index => $category)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-4 text-gray-500">{{ $categories->firstItem() + $index }}</td>
                            <td class="p-4 font-semibold text-gray-800">{{ $category->name }}</td>
                            <td class="p-4">
                                @if($category->parent)
                                    <span class="px-2.5 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-medium border border-slate-200/60">
                                        {{ $category->parent->name }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-xs font-medium border border-indigo-100/80">
                                        Danh mục gốc
                                    </span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                            <td class="p-4 text-gray-500">{{ $category->created_at ? $category->created_at->format('d/m/Y') : '---' }}</td>
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <button type="button" onclick="openEditModal({{ json_encode($category) }})"
                                        class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Tất cả danh mục con thuộc nó (nếu có) cũng sẽ bị ảnh hưởng.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-list text-2xl mb-2 block"></i>
                                Chưa có danh mục nào được tạo hoặc tìm thấy.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $categories->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

{{-- Modal Thêm danh mục --}}
<div id="modal-add-category" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Thêm Danh Mục Mới</h3>
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên Danh Mục</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Ví dụ: Điện thoại & Máy tính"
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50
                        {{ $errors->has('name') && !old('category_id') ? 'border-red-400' : '' }}">
                @if($errors->has('name') && !old('category_id'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thuộc Danh Mục Cha</label>
                <select name="parent_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 bg-white">
                    <option value="">-- Không chọn (Là danh mục gốc) --</option>
                    @foreach(\App\Models\Category::whereNull('parent_id')->get() as $parent)
                        <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-add-category')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm transition font-medium">Lưu lại</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa danh mục --}}
<div id="modal-edit-category" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Chỉnh Sửa Danh Mục</h3>
        <form id="form-edit-category" action="" method="POST">
            @csrf @method('PUT')
            <input type="hidden" id="edit-category-id" name="category_id" value="{{ old('category_id') }}">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tên Danh Mục</label>
                <input type="text" id="edit-category-name" name="name" value="{{ old('name') }}" required
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50
                        {{ $errors->has('name') && old('category_id') ? 'border-red-400' : '' }}">
                @if($errors->has('name') && old('category_id'))
                    <p class="text-red-500 text-xs mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1">Thuộc Danh Mục Cha</label>
                <select id="edit-category-parent-id" name="parent_id" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50 bg-white">
                    <option value="">-- Không chọn (Là danh mục gốc) --</option>
                    @foreach(\App\Models\Category::all() as $parent)
                        <option value="{{ $parent->id }}" class="edit-parent-option">{{ $parent->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-edit-category')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm transition font-medium">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(category) {
        document.getElementById('edit-category-id').value   = category.id;
        document.getElementById('edit-category-name').value = category.name;
        
        const parentSelect = document.getElementById('edit-category-parent-id');
        parentSelect.value = category.parent_id || '';

        // Logic thông minh chặn việc chọn chính danh mục đó làm cha của nó
        const options = parentSelect.querySelectorAll('.edit-parent-option');
        options.forEach(option => {
            if (parseInt(option.value) === category.id) {
                option.disabled = true;
                option.classList.add('bg-gray-100', 'text-gray-400');
            } else {
                option.disabled = false;
                option.classList.remove('bg-gray-100', 'text-gray-400');
            }
        });

        document.getElementById('form-edit-category').action = '/admin/categories/update/' + category.id;
        openModal('modal-edit-category');
    }

    // Tự động mở lại đúng modal khi có lỗi validation từ hệ thống trả về
    @if($errors->any())
        @if(old('category_id'))
            document.getElementById('form-edit-category').action = '/admin/categories/update/{{ old('category_id') }}';
            document.getElementById('edit-category-parent-id').value = '{{ old('parent_id') }}';
            
            const selectEl = document.getElementById('edit-category-parent-id');
            const opts = selectEl.querySelectorAll('.edit-parent-option');
            opts.forEach(opt => {
                if (parseInt(opt.value) === {{ old('category_id') }}) {
                    opt.disabled = true;
                    opt.classList.add('bg-gray-100', 'text-gray-400');
                }
            });
            openModal('modal-edit-category');
        @else
            openModal('modal-add-category');
        @endif
    @endif
</script>
@endsection