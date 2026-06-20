@extends('layouts.admin')

@section('title', 'Chi tiết Hóa đơn - 2HAND Admin')

@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Hóa đơn Giao dịch
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1 hide-on-print flex items-center">
        <a href="{{ route('admin.dashboard') ?? url('/admin') }}" class="hover:text-indigo-600 transition-colors">Trang chủ</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        
        <a href="{{ route('admin.orders.index') }}" class="hover:text-indigo-600 transition-colors">Giao dịch</a> 
        <i class="fa-solid fa-angle-right text-[10px] mx-2"></i> 
        
        <span class="text-gray-900 font-bold">Chi tiết</span>
    </p>
</div>
@endsection

@section('header_actions')
<div class="flex items-center gap-3 hide-on-print">
    <a href="{{ route('admin.orders.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold text-sm transition-colors flex items-center gap-2 shadow-sm">
        <i class="fa-solid fa-arrow-left"></i> Trở về
    </a>
    <button onclick="window.print()" class="px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-bold text-sm transition-all shadow-md flex items-center gap-2">
        <i class="fa-solid fa-print"></i> In Hóa Đơn
    </button>
</div>
@endsection

@section('content')
<style>
    /* CSS MẬT DÀNH RIÊNG CHO BẢN IN PDF */
    @media print {
        body * { visibility: hidden; }
        .invoice-container, .invoice-container * { visibility: visible; }
        .invoice-container { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            padding: 0; 
            box-shadow: none !important; 
            border: none !important; 
        }
        .hide-on-print { display: none !important; }
        * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        @page { size: auto; margin: 15mm; }
    }
</style>

<div class="max-w-4xl mx-auto pb-12">
    {{-- KHUNG HÓA ĐƠN CHÍNH (Vuông vức, viền sắc nét) --}}
    <div class="invoice-container bg-white p-8 md:p-12 shadow-sm border border-gray-300 border-t-8 border-t-indigo-700 relative">

        {{-- 1. HEADER HÓA ĐƠN --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center border-b-2 border-gray-800 pb-6 mb-8 gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight flex items-center gap-2 uppercase">
                    <i class="fa-solid fa-store text-indigo-700"></i> 2HAND MARKET
                </h1>
                <p class="text-gray-600 text-sm mt-1">Nền tảng mua bán đồ cũ an toàn & minh bạch</p>
            </div>
            <div class="text-left md:text-right">
                <h2 class="text-3xl font-black text-gray-300 uppercase tracking-widest mb-1">HÓA ĐƠN</h2>
                <p class="font-bold text-gray-900 text-lg">Mã số: #2H{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                <p class="text-gray-600 text-sm mt-1">Ngày lập: {{ $order->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        {{-- 2. THÔNG TIN KHÁCH HÀNG & NGƯỜI BÁN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
            
            {{-- Người bán --}}
            <div class="border-l-4 border-gray-200 pl-4">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Đơn vị Bán hàng</p>
                <h4 class="font-bold text-gray-900 text-base mb-1 uppercase">{{ $order->seller->name ?? 'Người bán ẩn danh' }}</h4>
                <p class="text-sm text-gray-700 mb-1">Email: {{ $order->seller->email ?? 'N/A' }}</p>
                <p class="text-sm text-gray-700">SĐT: {{ $order->seller->phone ?? 'N/A' }}</p>
            </div>

            {{-- Người mua --}}
            <div class="border-l-4 border-gray-200 pl-4">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2">Thông tin Người mua</p>
                <h4 class="font-bold text-gray-900 text-base mb-1 uppercase">{{ $order->receiver_name ?? ($order->buyer->name ?? 'Người mua ẩn danh') }}</h4>
                <p class="text-sm text-gray-700 mb-1">Email: {{ $order->buyer->email ?? 'N/A' }}</p>
                <p class="text-sm text-gray-700">SĐT: {{ $order->phone_number ?? ($order->buyer->phone ?? 'N/A') }}</p>
            </div>

            {{-- Địa chỉ nhận hàng --}}
            <div class="bg-gray-50 p-4 border border-gray-300">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-2 border-b border-gray-300 pb-1">Địa chỉ Giao hàng</p>
                <p class="font-bold text-gray-900 text-sm mb-1 uppercase">{{ $order->receiver_name }}</p>
                <p class="text-sm font-bold text-indigo-700 mb-2">{{ $order->phone_number }}</p>
                <p class="text-sm text-gray-700 leading-relaxed">{{ $order->shipping_address }}</p>
            </div>
        </div>

        {{-- 3. BẢNG CHI TIẾT SẢN PHẨM --}}
        <div class="mb-10">
            <table class="w-full text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100 text-gray-800 text-xs uppercase tracking-wider font-bold border-b-2 border-gray-400">
                        <th class="py-3 px-4 w-12 text-center border-r border-gray-300">STT</th>
                        <th class="py-3 px-4 border-r border-gray-300">Tên sản phẩm / Diễn giải</th>
                        <th class="py-3 px-4 text-center w-24 border-r border-gray-300">Mã SP</th>
                        <th class="py-3 px-4 text-center w-24 border-r border-gray-300">Số lượng</th>
                        <th class="py-3 px-4 text-right w-32">Đơn giá</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-4 px-4 text-center font-bold text-gray-700 border-r border-gray-300">1</td>
                        <td class="py-4 px-4 border-r border-gray-300">
                            <p class="font-bold text-gray-900 text-sm uppercase">{{ $order->product->title ?? 'Sản phẩm đã bị xóa khỏi hệ thống' }}</p>
                            <p class="text-xs text-gray-600 mt-1 italic">Hình thức: {{ $order->payment_method == 'cod' ? 'Thanh toán khi nhận hàng (COD)' : 'Chuyển khoản ngân hàng' }}</p>
                        </td>
                        <td class="py-4 px-4 text-center font-mono text-xs text-gray-700 border-r border-gray-300">SP{{ str_pad($order->product_id ?? 0, 5, '0', STR_PAD_LEFT) }}</td>
                        <td class="py-4 px-4 text-center font-bold text-gray-800 border-r border-gray-300">1</td>
                        <td class="py-4 px-4 text-right font-black text-gray-900">{{ number_format($order->total_amount ?? 0) }}đ</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 4. TỔNG KẾT DÒNG TIỀN & TRẠNG THÁI --}}
        <div class="flex flex-col-reverse md:flex-row justify-between items-end md:items-start gap-8 border-t border-gray-300 pt-6">
            
            {{-- Trạng thái đơn hàng (Dạng con dấu sắc nét) --}}
            <div class="w-full md:w-1/2">
                <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wider mb-3">Kết quả Giao dịch</p>
                @if($order->status === 'completed')
                    <div class="inline-flex items-center gap-2 px-5 py-2 border-2 border-emerald-600 text-emerald-700 uppercase font-black text-sm tracking-wide bg-emerald-50">
                        <i class="fa-solid fa-check-double"></i> Hoàn tất thành công
                    </div>
                @elseif(in_array($order->status, ['cancelled', 'failed', 'refunded', 'rejected']))
                    <div class="inline-flex items-center gap-2 px-5 py-2 border-2 border-red-600 text-red-700 uppercase font-black text-sm tracking-wide bg-red-50">
                        <i class="fa-solid fa-xmark"></i> Đã hủy / Thất bại
                    </div>
                    @if($order->cancel_reason)
                        <p class="text-sm text-red-600 mt-2 font-medium">Lý do: {{ $order->cancel_reason }}</p>
                    @endif
                @elseif($order->status === 'shipped')
                    <div class="inline-flex items-center gap-2 px-5 py-2 border-2 border-blue-600 text-blue-700 uppercase font-black text-sm tracking-wide bg-blue-50">
                        <i class="fa-solid fa-truck-fast"></i> Đang vận chuyển
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 px-5 py-2 border-2 border-orange-500 text-orange-700 uppercase font-black text-sm tracking-wide bg-orange-50">
                        <i class="fa-solid fa-box-open"></i> Đang chờ xử lý
                    </div>
                @endif
            </div>

            {{-- Tính toán tiền (Căn lề nghiêm túc) --}}
            <div class="w-full md:w-1/2 md:max-w-sm">
                <table class="w-full text-sm">
                    <tbody>
                        <tr>
                            <td class="py-2 text-gray-700 font-medium">Tổng tiền Khách thanh toán:</td>
                            <td class="py-2 text-right font-bold text-gray-900">{{ number_format($order->total_amount ?? 0) }} đ</td>
                        </tr>
                        <tr>
                            <td class="py-2 text-gray-700 font-medium border-b border-gray-300 pb-3">Phí dịch vụ 2HAND (Trừ vào người bán):</td>
                            <td class="py-2 text-right border-b border-gray-300 pb-3">
                                @if(in_array($order->status, ['cancelled', 'failed', 'refunded', 'rejected']))
                                    <span class="font-bold text-gray-400 line-through">0 đ</span>
                                @else
                                    <span class="font-bold text-red-600">- {{ number_format($order->fee_amount ?? 0) }} đ</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="py-4 text-gray-900 font-black uppercase text-base">Người Bán Thực Nhận:</td>
                            <td class="py-4 text-right font-black text-indigo-700 text-xl">
                                @if(in_array($order->status, ['cancelled', 'failed', 'refunded', 'rejected']))
                                    0 đ
                                @else
                                    {{ number_format(($order->total_amount ?? 0) - ($order->fee_amount ?? 0)) }} đ
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Chữ ký / Ký tên --}}
        <div class="flex justify-between items-start mt-16 pt-8 text-center px-8">
            <div>
                <p class="font-bold text-gray-800 mb-16 uppercase text-sm">Người Mua Hàng</p>
                <p class="text-gray-500 text-sm">(Ký, ghi rõ họ tên)</p>
            </div>
            <div>
                <p class="font-bold text-gray-800 mb-16 uppercase text-sm">Đại diện 2HAND MARKET</p>
                <p class="text-gray-500 text-sm">(Ký, ghi rõ họ tên)</p>
            </div>
        </div>

    </div>
</div>
@endsection