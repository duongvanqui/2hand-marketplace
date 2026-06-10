@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- ===== BẮT ĐẦU SIDEBAR (GIỮ NGUYÊN TỪ DASHBOARD) ===== --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            {{-- Đã tích hợp logic hiện Avatar thực tế ở Sidebar --}}
            @if(Auth::user()->avatar)
                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full object-cover shrink-0 border border-green-200">
            @else
                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif

            <div x-show="sidebarOpen" class="transition-opacity duration-300 overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-green-500 font-medium">
                    {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                </span>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-600 rounded-xl font-medium transition-all group">
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
            
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-xl font-medium transition-all">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>

             @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quản trị hệ thống</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                <span x-show="sidebarOpen">Tổng quan Admin</span>
            </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0 text-center"></i>
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
    {{-- ===== KẾT THÚC SIDEBAR ===== --}}

    {{-- ===== NỘI DUNG CHÍNH (CÀI ĐẶT) ===== --}}
    <main class="flex-1 p-8 overflow-y-auto">
        
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Cài đặt tài khoản cá nhân</h1>
            </div>
        </div>

        @if (session('status') === 'profile-updated')
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl shadow-sm flex items-center gap-3 font-medium mb-6">
                <i class="fa-solid fa-circle-check text-xl text-green-500"></i> Cập nhật thông tin tài khoản thành công!
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            {{-- FORM 1: THÔNG TIN CÁ NHÂN --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">
                <header class="mb-6 border-b border-gray-50 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Hồ sơ cá nhân</h2>
                    <p class="text-sm text-gray-500 mt-1">Cập nhật ảnh đại diện và thông tin liên hệ của bạn.</p>
                </header>

                <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('patch')

                    <div x-data="{ preview: null }">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Ảnh đại diện</label>
                        <div class="flex items-center gap-5">
                            <div class="w-20 h-20 rounded-full bg-gray-100 border-2 border-green-100 overflow-hidden flex-shrink-0 shadow-sm">
                                <template x-if="preview">
                                    <img :src="preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!preview">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-green-50"><i class="fa-regular fa-user text-2xl"></i></div>
                                    @endif
                                </template>
                            </div>
                            <div>
                                <label class="cursor-pointer bg-white px-4 py-2 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 transition inline-block">
                                    <i class="fa-solid fa-cloud-arrow-up mr-1 text-green-600"></i> Tải ảnh mới
                                    <input type="file" name="avatar" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
                                </label>
                                <p class="text-[11px] text-gray-400 mt-2">Định dạng: JPG, PNG. Tối đa 2MB.</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Họ và tên</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-gray-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 transition-all cursor-not-allowed" readonly title="Email không thể thay đổi">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Số điện thoại liên hệ</label>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="Nhập số điện thoại liên hệ..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-gray-900 font-medium">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Địa chỉ mặc định</label>
                        <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}" placeholder="Địa chỉ giao dịch, nhận hàng..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-gray-900 font-medium">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-all">
                            Lưu thông tin cá nhân
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORM 2: ĐỔI MẬT KHẨU --}}
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 h-fit">
                <header class="mb-6 border-b border-gray-50 pb-4">
                    <h2 class="text-xl font-bold text-gray-900">Bảo mật mật khẩu</h2>
                    <p class="text-sm text-gray-500 mt-1">Đảm bảo tài khoản của bạn sử dụng mật khẩu mạnh để giữ an toàn.</p>
                </header>

                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                        <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all">
                        @error('current_password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mật khẩu mới</label>
                        <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all">
                        @error('password', 'updatePassword')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all">
                    </div>

                    <div class="flex items-center gap-4 pt-2">
                        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3 px-8 rounded-xl shadow-md transition-all">
                            Đổi mật khẩu
                        </button>
                        @if (session('status') === 'password-updated')
                            <p class="text-sm text-green-600 font-medium"><i class="fa-solid fa-check mr-1"></i> Đã thay đổi</p>
                        @endif
                    </div>
                </form>
            </div>

        </div>
    </main>
</div>
@endsection