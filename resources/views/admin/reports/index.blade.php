@extends('layouts.admin')

@section('title', 'Quản lý Báo cáo vi phạm - 2HAND Admin')

{{-- BREADCRUMB ĐỒNG BỘ --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản lý Báo cáo vi phạm
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1 flex items-center">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-red-600 transition-colors">Trang chủ</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        <span class="text-gray-900 font-bold">Báo cáo vi phạm</span>
    </p>
</div>
@endsection

{{-- NÚT XUẤT BÁO CÁO --}}
@section('header_actions')
<button type="button" onclick="openModal('modal-export')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5 whitespace-nowrap">
    <i class="fa-solid fa-file-export"></i> Xuất báo cáo
</button>
@endsection

@section('content')
<div class="space-y-6 pb-10">

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
    {{-- THỐNG KÊ NHANH --}}
    {{-- ========================================== --}}
    @php
        $totalReports = \App\Models\Report::count();
        $pendingReports = \App\Models\Report::where('status', 'pending')->count();
        $resolvedReports = \App\Models\Report::where('status', 'resolved')->count();
        $dismissedReports = \App\Models\Report::where('status', 'dismissed')->count();
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-flag"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tổng báo cáo</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($totalReports) }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-orange-100 flex items-center gap-4 hover:shadow-lg transition-all group bg-gradient-to-br from-white to-orange-50/40">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <p class="text-[10px] font-bold text-orange-500 uppercase tracking-wider mb-1">Chờ xử lý</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($pendingReports) }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-check-double"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Đã xử lý (Khóa SP)</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($resolvedReports) }}</h4>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-gray-100 text-gray-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-ban"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Đã bỏ qua</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($dismissedReports) }}</h4>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- THANH TÌM KIẾM & BỘ LỌC (ĐÃ THU GỌN) --}}
    {{-- ========================================== --}}
    <div class="bg-white p-4 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex flex-col xl:flex-row justify-between items-center gap-4 mb-6 mt-6">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-wrap md:flex-nowrap items-center gap-3 w-full xl:w-auto">
            
            {{-- Ô Search thu gọn w-72 --}}
            <div class="relative w-full md:w-72">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm lý do, tên người dùng..." class="w-full pl-11 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 transition-all font-medium">
            </div>

            {{-- Dropdown Lọc --}}
            <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-2.5 focus:ring-blue-500 focus:border-blue-500 block w-full md:w-auto outline-none font-medium cursor-pointer shrink-0">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã khóa SP</option>
                <option value="dismissed" {{ request('status') == 'dismissed' ? 'selected' : '' }}>Đã bỏ qua</option>
            </select>

            {{-- Nút Tìm / Hủy --}}
            <div class="flex items-center gap-2 w-full md:w-auto shrink-0">
                <button type="submit" class="px-6 py-2.5 bg-gray-800 text-white text-sm font-bold rounded-xl hover:bg-gray-700 transition shadow-sm whitespace-nowrap">
                    Tìm kiếm
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.reports.index') }}" class="px-5 py-2.5 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 flex items-center justify-center whitespace-nowrap">
                        <i class="fa-solid fa-xmark mr-1"></i> Hủy
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- ========================================== --}}
    {{-- BẢNG DỮ LIỆU BÁO CÁO --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden mt-2">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-[10px] uppercase tracking-wider font-bold border-b border-gray-100">
                        <th class="p-5 pl-6">Người báo cáo</th>
                        <th class="p-5">Sản phẩm bị báo cáo</th>
                        <th class="p-5">Lý do & Chi tiết</th>
                        <th class="p-5 text-center">Trạng thái</th>
                        <th class="p-5 text-center pr-6">Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-sm">
                    @forelse($reports as $report)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        
                        <td class="p-5 pl-6">
                            <div class="flex items-center gap-3">
                                @if($report->user && $report->user->avatar)
                                    <img src="{{ asset('storage/' . $report->user->avatar) }}" class="w-10 h-10 rounded-full object-cover border border-gray-200 shadow-sm shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center font-black text-blue-600 shrink-0 border border-blue-100 shadow-sm uppercase">
                                        {{ substr($report->user->name ?? 'U', 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-gray-900">{{ $report->user->name ?? 'Người dùng ẩn' }}</p>
                                    <p class="text-[11px] font-medium text-gray-400">{{ $report->created_at->format('H:i d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-5">
                            @if($report->product)
                            <div class="flex items-center gap-3 max-w-[250px]">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 shrink-0 overflow-hidden border border-gray-200">
                                    @if($report->product->images && $report->product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $report->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-box"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <a href="{{ route('products.show', $report->product->id) }}" target="_blank" class="font-bold text-gray-900 hover:text-blue-600 transition-colors line-clamp-2 leading-tight mb-1" title="{{ $report->product->title }}">
                                        {{ $report->product->title }}
                                    </a>
                                    <p class="text-xs text-gray-500 font-medium">Bán bởi: <span class="text-indigo-600">{{ $report->product->user->name ?? 'Ẩn danh' }}</span></p>
                                </div>
                            </div>
                            @else
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-lg text-xs font-bold border border-gray-200">Sản phẩm đã bị xóa</span>
                            @endif
                        </td>

                        <td class="p-5">
                            <div class="flex flex-col gap-1.5 max-w-xs">
                                <span class="inline-flex items-center gap-1.5 w-fit px-2 py-1 bg-red-50 text-red-600 rounded-md text-[11px] font-bold border border-red-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i> {{ $report->reason }}
                                </span>
                                @if($report->details)
                                    <p class="text-xs font-medium text-gray-600 bg-gray-50 p-2 rounded-lg border border-gray-100 line-clamp-2" title="{{ $report->details }}">{{ $report->details }}</p>
                                @endif
                            </div>
                        </td>

                        <td class="p-5 text-center align-middle">
                            @if($report->status === 'pending')
                                <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-[11px] font-bold bg-orange-50 text-orange-600 border border-orange-100 whitespace-nowrap shadow-sm">
                                    <i class="fa-solid fa-clock mr-1.5"></i> Chờ xử lý
                                </span>
                            @elseif($report->status === 'resolved')
                                <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-[11px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 whitespace-nowrap shadow-sm">
                                    <i class="fa-solid fa-lock mr-1.5"></i> Đã khóa SP
                                </span>
                            @elseif($report->status === 'dismissed')
                                <span class="inline-flex items-center justify-center px-3 py-1.5 rounded-lg text-[11px] font-bold bg-gray-100 text-gray-600 border border-gray-200 whitespace-nowrap shadow-sm">
                                    <i class="fa-solid fa-ban mr-1.5"></i> Đã bỏ qua
                                </span>
                            @endif
                        </td>

                        <td class="p-5 pr-6 text-center align-middle">
                            <div class="flex items-center justify-center gap-2">
                                
                                @if($report->status === 'pending')
                                    <form action="{{ route('admin.reports.handle', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn có chắc chắn muốn khóa sản phẩm này?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="lock">
                                        <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Khóa sản phẩm vi phạm">
                                            <i class="fa-solid fa-lock"></i>
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.reports.handle', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Bỏ qua báo cáo này do không có vi phạm?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="dismiss">
                                        <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-600 hover:text-white transition-all shadow-sm border border-gray-200" title="Bỏ qua báo cáo">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>

                                @elseif($report->status === 'resolved')
                                    <form action="{{ route('admin.reports.handle', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Bạn muốn MỞ KHÓA lại sản phẩm này?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="unlock">
                                        <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Mở khóa sản phẩm">
                                            <i class="fa-solid fa-lock-open"></i>
                                        </button>
                                    </form>

                                @elseif($report->status === 'dismissed')
                                    <form action="{{ route('admin.reports.handle', $report->id) }}" method="POST" class="inline" onsubmit="return confirm('Khôi phục báo cáo này về trạng thái Chờ xử lý?');">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="action" value="revert">
                                        <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-500 hover:text-white transition-all shadow-sm" title="Khôi phục trạng thái chờ xử lý">
                                            <i class="fa-solid fa-rotate-left"></i>
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center text-gray-500">
                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-inner">
                                <i class="fa-solid fa-shield-check text-3xl text-emerald-500"></i>
                            </div>
                            <h4 class="text-gray-800 font-bold mb-1">Môi trường an toàn</h4>
                            <p class="text-sm font-medium text-gray-500">Hiện tại không có báo cáo vi phạm nào cần xử lý.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($reports->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <p>Hiển thị từ <span class="font-bold text-gray-900">{{ $reports->firstItem() }}</span> đến <span class="font-bold text-gray-900">{{ $reports->lastItem() }}</span> trong tổng số <span class="font-bold text-gray-900">{{ $reports->total() }}</span> báo cáo</p>
            {{ $reports->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ========================================== --}}
{{-- MODAL XUẤT BÁO CÁO VI PHẠM --}}
{{-- ========================================== --}}
<div id="modal-export" class="fixed inset-0 z-[100] hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-[420px] w-full overflow-hidden flex flex-col transform transition-transform">
        <div class="px-6 py-5 flex items-center justify-between border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-file-export text-blue-600"></i> Trích xuất Báo cáo vi phạm
            </h3>
            <button type="button" onclick="closeModal('modal-export')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.export', ['type' => 'reports']) }}" method="GET" class="p-6">
            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Từ ngày</label>
                    <input type="date" name="from_date" value="{{ now()->subMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Đến ngày</label>
                    <input type="date" name="to_date" value="{{ now()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-gray-700 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors">
                </div>
            </div>

            <div class="mb-5">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Lọc trạng thái</label>
                <select name="export_status" class="w-full px-3 py-2.5 border border-gray-200 bg-white rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">
                    <option value="all">Tất cả báo cáo</option>
                    <option value="pending">Chỉ danh sách Chờ xử lý</option>
                    <option value="resolved">Đã xử lý (Sản phẩm bị khóa)</option>
                </select>
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Định dạng file</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                        <input type="radio" name="format" value="excel" checked class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mt-0.5">
                        <div class="flex items-center gap-1.5">
                            <i class="fa-solid fa-file-excel text-green-600 text-lg"></i>
                            <span class="font-bold text-gray-700 text-sm">Excel</span>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50/30">
                        <input type="radio" name="format" value="pdf" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500 mt-0.5">
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
                <button type="submit" onclick="setTimeout(() => closeModal('modal-export'), 800)" class="w-2/3 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm transition-all shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-download"></i> Tải xuống
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(id) { 
        document.getElementById(id).classList.remove('hidden'); 
        document.body.style.overflow = 'hidden'; 
    }
    function closeModal(id) { 
        document.getElementById(id).classList.add('hidden'); 
        document.body.style.overflow = 'auto'; 
    }
</script>
@endsection