@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- ===== SIDEBAR ===== --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">

        {{-- Avatar & tên --}}
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

        {{-- Nav --}}
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
                class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">
                    Quản trị hệ thống
                </p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Tổng quan Admin</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 font-semibold rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Tài khoản</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-list text-lg w-6 shrink-0 text-center"></i>
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
                <h1 class="text-2xl font-bold text-gray-800">Quản Lý Danh Sách Tài Khoản</h1>
            </div>
            <button onclick="openModal('modal-add-user')"
                class="px-4 py-2 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition font-medium text-sm shadow-sm">
                + Thêm tài khoản mới
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
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-2 w-full md:w-1/2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm theo tên, email, số điện thoại..."
                    class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm text-gray-700 focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                <button type="submit"
                    class="px-4 py-2 bg-gray-800 text-white text-sm rounded-xl hover:bg-gray-700 transition">Tìm</button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}"
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
                            <th class="p-4">Họ và Tên</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Số điện thoại</th>
                            <th class="p-4">Địa chỉ</th>
                            <th class="p-4">Vai trò</th>
                            <th class="p-4">Trạng thái</th>
                            <th class="p-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-4 text-gray-500">{{ $users->firstItem() + $index }}</td>
                            <td class="p-4 font-semibold text-gray-800">{{ $user->name }}</td>
                            <td class="p-4 text-gray-600">{{ $user->email }}</td>
                            <td class="p-4 text-gray-500">{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                            <td class="p-4 text-gray-500">{{ $user->address ?? 'Chưa cập nhật' }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                    {{ $user->role === 'admin' ? 'bg-indigo-50 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $user->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                    {{ $user->status == 1 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                    {{ $user->status == 1 ? 'Hoạt động' : 'Bị khóa' }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-2">

                                    {{-- Khóa / Mở --}}
                                    <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn muốn thay đổi trạng thái?')">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg transition-all
                                                {{ $user->status == 1 ? 'text-yellow-500 hover:bg-yellow-50' : 'text-green-500 hover:bg-green-50' }}"
                                            title="{{ $user->status == 1 ? 'Khóa' : 'Mở khóa' }}">
                                            <i class="fa-solid {{ $user->status == 1 ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                        </button>
                                    </form>

                                    {{-- Sửa --}}
                                    <button type="button" onclick="openEditModal({{ $user }})"
                                        class="p-1.5 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all" title="Sửa">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    {{-- Xóa --}}
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn thực sự muốn xóa tài khoản này?')">
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
                            <td colspan="8" class="p-10 text-center text-gray-400 font-medium">
                                <i class="fa-solid fa-users-slash text-2xl mb-2 block"></i>
                                Không tìm thấy tài khoản nào phù hợp.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->appends(request()->query())->links() }}
            </div>
            @endif
        </div>

    </main>
</div>

{{-- ===== MODAL THÊM ===== --}}
<div id="modal-add-user" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Thêm Tài Khoản Mới</h3>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                    <input type="text" name="name" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                    <input type="text" name="phone"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                    <input type="text" name="address"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                        <select name="role"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            <option value="user">Thành viên</option>
                            <option value="admin">Quản trị viên</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            <option value="1">Hoạt động</option>
                            <option value="0">Khóa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-add-user')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm transition">Lưu dữ liệu</button>
            </div>
        </form>
    </div>
</div>

{{-- ===== MODAL SỬA ===== --}}
<div id="modal-edit-user" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Chỉnh Sửa Tài Khoản</h3>
        <form id="form-edit-user" action="" method="POST">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Họ và Tên</label>
                    <input type="text" id="edit-name" name="name" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="edit-email" name="email" required
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
                    <input type="text" id="edit-phone" name="phone"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Địa chỉ</label>
                    <input type="text" id="edit-address" name="address"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mật khẩu <span class="text-gray-400 font-normal">(bỏ trống nếu giữ nguyên)</span>
                    </label>
                    <input type="password" name="password" placeholder="••••••••"
                        class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò</label>
                        <select id="edit-role" name="role"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            <option value="user">Thành viên</option>
                            <option value="admin">Quản trị viên</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                        <select id="edit-status" name="status"
                            class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400">
                            <option value="1">Hoạt động</option>
                            <option value="0">Khóa</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeModal('modal-edit-user')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 text-sm transition">Cập nhật</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
    function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

    function openEditModal(user) {
        document.getElementById('edit-name').value    = user.name;
        document.getElementById('edit-email').value   = user.email;
        document.getElementById('edit-phone').value   = user.phone    || '';
        document.getElementById('edit-address').value = user.address  || '';
        document.getElementById('edit-role').value    = user.role;
        document.getElementById('edit-status').value  = user.status;
        document.getElementById('form-edit-user').action = '/admin/users/update/' + user.id;
        openModal('modal-edit-user');
    }
</script>
@endsection