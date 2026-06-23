@extends('layouts.app')

@section('title', 'Trung tâm trợ giúp - 2HAND')

@section('content')
<div class="bg-gray-50 py-10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
            <i class="fa-solid fa-angle-right text-[10px] text-gray-400"></i>
            <span class="text-emerald-600 font-bold">Trung tâm trợ giúp</span>
        </div>

        <div class="text-center mb-10">
            <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-5 shadow-lg shadow-emerald-200">
                <i class="fa-solid fa-life-ring"></i>
            </div>
            <h1 class="text-4xl font-black text-gray-900 mb-4 tracking-tight">Trung tâm trợ giúp</h1>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden relative p-8 md:p-12 space-y-6">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-green-600"></div>

            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <h3 class="font-bold text-gray-900 text-lg mb-2">Làm sao để đăng bán một món đồ?</h3>
                <p class="text-gray-600">Bạn cần Đăng nhập/Đăng ký tài khoản. Sau đó bấm vào nút "Đăng tin" ở góc phải màn hình, điền đầy đủ thông tin mô tả, chụp ảnh thật của sản phẩm và gửi duyệt.</p>
            </div>

            <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                <h3 class="font-bold text-gray-900 text-lg mb-2">Tôi bị người mua/bán lừa đảo thì phải làm sao?</h3>
                <p class="text-gray-600">Hãy dùng tính năng "Báo cáo" ngay tại trang sản phẩm hoặc trang chat. Cung cấp hình ảnh bằng chứng, Admin sẽ lập tức khóa tài khoản vi phạm và hỗ trợ bạn.</p>
            </div>
        </div>
    </div>
</div>
@endsection