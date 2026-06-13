@extends('layouts.admin')

@section('title', 'Quản lý Tài khoản - Admin')

{{-- Tùy chỉnh Header theo chuẩn thiết kế mới --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Danh sách người dùng
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1">Trang chủ <i class="fa-solid fa-angle-right text-[10px] mx-1"></i> Quản lý người dùng</p>
</div>
@endsection

@section('content')
<div class="pb-10">

@section('header_actions')
<button onclick="window.dispatchEvent(new CustomEvent('open-export-modal'))" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection
    
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
    {{-- 1. THỐNG KÊ NGƯỜI DÙNG (4 CARDS) --}}
    {{-- ========================================== --}}
    @php
        // Giả lập số liệu thống kê (Bạn có thể Query từ DB thực tế sau)
        $totalUsersCount = $users->total();
        $activeUsers = $users->filter(fn($u) => $u->status == 1)->count();
        $bannedUsers = $users->filter(fn($u) => $u->status == 0)->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">
        {{-- Card: Tổng người dùng --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Tổng người dùng</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($totalUsersCount) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +18.2% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Đang hoạt động --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-check"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Đang hoạt động</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($activeUsers) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +15.7% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Người dùng mới --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide">Đăng ký mới (7 ngày)</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">245</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-yellow-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +12.3% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Bị khóa --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-red-100 hover:shadow-lg transition-all group bg-gradient-to-br from-white to-red-50/50">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-user-lock"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-red-500 uppercase tracking-wide">Tài khoản bị khóa</p>
                    <h3 class="text-2xl font-black text-gray-900 leading-tight">{{ number_format($bannedUsers) }}</h3>
                </div>
            </div>
            <div class="pt-3 border-t border-red-100 flex items-center text-[10px] font-bold text-red-500">
                <i class="fa-solid fa-arrow-trend-down mr-1"></i> -8.1% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. THANH CÔNG CỤ (TÌM KIẾM & BỘ LỌC) --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row justify-between items-center gap-4 mb-6">
        
        {{-- Tìm kiếm & Lọc Form --}}
        <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full xl:w-auto">
            
            {{-- Ô Search --}}
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm theo tên, email, SĐT..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all font-medium">
            </div>

            {{-- Filter Vai trò --}}
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-600 hidden sm:block">Vai trò</span>
                <select name="role" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2 outline-none font-medium cursor-pointer">
                    <option value="">Tất cả vai trò</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Người dùng</option>
                </select>
            </div>

            {{-- Filter Trạng thái --}}
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold text-gray-600 hidden sm:block">Trạng thái</span>
                <select name="status" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-2 outline-none font-medium cursor-pointer">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Bị khóa</option>
                </select>
            </div>

            <button type="submit" class="hidden"></button> {{-- Ẩn nút submit, tự động chạy khi nhấn Enter ở ô input --}}
        </form>

        {{-- Nút Chức năng phải --}}
        <div class="flex items-center gap-3 w-full xl:w-auto">
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100">
                    <i class="fa-solid fa-xmark mr-1"></i> Xóa lọc
                </a>
            @endif
            <button class="px-4 py-2 bg-white border border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 transition shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-filter"></i> Lọc nâng cao
            </button>
            <button onclick="openModal('modal-add-user')" class="px-5 py-2 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-md shadow-blue-200 flex items-center gap-2 transform hover:-translate-y-0.5">
                <i class="fa-solid fa-user-plus"></i> Thêm mới
            </button>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. BẢNG DANH SÁCH NGƯỜI DÙNG --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-5 font-black text-gray-400">#</th>
                        <th class="p-5">Người dùng</th>
                        <th class="p-5">Email & Số điện thoại</th>
                        <th class="p-5 text-center">Vai trò</th>
                        <th class="p-5 text-center">Trạng thái</th>
                        <th class="p-5">Ngày đăng ký</th>
                        <th class="p-5 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($users as $index => $user)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="p-5 text-gray-400 font-bold">{{ $users->firstItem() + $index }}</td>
                        
                        {{-- Cột 1: Thông tin User (Avatar + Tên) --}}
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full border border-gray-200 overflow-hidden shrink-0 shadow-sm">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-600 font-black text-sm uppercase">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium">ID: {{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Cột 2: Liên hệ --}}
                        <td class="p-5">
                            <p class="text-gray-700 font-medium mb-0.5"><i class="fa-regular fa-envelope text-gray-400 mr-1"></i> {{ $user->email }}</p>
                            <p class="text-gray-500 text-xs"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> {{ $user->phone ?? 'Chưa cập nhật' }}</p>
                        </td>

                        {{-- Cột 3: Vai trò --}}
                        <td class="p-5 text-center">
                            @if($user->role === 'admin')
                                <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-md text-xs font-bold border border-purple-100">Admin</span>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-md text-xs font-bold border border-gray-200">Người dùng</span>
                            @endif
                        </td>

                        {{-- Cột 4: Trạng thái --}}
                        <td class="p-5 text-center">
                            @if($user->status == 1)
                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-md text-xs font-bold border border-emerald-100"><i class="fa-solid fa-circle text-[8px] mr-1"></i> Hoạt động</span>
                            @else
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-md text-xs font-bold border border-red-100"><i class="fa-solid fa-lock text-[10px] mr-1"></i> Bị khóa</span>
                            @endif
                        </td>

                        {{-- Cột 5: Ngày đăng ký --}}
                        <td class="p-5 text-gray-500 font-medium text-xs">
                            <p>{{ $user->created_at->format('d/m/Y') }}</p>
                            <p class="text-gray-400">{{ $user->created_at->format('H:i') }}</p>
                        </td>

                        {{-- Cột 6: Thao tác --}}
                        <td class="p-5">
                            <div class="flex items-center justify-center space-x-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                {{-- Nút Khóa / Mở --}}
                                <form action="{{ route('admin.users.toggle-status', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Xác nhận thay đổi trạng thái tài khoản này?')">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center transition-all shadow-sm {{ $user->status == 1 ? 'bg-yellow-50 text-yellow-600 hover:bg-yellow-500 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white' }}" title="{{ $user->status == 1 ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}">
                                        <i class="fa-solid {{ $user->status == 1 ? 'fa-lock' : 'fa-lock-open' }}"></i>
                                    </button>
                                </form>

                                {{-- Nút Sửa --}}
                                <button type="button" onclick="openEditModal({{ $user }})" class="w-8 h-8 rounded-lg flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Sửa thông tin">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </button>

                                {{-- Nút Xóa --}}
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('CẢNH BÁO: Xóa tài khoản sẽ xóa toàn bộ dữ liệu liên quan. Vẫn muốn tiếp tục?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-8 h-8 rounded-lg flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Xóa vĩnh viễn">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <i class="fa-solid fa-users-slash text-3xl text-gray-300"></i>
                            </div>
                            <p class="font-medium text-gray-500">Không tìm thấy tài khoản nào khớp với bộ lọc.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Phân trang (Pagination) --}}
        @if($users->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <p>Hiển thị từ <span class="font-bold text-gray-900">{{ $users->firstItem() }}</span> đến <span class="font-bold text-gray-900">{{ $users->lastItem() }}</span> trong tổng số <span class="font-bold text-gray-900">{{ $users->total() }}</span> người dùng</p>
            {{ $users->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- MODALS KẾ THỪA CHUẨN UI --}}
{{-- ========================================== --}}

{{-- Modal Thêm User --}}
<div id="modal-add-user" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-user-plus text-blue-600"></i> Thêm Tài Khoản Mới</h3>
        </div>
        <form action="{{ route('admin.users.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Họ và Tên <span class="text-red-500">*</span></label>
                <input type="text" name="name" required placeholder="Nguyễn Văn A" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email <span class="text-red-500">*</span></label>
                <input type="email" name="email" required placeholder="email@example.com" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Số điện thoại</label>
                    <input type="text" name="phone" placeholder="090..." class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Mật khẩu <span class="text-red-500">*</span></label>
                    <input type="password" name="password" required placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Địa chỉ</label>
                <input type="text" name="address" placeholder="Thành phố, Quốc gia..." class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Vai trò</label>
                    <select name="role" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 transition-all cursor-pointer appearance-none">
                        <option value="user">Người dùng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Trạng thái</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-blue-400 transition-all cursor-pointer appearance-none">
                        <option value="1">Hoạt động</option>
                        <option value="0">Bị khóa</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-add-user')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-blue-200">Tạo tài khoản</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Sửa User --}}
<div id="modal-edit-user" class="fixed inset-0 z-50 hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden flex flex-col transform transition-all">
        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-xl font-black text-gray-800 flex items-center gap-2"><i class="fa-solid fa-pen-to-square text-indigo-600"></i> Cập Nhật Tài Khoản</h3>
        </div>
        <form id="form-edit-user" action="" method="POST" class="p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Họ và Tên</label>
                <input type="text" id="edit-name" name="name" required class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Email</label>
                    <input type="email" id="edit-email" name="email" required class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Số điện thoại</label>
                    <input type="text" id="edit-phone" name="phone" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Địa chỉ</label>
                <input type="text" id="edit-address" name="address" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Mật khẩu mới <span class="font-medium normal-case text-gray-400">(Bỏ trống nếu không đổi)</span></label>
                <input type="password" name="password" placeholder="••••••••" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-50 transition-all">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Vai trò</label>
                    <select id="edit-role" name="role" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 transition-all cursor-pointer appearance-none">
                        <option value="user">Người dùng</option>
                        <option value="admin">Quản trị viên</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1">Trạng thái</label>
                    <select id="edit-status" name="status" class="w-full px-4 py-2.5 border border-gray-200 bg-gray-50/50 rounded-xl text-sm font-medium focus:bg-white focus:outline-none focus:border-indigo-400 transition-all cursor-pointer appearance-none">
                        <option value="1">Hoạt động</option>
                        <option value="0">Bị khóa</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-50 mt-4">
                <button type="button" onclick="closeModal('modal-edit-user')" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition">Hủy</button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-sm transition shadow-md shadow-indigo-200">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Xử lý bật/tắt Modal mượt mà
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; // Khóa cuộn trang nền
    }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto';
    }

    // Đổ dữ liệu vào Modal Sửa
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