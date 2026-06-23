@extends('layouts.app')

@section('title', 'Hướng dẫn mua bán an toàn - 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Mua bán an toàn</span>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>
            
            <h2 class="text-3xl font-black text-gray-900 mb-6 text-center"><i class="fa-solid fa-shield text-emerald-500 mr-2"></i> Mẹo giao dịch an toàn</h2>
            
            <div class="space-y-4 text-gray-600 leading-relaxed font-medium">
                <p><strong class="text-gray-900">1. Đừng chuyển tiền trước khi nhận hàng:</strong> Trừ khi bạn và người bán có giao dịch thông qua cổng thanh toán bảo đảm của sàn, TUYỆT ĐỐI không chuyển khoản đặt cọc trước cho người lạ.</p>
                <p><strong class="text-gray-900">2. Kiểm tra kỹ hàng hóa lúc nhận (Đồng kiểm):</strong> Hãy yêu cầu bưu tá cho xem hàng trước khi trả tiền (COD).</p>
                <p><strong class="text-gray-900">3. Chat trực tiếp trên sàn:</strong> Mọi bằng chứng lừa đảo chỉ có hiệu lực khi bạn nhắn tin thỏa thuận qua hệ thống Chat của 2HAND. Đừng chuyển sang Zalo/Zalo để tránh rủi ro mất dấu vết.</p>
            </div>
        </div>
    </div>
</div>
@endsection