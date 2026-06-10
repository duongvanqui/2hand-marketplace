@extends('layouts.app')

@section('title', 'Admin Dashboard - Master Control')

@section('content')
{{-- Load thư viện vẽ biểu đồ --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="flex min-h-screen bg-gray-50" x-data="{ sidebarOpen: true }">
    
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
                
                {{-- NÚT TỔNG QUAN ADMIN (ĐANG ACTIVE - MÀU XANH) --}}
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Tổng quan Admin</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
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

    <main class="flex-1 p-8 overflow-y-auto">
        
        {{-- Header có nút Menu Hamburger --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2 transition-all">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-black text-gray-900"><i class="fa-solid fa-gauge-high text-indigo-600 mr-2"></i>TỔNG QUAN HỆ THỐNG</h1>
                    <p class="text-gray-500 text-sm mt-1">Dữ liệu được cập nhật theo thời gian thực.</p>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-2 -m-2">
            
            {{-- 1. THỐNG KÊ TỔNG QUAN (Summary Cards) --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                {{-- Doanh thu --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-indigo-300 transition">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-sack-dollar text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Tổng Doanh Thu</p>
                    <h3 class="text-xl font-black text-gray-900 mt-1">{{ number_format($totalRevenue) }}đ</h3>
                </div>
                {{-- Đơn hàng --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-emerald-300 transition">
                    <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-boxes-packing text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Tổng Đơn Hàng</p>
                    <h3 class="text-xl font-black text-gray-900 mt-1">{{ number_format($totalOrders) }}</h3>
                </div>
                {{-- Người dùng --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-blue-300 transition">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-users text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Người Dùng</p>
                    <h3 class="text-xl font-black text-gray-900 mt-1">{{ number_format($totalUsers) }}</h3>
                </div>
                {{-- Sản phẩm --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-purple-300 transition">
                    <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-tags text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Tổng Sản Phẩm</p>
                    <h3 class="text-xl font-black text-gray-900 mt-1">{{ number_format($totalProducts) }}</h3>
                </div>
                {{-- Chờ duyệt --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-yellow-300 transition relative">
                    @if($pendingProducts > 0)<span class="absolute top-4 right-4 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-yellow-500"></span></span>@endif
                    <div class="w-10 h-10 bg-yellow-50 text-yellow-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-hourglass-half text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">SP Chờ Duyệt</p>
                    <h3 class="text-xl font-black text-yellow-600 mt-1">{{ number_format($pendingProducts) }}</h3>
                </div>
                {{-- Báo cáo xấu --}}
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 hover:border-red-300 transition relative">
                    @if($unresolvedReports > 0)<span class="absolute top-4 right-4 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span></span>@endif
                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mb-3"><i class="fa-solid fa-flag text-lg"></i></div>
                    <p class="text-xs text-gray-500 font-bold uppercase">Báo Cáo (Report)</p>
                    <h3 class="text-xl font-black text-red-600 mt-1">{{ number_format($unresolvedReports) }}</h3>
                </div>
            </div>

            {{-- 2. KHU VỰC BIỂU ĐỒ (Charts) --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                {{-- Biểu đồ Doanh Thu (Chiếm 2 cột trên màn hình lớn) --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:col-span-2">
                    <h3 class="font-bold text-gray-800 mb-4"><i class="fa-solid fa-chart-line text-indigo-500 mr-2"></i>Biểu đồ Doanh thu (Phí sàn) 6 tháng gần nhất</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Biểu đồ Đơn Hàng --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4"><i class="fa-solid fa-chart-bar text-emerald-500 mr-2"></i>Lượng Đơn Hàng Mới</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="ordersChart"></canvas>
                    </div>
                </div>

                {{-- Biểu đồ Người dùng --}}
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4"><i class="fa-solid fa-chart-pie text-blue-500 mr-2"></i>Đăng Ký Tài Khoản Mới</h3>
                    <div class="relative h-64 w-full">
                        <canvas id="usersChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- HOẠT ĐỘNG MỚI NHẤT --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4"><i class="fa-solid fa-bolt text-yellow-500 mr-2"></i>Cần Xử Lý Gấp</h3>
                <div class="flex items-center justify-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <p class="text-gray-500 text-sm">Hiển thị danh sách duyệt tiền, duyệt bài, hoặc report tại đây để truy cập nhanh...</p>
                </div>
            </div>

        </div>
    </main>
</div>

{{-- SCRIPT XỬ LÝ BIỂU ĐỒ --}}
<script>
    // 1. Dữ liệu từ Backend đẩy xuống JS
    const chartLabels = {!! json_encode($chartLabels) !!};
    const revenueData = {!! json_encode($revenueData) !!};
    const ordersData = {!! json_encode($ordersData) !!};
    const newUsersData = {!! json_encode($newUsersData) !!};

    // Định dạng tiền tệ cho biểu đồ
    const currencyFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

    // 2. Cấu hình Biểu đồ Doanh Thu (Line Chart)
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Doanh thu phí sàn',
                data: revenueData,
                borderColor: '#4f46e5', // indigo-600
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                tension: 0.4, // Đường cong mềm mại
                fill: true
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { tooltip: { callbacks: { label: function(context) { return currencyFormat.format(context.raw); } } } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // 3. Cấu hình Biểu đồ Đơn hàng (Bar Chart)
    new Chart(document.getElementById('ordersChart'), {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Số lượng đơn',
                data: ordersData,
                backgroundColor: '#10b981', // emerald-500
                borderRadius: 6
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });

    // 4. Cấu hình Biểu đồ Người dùng (Line Chart / Area)
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Thành viên mới',
                data: newUsersData,
                borderColor: '#3b82f6', // blue-500
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderWidth: 2,
                fill: true,
                stepped: true // Đường gấp khúc
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
    });
</script>
@endsection