@extends('layouts.app')

@section('title', 'Giới thiệu về 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        
        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Giới thiệu về chúng tôi</span>
        </div>

        {{-- Tiêu đề --}}
        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-5 shadow-lg shadow-emerald-200 transform rotate-3 hover:rotate-6 transition-transform">
                <i class="fa-solid fa-hand-holding-hand"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">Về <span class="text-emerald-500">2HAND</span></h1>
            <p class="text-lg text-gray-500 font-medium">Nền tảng mua bán đồ cũ an toàn, tiện lợi và minh bạch nhất.</p>
        </div>

        {{-- Nội dung --}}
        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12 space-y-8 text-gray-600 font-medium leading-relaxed">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>

            <section>
                <h2 class="text-2xl font-black text-gray-900 mb-4 flex items-center gap-2"><i class="fa-solid fa-leaf text-emerald-500"></i> Sứ mệnh của chúng tôi</h2>
                <p class="mb-4">Tại 2HAND, chúng tôi tin rằng mỗi món đồ đều có vòng đời thứ hai ý nghĩa. Việc tái sử dụng không chỉ giúp bạn tiết kiệm chi phí mà còn đóng góp to lớn vào việc bảo vệ môi trường, giảm thiểu rác thải tiêu dùng.</p>
            </section>

            <hr class="border-gray-100">

            <section>
                <h2 class="text-2xl font-black text-gray-900 mb-4 flex items-center gap-2"><i class="fa-solid fa-shield-halved text-emerald-500"></i> Giá trị cốt lõi</h2>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <div class="mt-1 w-6 h-6 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-xs"></i></div>
                        <p><strong>Giao dịch an toàn:</strong> Tích hợp tính năng Thanh toán COD và hệ thống Đánh giá người dùng chặt chẽ.</p>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="mt-1 w-6 h-6 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center shrink-0"><i class="fa-solid fa-check text-xs"></i></div>
                        <p><strong>Giao diện thân thiện:</strong> Trải nghiệm mua sắm mượt mà, tốc độ cao, hỗ trợ chat trực tiếp ngay trên hệ thống.</p>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</div>
@endsection