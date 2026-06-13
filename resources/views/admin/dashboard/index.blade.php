@extends('layouts.admin')

@section('title', 'Tổng quan Admin - 2HAND')

{{-- Tùy chỉnh Header: Thêm lời chào và Nút Xuất báo cáo --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Xin chào, {{ Auth::user()->name ?? 'Admin' }}! <span class="text-3xl animate-bounce origin-bottom-right inline-block">👋</span>
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1">Đây là tổng quan hoạt động của hệ thống.</p>
</div>
@endsection

@section('header_actions')
<button onclick="window.dispatchEvent(new CustomEvent('open-export-modal'))" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection

@section('content')
<div class="pb-10">
    
    {{-- ========================================== --}}
    {{-- 1. THỐNG KÊ TỔNG QUAN (6 CARDS CHUẨN UI MỚI) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-5 mb-8">
        
        {{-- Card: Tổng người dùng --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-users"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Tổng người dùng</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($totalUsers) }}</h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +18.2% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Tổng sản phẩm --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Tổng sản phẩm</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($totalProducts) }}</h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +12.5% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: SP chờ duyệt --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-yellow-50 text-yellow-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-regular fa-clock"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Sản phẩm chờ duyệt</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($pendingProducts) }}</h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-yellow-600">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +5.3% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Tổng đơn hàng --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-cart-arrow-down"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Tổng đơn hàng</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($totalOrders) }}</h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +20.1% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Doanh thu --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Doanh thu</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($totalRevenue) }}<span class="text-sm ml-0.5">đ</span></h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-green-500">
                <i class="fa-solid fa-arrow-trend-up mr-1"></i> +15.7% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>

        {{-- Card: Báo cáo vi phạm --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group relative">
            @if($unresolvedReports > 0)
                <span class="absolute top-4 right-4 flex h-3 w-3"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span><span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span></span>
            @endif
            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-xl mb-4 group-hover:scale-110 transition-transform">
                <i class="fa-regular fa-flag"></i>
            </div>
            <p class="text-xs font-bold text-gray-500 mb-1 tracking-wide">Báo cáo vi phạm</p>
            <h3 class="text-2xl font-black text-gray-900">{{ number_format($unresolvedReports) }}</h3>
            <div class="mt-4 pt-3 border-t border-gray-50 flex items-center text-[10px] font-bold text-red-500">
                <i class="fa-solid fa-arrow-trend-down mr-1"></i> -8.1% <span class="text-gray-400 font-medium ml-1">so với tuần trước</span>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 2. KHU VỰC BIỂU ĐỒ (3 CỘT) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        
        {{-- Biểu đồ Doanh Thu --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Doanh thu</h3>
                <span class="text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">7 ngày qua <i class="fa-solid fa-angle-down ml-1"></i></span>
            </div>
            <div class="relative h-48 w-full mb-4">
                <canvas id="revenueChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end">
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase">Tổng doanh thu</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($totalRevenue) }}đ</p>
                </div>
                <div class="text-right">
                    <p class="text-green-500 font-bold text-xs"><i class="fa-solid fa-arrow-up"></i> 15.7%</p>
                    <p class="text-[10px] text-gray-400">so với 7 ngày trước</p>
                </div>
            </div>
        </div>

        {{-- Biểu đồ Đơn Hàng --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Đơn hàng</h3>
                <span class="text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">7 ngày qua <i class="fa-solid fa-angle-down ml-1"></i></span>
            </div>
            <div class="relative h-48 w-full mb-4">
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end">
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase">Tổng đơn hàng</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-green-500 font-bold text-xs"><i class="fa-solid fa-arrow-up"></i> 20.1%</p>
                    <p class="text-[10px] text-gray-400">so với 7 ngày trước</p>
                </div>
            </div>
        </div>

        {{-- Biểu đồ Sản phẩm --}}
        <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-6 border-b border-gray-50 pb-4">
                <h3 class="font-bold text-gray-800">Tài khoản & SP Mới</h3>
                <span class="text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100">7 ngày qua <i class="fa-solid fa-angle-down ml-1"></i></span>
            </div>
            <div class="relative h-48 w-full mb-4">
                <canvas id="usersChart"></canvas>
            </div>
            <div class="mt-auto pt-4 border-t border-gray-50 flex justify-between items-end">
                <div>
                    <p class="text-[11px] text-gray-400 font-bold uppercase">Tổng đăng mới</p>
                    <p class="font-black text-gray-900 text-xl">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="text-right">
                    <p class="text-green-500 font-bold text-xs"><i class="fa-solid fa-arrow-up"></i> 12.5%</p>
                    <p class="text-[10px] text-gray-400">so với 7 ngày trước</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. DANH SÁCH CHI TIẾT (3 CỘT) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- Cột 1: Sản phẩm chờ duyệt --}}
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">Sản phẩm chờ duyệt <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $pendingProducts }}</span></h3>
                <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                {{-- Mockup Danh sách (Bạn có thể thay vòng lặp dữ liệu thật vào đây) --}}
                @for ($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-gray-100">
                    <div class="w-12 h-12 rounded-xl bg-gray-100 shrink-0 border border-gray-200 overflow-hidden flex items-center justify-center text-gray-300">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-blue-600 transition-colors">iPhone 13 Pro Max 128GB</h4>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">Nguyễn Văn A <span class="mx-1">•</span> 5 phút trước</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-sm text-gray-900 mb-1">18.500.000đ</p>
                        <span class="text-[10px] bg-yellow-50 text-yellow-600 font-bold px-2 py-0.5 rounded border border-yellow-100">Chờ duyệt</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Cột 2: Đơn hàng mới --}}
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800">Đơn hàng mới</h3>
                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                @for ($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-3 hover:bg-gray-50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-gray-100">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-bag-shopping"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-purple-600 transition-colors">#DH1256{{ 8-$i }}</h4>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">Người mua: Trần Quốc Bảo</p>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="font-bold text-sm text-gray-900 mb-1">12.500.000đ</p>
                        <span class="text-[10px] bg-green-50 text-green-600 font-bold px-2 py-0.5 rounded border border-green-100">Chờ xác nhận</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        {{-- Cột 3: Vi phạm mới --}}
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col overflow-hidden">
            <div class="p-5 border-b border-gray-50 flex items-center justify-between bg-gray-50/30">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">Vi phạm mới <span class="bg-red-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ $unresolvedReports }}</span></h3>
                <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-700">Xem tất cả</a>
            </div>
            <div class="p-2 flex-1 overflow-y-auto max-h-96 no-scrollbar">
                @for ($i = 0; $i < 4; $i++)
                <div class="flex items-center gap-3 p-3 hover:bg-red-50/50 rounded-2xl transition group cursor-pointer border border-transparent hover:border-red-100">
                    <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 shrink-0 flex items-center justify-center border border-red-100">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-800 truncate group-hover:text-red-600 transition-colors">Báo cáo sản phẩm #SP125{{ 60-$i }}</h4>
                        <p class="text-[11px] text-gray-500 truncate mt-0.5">Lý do: Spam, quảng cáo <span class="mx-1">•</span> 5 phút trước</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="text-[10px] text-red-500 font-bold underline underline-offset-2">Chưa xử lý</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>

    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL: XUẤT BÁO CÁO (MOCKUP UI) --}}
{{-- ========================================== --}}
<div x-data="{ open: false }" @open-export-modal.window="open = true">
    <div x-show="open" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center">
        {{-- Backdrop --}}
        <div x-show="open" x-transition.opacity @click="open = false" class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm"></div>
        
        {{-- Modal Content --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white w-full max-w-md rounded-3xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col m-4">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="font-black text-xl text-gray-800 flex items-center gap-2"><i class="fa-solid fa-file-export text-blue-600"></i> Xuất Báo Cáo</h3>
                <button @click="open = false" class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <div class="p-6 space-y-5">
                {{-- Chọn ngày --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Từ ngày</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Đến ngày</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition">
                    </div>
                </div>

                {{-- Chọn loại dữ liệu --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Dữ liệu xuất</label>
                    <select class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none transition appearance-none cursor-pointer">
                        <option>Báo cáo Doanh thu (Phí sàn)</option>
                        <option>Báo cáo Đơn hàng</option>
                        <option>Báo cáo Sản phẩm chờ duyệt</option>
                        <option>Danh sách Người dùng mới</option>
                    </select>
                </div>

                {{-- Chọn định dạng --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Định dạng file</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="format" value="excel" class="w-4 h-4 text-blue-600 focus:ring-blue-500" checked>
                            <span class="font-bold text-sm text-gray-700 flex items-center gap-2"><i class="fa-solid fa-file-excel text-green-600 text-lg"></i> Excel</span>
                        </label>
                        <label class="flex items-center gap-3 p-3 border-2 border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/50">
                            <input type="radio" name="format" value="pdf" class="w-4 h-4 text-blue-600 focus:ring-blue-500">
                            <span class="font-bold text-sm text-gray-700 flex items-center gap-2"><i class="fa-solid fa-file-pdf text-red-500 text-lg"></i> PDF</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex gap-3">
                <button @click="open = false" class="w-1/3 py-3 font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition text-sm">Hủy</button>
                <button @click="open = false; alert('Bắt đầu tải xuống báo cáo...')" class="w-2/3 py-3 font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition text-sm shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Tải xuống
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- KỊCH BẢN CHUẨN HÓA CHART.JS ĐỂ GIỐNG ẢNH --}}
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = {!! json_encode($chartLabels) !!};
    const revenueData = {!! json_encode($revenueData) !!};
    const ordersData = {!! json_encode($ordersData) !!};
    const newUsersData = {!! json_encode($newUsersData) !!};

    const currencyFormat = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' });

    // Config chung để đường Chart mượt giống thiết kế
    const commonOptions = {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { 
            x: { grid: { display: false }, ticks: { font: { size: 10 } } },
            y: { border: { display: false }, ticks: { font: { size: 10 } } } 
        }
    };

    // 1. Chart Doanh thu (Line Xanh dương)
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                data: revenueData,
                borderColor: '#2563eb', backgroundColor: '#2563eb', // Blue
                borderWidth: 2, tension: 0.1, pointRadius: 4, pointHoverRadius: 6
            }]
        },
        options: { ...commonOptions, plugins: { tooltip: { callbacks: { label: (c) => currencyFormat.format(c.raw) } } } }
    });

    // 2. Chart Đơn hàng (Line Xanh lá)
    new Chart(document.getElementById('ordersChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                data: ordersData,
                borderColor: '#10b981', backgroundColor: '#10b981', // Emerald
                borderWidth: 2, tension: 0.1, pointRadius: 4, pointHoverRadius: 6
            }]
        },
        options: commonOptions
    });

    // 3. Chart Sản phẩm (Line Tím)
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                data: newUsersData,
                borderColor: '#8b5cf6', backgroundColor: '#8b5cf6', // Purple
                borderWidth: 2, tension: 0.1, pointRadius: 4, pointHoverRadius: 6
            }]
        },
        options: commonOptions
    });
</script>
@endsection