@extends('layouts.app')

@section('title', 'Hướng dẫn thanh toán COD - 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Thanh toán COD</span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12 text-center">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>
            
            <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-6">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>

            <h2 class="text-3xl font-black text-gray-900 mb-6">Giao hàng Nhận tiền (COD)</h2>
            <p class="text-gray-600 leading-relaxed font-medium mb-6">COD (Cash On Delivery) là hình thức thanh toán khi nhận hàng. Tại 2HAND, quy trình này diễn ra như sau:</p>

            <ul class="text-left max-w-2xl mx-auto space-y-4 bg-gray-50 p-6 rounded-2xl border border-gray-100">
                <li><i class="fa-solid fa-1 text-emerald-500 mr-2"></i> Người mua bấm "Đặt hàng" và chọn COD.</li>
                <li><i class="fa-solid fa-2 text-emerald-500 mr-2"></i> Người bán tự đóng gói và gửi qua đơn vị vận chuyển (GHTK, Viettel Post...).</li>
                <li><i class="fa-solid fa-3 text-emerald-500 mr-2"></i> Bưu tá giao hàng. Người mua kiểm tra đúng món đồ mới trả tiền cho bưu tá.</li>
                <li><i class="fa-solid fa-4 text-emerald-500 mr-2"></i> Người mua vào trang "Đơn hàng" bấm "Đã nhận được hàng" để hoàn tất giao dịch!</li>
            </ul>
        </div>
    </div>
</div>
@endsection