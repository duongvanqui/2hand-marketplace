@extends('layouts.app')

@section('title', 'Quy chế hoạt động - 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Quy chế hoạt động sàn</span>
        </div>

        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-5 shadow-lg shadow-emerald-200 transform -rotate-3 hover:rotate-0 transition-transform">
                <i class="fa-solid fa-scale-balanced"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">Quy chế hoạt động</h1>
            <p class="text-lg text-gray-500 font-medium">Nguyên tắc để xây dựng một cộng đồng mua bán văn minh.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12 space-y-8 text-gray-600 font-medium leading-relaxed">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>

            <section>
                <h2 class="text-2xl font-black text-gray-900 mb-4">1. Nguyên tắc chung</h2>
                <p>Nền tảng 2HAND là nơi trung gian kết nối người mua và người bán. Các bên tham gia giao dịch trên 2HAND tự do thỏa thuận trên cơ sở tôn trọng quyền và lợi ích hợp pháp của các bên, không trái với quy định của pháp luật.</p>
            </section>

            <hr class="border-gray-100">

            <section>
                <h2 class="text-2xl font-black text-gray-900 mb-4">2. Hàng hóa cấm giao dịch</h2>
                <ul class="list-disc list-inside space-y-2 text-gray-600">
                    <li>Hàng hóa, dịch vụ cấm kinh doanh theo quy định của pháp luật (Vũ khí, chất kích thích...).</li>
                    <li>Sản phẩm giả mạo nhãn hiệu, vi phạm quyền sở hữu trí tuệ.</li>
                    <li>Các thiết bị lưu trữ dữ liệu cá nhân chưa được xóa sạch.</li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection