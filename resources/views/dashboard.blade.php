@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
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
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-xl font-medium transition-all">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
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

                <a href="{{ route('admin.wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-vault text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản trị tài chính</span>
                </a>

            </div>
            @endif
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Bảng điều khiển cá nhân</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm text-gray-400 font-medium uppercase m-0">Tin đang bán</p>
                    <h3 class="text-3xl font-bold text-gray-800 m-0 mt-1">{{ $totalApproved }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center text-green-500 text-xl">
                    <i class="fa-solid fa-check-to-slot"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm text-gray-400 font-medium uppercase m-0">Tổng lượt xem tin</p>
                    <h3 class="text-3xl font-bold text-gray-800 m-0 mt-1">{{ number_format($totalViews) }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 text-xl">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition">
                <div>
                    <p class="text-sm text-gray-400 font-medium uppercase m-0">Tin bị từ chối</p>
                    <h3 class="text-3xl font-bold text-gray-800 m-0 mt-1">{{ $totalRejected }}</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center text-red-500 text-xl">
                    <i class="fa-solid fa-circle-exclamation"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">Tin thanh lý của bạn</h3>
                <a href="{{ route('products.create') }}" class="px-4 py-2 bg-emerald-500 text-white font-medium rounded-xl text-sm hover:bg-emerald-600 transition-all shadow-sm shadow-emerald-200">
                    + Đăng tin mới
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase border-b border-gray-100">
                            <th class="p-4">Sản phẩm</th>
                            <th class="p-4">Giá bán</th>
                            <th class="p-4">Ngày đăng</th>
                            <th class="p-4">Trạng thái</th>
                            <th class="p-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-4 font-semibold text-gray-800">
                                <a href="{{ route('products.show', $product->slug) }}" class="hover:text-emerald-500 transition-colors">
                                    {{ $product->title }}
                                </a>
                            </td>
                            <td class="p-4 text-emerald-500 font-bold">{{ number_format($product->price) }}đ</td>
                            <td class="p-4 text-gray-500">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="p-4">
                                @if($product->status === 'approved')
                                <span class="px-2.5 py-1 bg-green-50 text-green-600 rounded-full text-xs font-semibold">Đang hiển thị</span>
                                @elseif($product->status === 'pending')
                                <span class="px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-semibold">Chờ duyệt</span>
                                @else
                                <span class="px-2.5 py-1 bg-red-50 text-red-600 rounded-full text-xs font-semibold">Bị từ chối</span>
                                @endif
                            </td>
                            <td class="p-4 flex items-center justify-center space-x-2">
                                <a href="#" class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-all" title="Sửa">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <button class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Xóa">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400 font-medium">
                                Bạn chưa đăng tải tin thanh lý nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($products->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $products->links() }}
            </div>
            @endif
        </div>
    </main>
</div>
@endsection