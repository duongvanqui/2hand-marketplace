@extends('layouts.admin')

@section('title', 'Quản lý Đơn hàng - 2HAND Admin')

@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản lý Đơn hàng
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1 flex items-center">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        
        <span class="text-gray-900 font-bold">Giao dịch</span>
    </p>
</div>
@endsection

@section('header_actions')
<button type="button" onclick="openModal('modal-export')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-blue-200/50 flex items-center gap-2 transform hover:-translate-y-0.5">
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

    {{-- ========================================== --}}
    {{-- THỐNG KÊ (ĐÃ CHIA THÀNH 5 Ô ĐỒNG ĐỀU) --}}
    {{-- ========================================== --}}
    @php
        // Đếm riêng số lượng đơn bị hủy/từ chối
        $cancelledCount = \App\Models\Order::whereIn('status', ['cancelled', 'failed', 'refunded', 'rejected'])->count();
    @endphp

    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-4">
        {{-- Ô 1: Tổng đơn --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-boxes-packing"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Tổng đơn</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($stats['total_orders'] ?? 0) }}</h4>
            </div>
        </div>

        {{-- Ô 2: Phí sàn --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-piggy-bank"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Phí thu được</p>
                <h4 class="text-xl font-black text-emerald-600 leading-none">{{ number_format($stats['total_revenue'] ?? 0) }}đ</h4>
            </div>
        </div>

        {{-- Ô 3: Đang giao --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-truck-fast"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Đang chờ giao</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($stats['pending'] ?? 0) }}</h4>
            </div>
        </div>

        {{-- Ô 4: Hoàn tất --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 flex items-center gap-4 hover:shadow-lg transition-all group">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-check-double"></i></div>
            <div>
                <p class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Hoàn tất</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($stats['completed'] ?? 0) }}</h4>
            </div>
        </div>

        {{-- Ô 5: Đã hủy (MỚI THÊM) --}}
        <div class="bg-white p-5 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-red-100 flex items-center gap-4 hover:shadow-lg transition-all group bg-gradient-to-br from-white to-red-50/40">
            <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-xl shadow-inner group-hover:scale-110 transition-transform shrink-0"><i class="fa-solid fa-file-circle-xmark"></i></div>
            <div>
                <p class="text-[10px] font-bold text-red-500 uppercase tracking-wider mb-1">Đã hủy / Bị từ chối</p>
                <h4 class="text-xl font-black text-gray-900 leading-none">{{ number_format($cancelledCount) }}</h4>
            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- BẢNG DỮ LIỆU --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden mt-2">

        {{-- Bộ lọc & Tìm kiếm --}}
        <div class="p-5 border-b border-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-wrap w-full md:w-auto items-center gap-3">
                <div class="relative w-full md:w-80">
                    <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã đơn, tên sản phẩm..." class="w-full pl-11 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:bg-white focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 outline-none transition-all font-medium">
                </div>

                <select name="status" class="bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-xl px-4 py-2 focus:bg-white focus:ring-2 focus:ring-emerald-100 outline-none cursor-pointer font-medium">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending_shipping" {{ request('status') == 'pending_shipping' ? 'selected' : '' }}>Chờ đóng gói (COD)</option>
                    <option value="paid_escrow" {{ request('status') == 'paid_escrow' ? 'selected' : '' }}>Đã thanh toán (Chờ giao)</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang vận chuyển</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn tất</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Đã hủy / Bị từ chối</option>
                </select>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-5 py-2 bg-gray-800 hover:bg-gray-900 text-white font-bold rounded-xl text-sm transition-colors shadow-sm">Lọc</button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 bg-red-50 text-red-600 text-sm font-bold rounded-xl hover:bg-red-100 transition shadow-sm border border-red-100 whitespace-nowrap">
                            <i class="fa-solid fa-xmark"></i> Xóa lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs uppercase tracking-wider font-bold">
                        <th class="p-5 border-b border-gray-100">Mã Đơn & Thời gian</th>
                        <th class="p-5 border-b border-gray-100">Sản phẩm & Tiền</th>
                        <th class="p-5 border-b border-gray-100">Thông tin 2 Bên</th>
                        <th class="p-5 border-b border-gray-100 text-center">Trạng thái</th>
                        <th class="p-5 border-b border-gray-100 text-center">Tùy chọn</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/80 transition-colors group">

                        <td class="p-5 align-top">
                            <div class="font-black text-gray-900 text-sm mb-1 group-hover:text-emerald-600 transition-colors">#2H{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-[11px] text-gray-500 font-medium space-y-1">
                                <p><span class="text-gray-400">Tạo:</span> {{ $order->created_at->format('H:i d/m/Y') }}</p>
                                @if(in_array($order->status, ['completed', 'shipped', 'cancelled', 'failed', 'rejected']))
                                <p><span class="text-emerald-500">Cập nhật:</span> {{ $order->updated_at->format('H:i d/m/Y') }}</p>
                                @endif
                            </div>
                        </td>

                        <td class="p-5 align-top">
                            <div class="flex items-start gap-3 max-w-[250px]">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 shrink-0 overflow-hidden border border-gray-200">
                                    @if($order->product && $order->product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="fa-solid fa-box"></i></div>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 line-clamp-2 leading-tight mb-1 hover:text-blue-600 transition-colors cursor-pointer">{{ $order->product->title ?? 'Sản phẩm đã xóa' }}</p>
                                    <div class="flex gap-2 text-xs items-center">
                                        <span class="font-black text-red-500">{{ number_format($order->total_amount) }}đ</span>
                                        <span class="text-gray-300">|</span>
                                        
                                        @if(in_array($order->status, ['cancelled', 'failed', 'refunded', 'rejected']))
                                            <span class="text-gray-400 font-bold line-through" title="Đơn thất bại, không thu phí">+{{ number_format($order->fee_amount) }}đ phí</span>
                                        @else
                                            <span class="text-emerald-600 font-bold" title="Phí sàn thu được">+{{ number_format($order->fee_amount) }}đ phí</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-5 align-top">
                            <div class="space-y-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-[10px] font-bold border border-blue-100"><i class="fa-solid fa-store"></i></div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide leading-none mb-0.5">Người bán</p>
                                        <p class="text-xs font-bold text-gray-800">{{ $order->seller->name ?? 'Không rõ' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center text-[10px] font-bold border border-orange-100"><i class="fa-solid fa-cart-shopping"></i></div>
                                    <div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wide leading-none mb-0.5">Người mua</p>
                                        <p class="text-xs font-bold text-gray-800">{{ $order->receiver_name }}</p>
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td class="p-5 align-middle text-center">
                            @if($order->status === 'pending_shipping' || $order->status === 'paid_escrow')
                            <span class="bg-orange-50 text-orange-600 text-[11px] font-bold px-3 py-1.5 rounded-md border border-orange-100 whitespace-nowrap"><i class="fa-solid fa-box-open mr-1"></i> Chờ đóng gói</span>
                            @elseif($order->status === 'shipped')
                            <span class="bg-blue-50 text-blue-600 text-[11px] font-bold px-3 py-1.5 rounded-md border border-blue-100 whitespace-nowrap"><i class="fa-solid fa-truck-fast mr-1"></i> Đang giao</span>
                            @elseif($order->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-600 text-[11px] font-bold px-3 py-1.5 rounded-md border border-emerald-100 whitespace-nowrap"><i class="fa-solid fa-check-double mr-1"></i> Hoàn tất</span>
                            @elseif(in_array($order->status, ['cancelled', 'failed', 'refunded', 'rejected']))
                            <span class="bg-red-50 text-red-600 text-[11px] font-bold px-3 py-1.5 rounded-md border border-red-100 whitespace-nowrap"><i class="fa-solid fa-xmark mr-1"></i> Đã hủy / Bị từ chối</span>
                            @else
                            <span class="bg-gray-100 text-gray-600 text-[11px] font-bold px-3 py-1.5 rounded-md border border-gray-200 whitespace-nowrap">{{ strtoupper($order->status) }}</span>
                            @endif
                        </td>

                        <td class="p-5 align-middle text-center">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-50 hover:bg-blue-50 text-gray-500 hover:text-blue-600 transition-colors border border-gray-200 hover:border-blue-200 shadow-sm" title="Xem chi tiết giao dịch">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100 shadow-inner">
                                <i class="fa-solid fa-file-invoice-dollar text-3xl text-gray-300"></i>
                            </div>
                            <h4 class="text-gray-600 font-bold mb-1">Trống rỗng</h4>
                            <p class="text-sm font-medium text-gray-500">Chưa có giao dịch nào khớp với bộ lọc.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50/50 flex justify-between items-center text-sm text-gray-500">
            <p>Hiển thị từ <span class="font-bold text-gray-900">{{ $orders->firstItem() }}</span> đến <span class="font-bold text-gray-900">{{ $orders->lastItem() }}</span> trong tổng số <span class="font-bold text-gray-900">{{ $orders->total() }}</span> giao dịch</p>
            {{ $orders->appends(request()->query())->links() }}
        </div>
        @endif

    </div>
</div>

{{-- MODAL XUẤT BÁO CÁO --}}
<div id="modal-export" class="fixed inset-0 z-[100] hidden bg-gray-900/40 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
    <div class="bg-white rounded-3xl shadow-2xl max-w-[420px] w-full overflow-hidden flex flex-col transform transition-transform">
        <div class="px-6 py-5 flex items-center justify-between border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-file-export text-blue-600"></i> Xuất Báo Cáo Giao Dịch
            </h3>
            <button type="button" onclick="closeModal('modal-export')" class="text-gray-400 hover:text-gray-600 transition-colors w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-200">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.export', ['type' => 'orders']) }}" method="GET" class="p-6">
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
                <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-2">Trạng thái Giao dịch</label>
                <select name="export_status" class="w-full px-3 py-2.5 border border-gray-200 bg-white rounded-lg text-sm text-gray-700 font-medium focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors cursor-pointer">
                    <option value="all">Tất cả giao dịch</option>
                    <option value="completed">Chỉ Giao dịch Hoàn tất (Đã thu phí)</option>
                    <option value="rejected">Chỉ Giao dịch Bị hủy / Bị từ chối</option>
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