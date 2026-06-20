@extends('layouts.admin')

@section('title', 'Quản trị tài chính - Admin')

{{-- Đẩy tiêu đề lên thanh Header dùng chung --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản trị Tài chính & Doanh thu
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-blue-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
        <span class="text-gray-900">Quản trị tài chính</span>
    </div>
</div>
@endsection

{{--  XUẤT BÁO CÁO  --}}
@section('header_actions')
<button type="button" onclick="openModal('modal-export', 'profits')" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-emerald-200/50 flex items-center gap-2 transform hover:-translate-y-0.5 whitespace-nowrap">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection

@section('content')
<div class="pb-10">

    {{-- ========================================== --}}
    {{-- KHỐI HIỂN THỊ THÔNG BÁO FLASH --}}
    {{-- ========================================== --}}
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
    {{-- 1. THỐNG KÊ TÀI CHÍNH (2 CARDS LỚN) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
        
        {{-- Card: Tổng Doanh Thu --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between relative overflow-hidden z-10">
            <div class="absolute right-0 top-0 w-40 h-40 bg-emerald-50 rounded-full blur-3xl -z-10 group-hover:bg-emerald-100 transition-colors"></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                    Tổng phí sàn thu được (3%)
                </p>
                <h3 class="text-4xl font-black text-gray-900">{{ number_format($totalRevenue ?? 0) }}<span class="text-2xl text-gray-400 font-bold ml-1">đ</span></h3>
                <p class="text-[11px] font-bold text-emerald-600 mt-3 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-vault"></i> Lợi nhuận ròng của hệ thống
                </p>
            </div>
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-green-400 text-white flex items-center justify-center text-3xl md:text-4xl shadow-lg shadow-emerald-200 group-hover:scale-110 group-hover:-rotate-6 transition-transform shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>
        
        {{-- Card: Lệnh rút tiền --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between relative overflow-hidden z-10">
            <div class="absolute right-0 top-0 w-40 h-40 bg-blue-50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100 transition-colors"></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                    Lệnh rút tiền chờ duyệt
                </p>
                <h3 class="text-4xl font-black text-gray-900">{{ count($pendingWithdrawals ?? []) }}<span class="text-xl text-gray-400 font-bold ml-2 tracking-normal">yêu cầu</span></h3>
                <p class="text-[11px] font-bold text-blue-600 mt-3 bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-bell animate-pulse"></i> Cần Admin xử lý chuyển khoản
                </p>
            </div>
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center text-3xl md:text-4xl shadow-lg shadow-blue-200 group-hover:scale-110 group-hover:rotate-6 transition-transform shrink-0">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- THANH TÌM KIẾM ĐÃ THU GỌN LẠI --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row justify-between items-center gap-4 mb-6">
        <form action="{{ route('admin.wallet.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full xl:w-auto">
            
            {{-- Ô Search thu gọn w-80 --}}
            <div class="relative w-full md:w-80 shrink-0">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên user, ngân hàng..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-50 transition-all font-medium">
            </div>

            <div class="flex items-center gap-2 w-full md:w-auto shrink-0">
                <button type="submit" class="px-6 py-2.5 bg-gray-900 text-white text-sm font-bold rounded-xl hover:bg-gray-800 transition shadow-sm whitespace-nowrap">
                    Tìm kiếm
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.wallet.index') }}" class="px-5 py-2.5 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 flex items-center justify-center whitespace-nowrap">
                        <i class="fa-solid fa-xmark mr-1"></i> Hủy
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ========================================== --}}
    {{-- 2. BẢNG DANH SÁCH YÊU CẦU RÚT TIỀN (CHỜ XỬ LÝ) --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden mb-6">
        <div class="p-5 border-b border-gray-50 bg-blue-50/30 flex justify-between items-center">
            <h3 class="font-black text-gray-800 text-lg flex items-center gap-2">
                <i class="fa-solid fa-spinner text-blue-500 animate-spin-slow"></i> Yêu cầu rút tiền chờ xử lý
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-5 w-16 text-center font-black text-gray-400">#</th>
                        <th class="p-5">Người yêu cầu</th>
                        <th class="p-5">Số tiền rút</th>
                        <th class="p-5">Thông tin ngân hàng nhận</th>
                        <th class="p-5 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($pendingWithdrawals ?? [] as $index => $w)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="p-5 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                        
                        {{-- Cột Người Yêu Cầu --}}
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-black text-sm flex items-center justify-center shrink-0 border border-blue-100 shadow-sm uppercase">
                                    {{ substr($w->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $w->user->name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $w->user->email }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $w->created_at->format('H:i d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Số Tiền --}}
                        <td class="p-5">
                            <span class="text-lg font-black text-red-500">{{ number_format($w->amount) }}<span class="text-sm underline ml-0.5">đ</span></span>
                        </td>

                        {{-- Cột Ngân hàng --}}
                        <td class="p-5">
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 text-sm font-medium text-gray-800 whitespace-pre-line leading-relaxed max-w-sm">
                                <i class="fa-solid fa-building-columns text-gray-400 mr-1.5"></i> {{ $w->bank_info }}
                            </div>
                        </td>

                        {{-- Cột Hành động --}}
                        <td class="p-5">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('admin.wallet.approve', $w->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn XÁC NHẬN đã chuyển khoản thành công?')">
                                    @csrf @method('PATCH')
                                    <button class="flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-xl text-xs font-bold transition-all border border-emerald-100 hover:border-emerald-500 shadow-sm" title="Duyệt chi">
                                        <i class="fa-solid fa-check"></i> Duyệt chi
                                    </button>
                                </form>

                                <form action="{{ route('admin.wallet.reject', $w->id) }}" method="POST" onsubmit="return confirm('Từ chối lệnh rút này và hoàn tiền lại ví User?')">
                                    @csrf @method('PATCH')
                                    <button class="flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-xl text-xs font-bold transition-all border border-red-100 hover:border-red-500 shadow-sm" title="Từ chối">
                                        <i class="fa-solid fa-xmark"></i> Từ chối
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-10 text-center text-gray-500">
                            <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-3 border border-blue-100 shadow-inner">
                                <i class="fa-solid fa-mug-hot text-2xl text-blue-400"></i>
                            </div>
                            <p class="font-bold text-gray-700 mb-1">Rảnh tay rồi!</p>
                            <p class="text-sm font-medium">Hiện tại không có yêu cầu rút tiền nào cần xử lý.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- 3. GRID 2 CỘT: LỊCH SỬ RÚT TIỀN & LỢI NHUẬN SÀN --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        {{-- CỘT TRÁI: Lịch sử rút tiền đã xử lý --}}
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col h-[500px]">
            <div class="p-5 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center shrink-0">
                <h3 class="font-black text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left text-gray-500"></i> Lịch sử xử lý rút tiền
                </h3>
            </div>
            
            {{-- Vùng tự động cuộn (overflow-y-auto) --}}
            <div class="overflow-y-auto flex-1 p-4 space-y-3 relative">
                @forelse($processedWithdrawals ?? [] as $pw)
                <div class="flex items-center justify-between p-3.5 bg-white rounded-2xl border border-gray-100 hover:shadow-md transition-shadow">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gray-100 text-gray-600 font-black text-sm flex items-center justify-center shrink-0">
                            {{ substr($pw->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $pw->user->name }}</p>
                            <p class="text-[10px] text-gray-400">{{ $pw->updated_at->format('H:i - d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-black text-gray-700">{{ number_format($pw->amount) }}đ</span>
                        <div class="mt-1">
                            @if($pw->status == 'approved')
                                <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100"><i class="fa-solid fa-check"></i> Đã chuyển</span>
                            @else
                                <span class="text-[9px] font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded border border-red-100"><i class="fa-solid fa-xmark"></i> Bị từ chối</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 h-full flex flex-col items-center justify-center">
                    <i class="fa-regular fa-folder-open text-3xl mb-2"></i>
                    <p class="text-sm font-medium">Chưa có dữ liệu</p>
                </div>
                @endforelse
            </div>

            {{-- NÚT 2: TỰ ĐỘNG CHỌN "WITHDRAWALS" (GIẢI NGÂN) --}}
            <div class="p-3 border-t border-gray-100 bg-gray-50/30 shrink-0">
                <button onclick="openModal('modal-export', 'withdrawals')" class="w-full py-2 text-sm font-bold text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-export"></i> Xuất dữ liệu Rút tiền
                </button>
            </div>
        </div>

        {{-- CỘT PHẢI: Lịch sử Thu phí sàn --}}
        <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden flex flex-col h-[500px]">
            <div class="p-5 border-b border-gray-50 bg-emerald-50/30 flex justify-between items-center shrink-0">
                <h3 class="font-black text-gray-800 flex items-center gap-2">
                    <i class="fa-solid fa-chart-line text-emerald-500"></i> Lợi nhuận nhận được
                </h3>
            </div>
            
            {{-- Vùng tự động cuộn (overflow-y-auto) --}}
            <div class="overflow-y-auto flex-1 p-4 space-y-3 relative">
                @php
                    $profitHistory = \App\Models\Order::where('status', 'completed')->where('fee_amount', '>', 0)->latest()->take(20)->get();
                @endphp

                @forelse($profitHistory as $profit)
                <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-2xl border border-gray-100 hover:border-emerald-200 transition-colors">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-hand-holding-dollar text-sm"></i>
                        </div>
                        <div class="truncate">
                            <p class="font-bold text-gray-900 text-sm truncate" title="{{ $profit->product->title ?? 'Sản phẩm đã xóa' }}">
                                {{ $profit->product->title ?? 'Sản phẩm đã bị xóa' }}
                            </p>
                            <p class="text-[10px] text-gray-500 mt-0.5">Mã đơn: <span class="font-mono text-gray-700">#{{ str_pad($profit->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                        </div>
                    </div>
                    <div class="text-right shrink-0 pl-2">
                        <p class="font-black text-emerald-600">+{{ number_format($profit->fee_amount) }}<span class="text-xs ml-0.5 font-bold">đ</span></p>
                        <p class="text-[9px] text-gray-400 mt-1">{{ $profit->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-10 text-gray-400 h-full flex flex-col items-center justify-center">
                    <i class="fa-solid fa-chart-pie text-3xl mb-2 text-emerald-200"></i>
                    <p class="text-sm font-medium">Chưa có khoản lợi nhuận nào.</p>
                </div>
                @endforelse
            </div>

            {{-- NÚT 3: TỰ ĐỘNG CHỌN "PROFITS" (LỢI NHUẬN) --}}
            <div class="p-3 border-t border-gray-100 bg-emerald-50/10 shrink-0">
                <button onclick="openModal('modal-export', 'profits')" class="w-full py-2 text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-xl transition-colors flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-file-export"></i> Xuất dữ liệu Lợi nhuận
                </button>
            </div>
        </div>

    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL XUẤT BÁO CÁO TÀI CHÍNH --}}
{{-- ========================================== --}}
<div id="modal-export" class="fixed inset-0 z-[100] hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-xl max-w-[420px] w-full overflow-hidden flex flex-col transform transition-transform">
        <div class="px-6 py-5 flex items-center justify-between border-b border-gray-100">
            <h3 class="text-base font-black text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-file-export text-emerald-600"></i> Xuất Báo Cáo Tài Chính
            </h3>
            <button type="button" onclick="closeModal('modal-export')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.export', ['type' => 'wallet']) }}" method="GET" class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Từ ngày</label>
                    <input type="date" name="from_date" value="{{ now()->subMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Đến ngày</label>
                    <input type="date" name="to_date" value="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Loại báo cáo</label>
                {{-- ID report_type_select ĐỂ JS TÌM VÀ THAY ĐỔI GIÁ TRỊ --}}
                <select id="report_type_select" name="report_type" class="w-full px-3 py-2.5 border border-gray-200 bg-white rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition-colors cursor-pointer">
                    <option value="profits">Báo cáo Lợi nhuận sàn (Phí giao dịch)</option>
                    <option value="withdrawals">Báo cáo Yêu cầu rút tiền (Giải ngân)</option>
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Định dạng file</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/30">
                        <input type="radio" name="format" value="excel" checked class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500 mt-0.5">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-excel text-green-600 text-lg"></i>
                            <span class="font-bold text-gray-700 text-sm">Excel</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/30">
                        <input type="radio" name="format" value="pdf" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500 mt-0.5">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-pdf text-red-600 text-lg"></i>
                            <span class="font-bold text-gray-700 text-sm">PDF</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-between gap-3 pt-2 border-t border-gray-50">
                <button type="button" onclick="closeModal('modal-export')" class="w-1/3 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-xl text-sm transition-colors">
                    Hủy
                </button>
                <button type="submit" onclick="setTimeout(() => closeModal('modal-export'), 800)" class="w-2/3 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-emerald-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Tải xuống
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Đã nâng cấp tham số thứ 2 (reportType) để đồng bộ Dropdown
    function openModal(id, reportType = null) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
        
        // Nếu có truyền loại báo cáo, tự động đổi giá trị của Select box
        if (reportType) {
            let selectEl = document.getElementById('report_type_select');
            if (selectEl) {
                selectEl.value = reportType;
            }
        }
    }
    
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
    }
</script>
@endsection