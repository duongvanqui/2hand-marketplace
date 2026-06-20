@extends('layouts.admin')

@section('title', 'Tổng quan Admin - 2HAND')

{{-- Đẩy tiêu đề lên thanh Header dùng chung và chuẩn hóa thanh Breadcrumb --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Tổng quan hệ thống
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
        <span class="text-gray-900">Tổng quan</span>
    </div>
</div>
@endsection

{{-- Nút hành động trích xuất file PDF báo cáo --}}
@section('header_actions')
<a href="{{ route('admin.dashboard.export', ['days' => request('days', 7)]) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5 whitespace-nowrap">
    <i class="fa-solid fa-file-pdf"></i> Xuất báo cáo PDF
</a>
@endsection

@section('content')
<div class="pb-10">
    
    {{-- ========================================== --}}
    {{-- 1. KHỐI THẺ THỐNG KÊ (BẢN REDESIGN 3 CỘT THOÁNG ĐÃNG) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- Thẻ: Tổng người dùng --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Tổng người dùng</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalUsers ?? 0) }}</h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-emerald-500 bg-emerald-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-arrow-trend-up mr-1.5"></i> Hoạt động ổn định
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>

        {{-- Thẻ: Tổng sản phẩm --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Tổng sản phẩm</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalProducts ?? 0) }}</h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-emerald-500 bg-emerald-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-box-open mr-1.5"></i> Đã đăng lên sàn
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
        </div>

        {{-- Thẻ: Sản phẩm chờ duyệt --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">SP chờ duyệt</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($pendingProducts ?? 0) }}</h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-yellow-600 bg-yellow-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-clock-rotate-left mr-1.5"></i> Cần duyệt gấp
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-yellow-50 text-yellow-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
        </div>

        {{-- Thẻ: Tổng đơn hàng --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Tổng đơn hàng</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalOrders ?? 0) }}</h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-purple-600 bg-purple-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-money-bill-transfer mr-1.5"></i> Đơn hàng hệ thống
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-purple-50 text-purple-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-solid fa-cart-arrow-down"></i>
            </div>
        </div>

        {{-- Thẻ: Doanh thu phí sàn --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center">
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Doanh thu phí sàn (3%)</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($totalRevenue ?? 0) }}<span class="text-lg text-gray-400 ml-0.5">đ</span></h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-blue-600 bg-blue-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-vault mr-1.5"></i> Lợi nhuận sàn thu về
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-blue-50 text-blue-600 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>

        {{-- Thẻ: Báo cáo vi phạm --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 hover:shadow-md transition-all group flex justify-between items-center relative">
            @if(isset($unresolvedReports) && $unresolvedReports > 0)
                <span class="absolute top-5 right-5 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                </span>
            @endif
            <div>
                <p class="text-xs font-bold text-gray-400 mb-2 uppercase tracking-wide">Báo cáo vi phạm</p>
                <h3 class="text-3xl font-black text-gray-900">{{ number_format($unresolvedReports ?? 0) }}</h3>
                <div class="mt-3 flex items-center text-[11px] font-bold text-red-600 bg-red-50 w-max px-2.5 py-1 rounded-md">
                    <i class="fa-solid fa-circle-exclamation mr-1.5"></i> Chờ xử lý gấp
                </div>
            </div>
            <div class="w-16 h-16 rounded-[1.25rem] bg-red-50 text-red-500 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shrink-0">
                <i class="fa-regular fa-flag"></i>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- 2. KHU VỰC 3 KHỐI SƠ ĐỒ / BIỂU ĐỒ HOÀN THIỆN --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- Sơ đồ Doanh thu --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col h-[350px]">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Doanh thu</h3>
                <select onchange="window.location.href='?days=' + this.value" class="text-xs font-medium text-gray-600 bg-gray-50 px-2 py-1.5 rounded-lg border border-gray-200 outline-none cursor-pointer">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>1 năm qua</option>
                </select>
            </div>
            <div class="flex-1 min-h-0 relative">
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end shrink-0">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">TỔNG TRONG KỲ</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($chartTotalRevenue ?? 0) }}đ</p>
                </div>
            </div>
        </div>

        {{-- Sơ đồ Đơn hàng --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col h-[350px]">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Đơn hàng</h3>
                <select onchange="window.location.href='?days=' + this.value" class="text-xs font-medium text-gray-600 bg-gray-50 px-2 py-1.5 rounded-lg border border-gray-200 outline-none cursor-pointer">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>1 năm qua</option>
                </select>
            </div>
            <div class="flex-1 min-h-0 relative">
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end shrink-0">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">TỔNG TRONG KỲ</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($chartTotalOrders ?? 0) }}</p>
                </div>
            </div>
        </div>

        {{-- Sơ đồ Tài khoản & Sản phẩm --}}
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 flex flex-col h-[350px]">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Tài khoản & SP Mới</h3>
                <select onchange="window.location.href='?days=' + this.value" class="text-xs font-medium text-gray-600 bg-gray-50 px-2 py-1.5 rounded-lg border border-gray-200 outline-none cursor-pointer">
                    <option value="7" {{ $days == 7 ? 'selected' : '' }}>7 ngày qua</option>
                    <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 ngày qua</option>
                    <option value="365" {{ $days == 365 ? 'selected' : '' }}>1 năm qua</option>
                </select>
            </div>
            <div class="flex-1 min-h-0 relative">
                <canvas id="usersChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end shrink-0">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide">TỔNG ĐĂNG MỚI</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($chartTotalProducts ?? 0) }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- 3. KHU VỰC 3 CỘT DANH SÁCH CHI TIẾT THỰC --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Cột 1: Sản phẩm chờ duyệt --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">Sản phẩm chờ duyệt <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ count($recentPendingProducts ?? []) }}</span></h3>
                <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                @forelse($recentPendingProducts ?? [] as $product)
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-2xl transition border border-transparent hover:border-gray-100 group">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 shrink-0 border border-gray-200 overflow-hidden flex items-center justify-center">
                        @if($product->images && count(json_decode($product->images)) > 0)
                            <img src="{{ asset('storage/' . json_decode($product->images)[0]) }}" class="w-full h-full object-cover">
                        @else
                            <i class="fa-solid fa-box text-gray-400"></i>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-blue-600 transition-colors">{{ $product->title }}</h4>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">{{ $product->user->name ?? 'Ẩn danh' }} <span class="mx-1">•</span> {{ $product->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-sm text-gray-900 mb-1">{{ number_format($product->price) }}đ</p>
                        <span class="text-[10px] bg-yellow-50 text-yellow-600 font-bold px-2 py-0.5 rounded border border-yellow-100">Chờ duyệt</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-check-double text-3xl mb-2 text-green-400"></i>
                    <p class="text-sm font-medium">Không có sản phẩm chờ duyệt</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Cột 2: Đơn hàng mới --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800">Đơn hàng mới</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                @forelse($recentOrders ?? [] as $order)
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-2xl transition border border-transparent hover:border-gray-100">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate">#DH{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h4>
                        {{-- FIX: Đã gọi đúng quan hệ $order->buyer->name --}}
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">Người mua: {{ $order->buyer->name ?? 'N/A' }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-sm text-gray-900 mb-1">{{ number_format($order->total_amount) }}đ</p>
                        <span class="text-[10px] bg-gray-100 text-gray-600 font-bold px-2 py-0.5 rounded border border-gray-200">{{ $order->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-box-open text-3xl mb-2 text-gray-300"></i>
                    <p class="text-sm font-medium">Chưa có đơn hàng mới</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Cột 3: Vi phạm mới --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">Vi phạm mới <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ count($recentReports ?? []) }}</span></h3>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                @forelse($recentReports ?? [] as $report)
                <div class="flex items-center gap-3 p-3 hover:bg-red-50/50 rounded-2xl transition border border-transparent hover:border-red-100 group">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 shrink-0 flex items-center justify-center border border-red-100">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-red-600 transition-colors">SP: {{ $report->product->title ?? 'Đã xóa hoặc ẩn' }}</h4>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">Lý do: {{ Str::limit($report->reason, 22) }} <span class="mx-1">•</span> {{ $report->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] text-red-500 font-bold underline underline-offset-2">Chưa xử lý</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-400">
                    <i class="fa-solid fa-shield-halved text-3xl mb-2 text-green-400"></i>
                    <p class="text-sm font-medium">Hệ thống an toàn, sạch sẽ</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection

{{-- ========================================== --}}
{{-- SCRIPT ĐIỀU KHIỂN BIỂU ĐỒ CHART.JS (ĐÃ FIX LỖI) --}}
{{-- ========================================== --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = {!! json_encode($chartLabels ?? []) !!};
    const revenueData = {!! json_encode($revenueData ?? []) !!};
    const ordersData  = {!! json_encode($ordersData ?? []) !!};
    const newUsersData = {!! json_encode($newUsersData ?? []) !!};

    const currencyFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

    // Định hình cấu trúc khung đồ thị chung (BẬT LẠI TRỤC DỌC Y)
    const commonOptions = {
        responsive: true, 
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false } // Tắt ô tiêu đề thừa gây lỗi undefined
        },
        scales: { 
            x: { 
                grid: { display: false }, 
                ticks: { font: { size: 10, family: 'sans-serif' }, color: '#9ca3af' }, 
                border: { display: false } 
            },
            y: { 
                beginAtZero: true, 
                grid: { borderDash: [4, 4], color: '#f3f4f6' }, 
                border: { display: false },
                ticks: { 
                    display: true, // BẬT LẠI TRỤC DỌC SỐ
                    font: { size: 10, family: 'sans-serif' },
                    color: '#9ca3af',
                    maxTicksLimit: 5,
                    callback: function(value) {
                        // Rút gọn con số cho sơ đồ thoáng đãng (Tr: Triệu, K: Ngàn)
                        if (value >= 1000000) return (value / 1000000) + ' Tr';
                        if (value >= 1000) return (value / 1000) + ' K';
                        return value;
                    }
                } 
            } 
        },
        interaction: { intersect: false, mode: 'index' }
    };

    // 1. Chart nét cong Doanh Thu
    if(document.getElementById('revenueChart')) {
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Doanh thu',
                    data: revenueData,
                    borderColor: '#3b82f6', 
                    backgroundColor: 'rgba(59, 130, 246, 0.08)', 
                    borderWidth: 3, 
                    tension: 0.4, // Làm mượt nét cong
                    fill: true,   // Bật đổ màu nền
                    pointRadius: 0,
                    pointHoverRadius: 5
                }]
            },
            options: { ...commonOptions, plugins: { ...commonOptions.plugins, tooltip: { callbacks: { label: (c) => ' Doanh thu: ' + currencyFormat.format(c.raw) } } } }
        });
    }

    // 2. Chart nét cong Đơn Hàng
    if(document.getElementById('ordersChart')) {
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Đơn hàng',
                    data: ordersData,
                    borderColor: '#10b981', 
                    backgroundColor: 'rgba(16, 185, 129, 0.08)',
                    borderWidth: 3, 
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5
                }]
            },
            options: commonOptions
        });
    }

    // 3. Chart nét cong Đăng Sản phẩm mới
    if(document.getElementById('usersChart')) {
        new Chart(document.getElementById('usersChart'), {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Sản phẩm mới',
                    data: newUsersData,
                    borderColor: '#8b5cf6', 
                    backgroundColor: 'rgba(139, 92, 246, 0.08)',
                    borderWidth: 3, 
                    tension: 0.4,
                    fill: true,
                    pointRadius: 0,
                    pointHoverRadius: 5
                }]
            },
            options: commonOptions
        });
    }
</script>
@endsection