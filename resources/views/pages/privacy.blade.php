@extends('layouts.app')

@section('title', 'Chính sách bảo mật - 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Chính sách bảo mật</span>
        </div>

        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-5 shadow-lg shadow-emerald-200 transform transition-transform hover:scale-110">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 tracking-tight">Chính sách bảo mật</h1>
            <p class="text-lg text-gray-500 font-medium">Bảo vệ quyền riêng tư và dữ liệu cá nhân của bạn là ưu tiên hàng đầu.</p>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12 space-y-8 text-gray-600 font-medium leading-relaxed">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>

            <section>
                <h2 class="text-xl font-black text-gray-900 mb-3">Thu thập thông tin cá nhân</h2>
                <p>Chúng tôi chỉ thu thập thông tin khi bạn đăng ký tài khoản (Tên, Email, Số điện thoại) và các thông tin cần thiết phục vụ cho việc vận chuyển giao dịch. Dữ liệu này được sử dụng riêng để xác thực và hỗ trợ bảo vệ bạn trên nền tảng.</p>
            </section>
            
            <section>
                <h2 class="text-xl font-black text-gray-900 mb-3">Cam kết bảo mật</h2>
                <p>Chúng tôi sử dụng các biện pháp mã hóa an toàn nhất hiện nay (Bcrypt cho mật khẩu, bảo mật Transport Layer) để đảm bảo thông tin của bạn không bị đánh cắp hay tiết lộ cho bất kỳ bên thứ 3 nào với mục đích thương mại.</p>
            </section>
        </div>
    </div>
</div>
@endsection