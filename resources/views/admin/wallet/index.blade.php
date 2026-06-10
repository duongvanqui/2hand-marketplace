@extends('layouts.app')

@section('title', 'Quản trị tài chính sàn 2HAND')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">
    
    {{-- SIDEBAR BÊN TRÁI (HIỆU ỨNG THU PHÓNG) --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0 uppercase">
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
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>

            <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-clipboard-list text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-wallet text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Ví 2HAND</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
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
                
                {{-- NÚT QUẢN TRỊ TÀI CHÍNH (ĐANG ACTIVE - MÀU XANH) --}}
                <a href="{{ route('admin.wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-vault text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản trị tài chính</span>
                </a>
            </div>
            @endif
        </nav>
    </aside>

    {{-- NỘI DUNG CHÍNH --}}
    <main class="flex-1 p-8 overflow-y-auto">

        {{-- BỔ SUNG: Header chứa nút bấm đóng mở Sidebar --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2 transition-all">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Quản trị Tài chính & Doanh thu</h1>
            </div>
        </div>

        {{-- KHỐI HIỂN THỊ THÔNG BÁO --}}
        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl mb-6 font-bold border border-emerald-200">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 text-red-800 p-4 rounded-xl mb-6 font-bold border border-red-200">
                <i class="fa-solid fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Khối thống kê doanh thu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-emerald-500">
                <p class="text-sm text-gray-400 font-bold uppercase">Tổng phí sàn thu được (3%)</p>
                <h3 class="text-3xl font-black text-emerald-600 mt-1">{{ number_format($totalRevenue) }} đ</h3>
                <p class="text-xs text-gray-500 mt-2 italic">* Tiền này đã nằm trong tài khoản của sàn.</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                <p class="text-sm text-gray-400 font-bold uppercase">Lệnh rút tiền chờ duyệt</p>
                <h3 class="text-3xl font-black text-blue-600 mt-1">{{ count($pendingWithdrawals) }} yêu cầu</h3>
            </div>
        </div>

        {{-- Danh sách lệnh rút tiền đang chờ --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="p-4 bg-gray-50 border-b border-gray-100">
                <h3 class="font-bold text-gray-800"><i class="fa-solid fa-clock-rotate-left mr-1"></i> Yêu cầu rút tiền chờ xử lý</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase">
                        <tr>
                            <th class="p-4">Người yêu cầu</th>
                            <th class="p-4">Số tiền rút</th>
                            <th class="p-4">Thông tin ngân hàng</th>
                            <th class="p-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($pendingWithdrawals as $w)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ $w->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $w->user->email }}</p>
                            </td>
                            <td class="p-4 font-black text-red-600">{{ number_format($w->amount) }}đ</td>
                            <td class="p-4 text-xs text-gray-600">{{ $w->bank_info }}</td>
                            <td class="p-4 flex justify-center gap-2">
                                <form action="{{ route('admin.wallet.approve', $w->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH') 
                                    <button class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-xs font-bold hover:bg-emerald-600 transition shadow-sm">Duyệt chi</button>
                                </form>
                                <form action="{{ route('admin.wallet.reject', $w->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="px-3 py-1.5 bg-red-100 text-red-600 rounded-lg text-xs font-bold hover:bg-red-200 transition">Từ chối</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-8 text-center text-gray-400 font-medium">Không có yêu cầu rút tiền nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
@endsection