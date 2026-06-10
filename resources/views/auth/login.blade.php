<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - 2HAND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        /* Hiệu ứng chuyển động nhẹ nhàng cho các khối màu phía sau */
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 7s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
    </style>
</head>

{{-- Nền xám nhạt bao phủ toàn trang --}}
<body class="font-sans antialiased text-gray-900 min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-50">

    {{-- KỸ THUẬT TẠO NỀN KHÔNG CẦN ẢNH: 
         Tạo 3 khối màu gradient tròn, làm nhòe mạnh (blur-3xl) và cho chuyển động nhẹ --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-4000"></div>

    {{-- Khối Form Đăng Nhập (Hiệu ứng Kính mờ - Glassmorphism) --}}
    <div class="relative z-10 w-full max-w-md px-4">
        {{-- bg-white/70 và backdrop-blur-xl tạo độ trong suốt và mờ cho thẻ form --}}
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8 md:p-10">
            
            {{-- Logo Brand --}}
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center justify-center">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-2 shadow-sm border border-green-200">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span class="text-3xl font-black text-green-700 tracking-tighter uppercase">2HAND</span>
                </a>
                <p class="text-gray-500 text-sm mt-2 font-medium">Chào mừng trở lại! Vui lòng đăng nhập.</p>
            </div>

            {{-- Thông báo lỗi hệ thống (Nếu có) --}}
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded-lg text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email của bạn</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                               placeholder="nhapemail@gmail.com"
                               class="w-full pl-11 pr-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium text-gray-900 placeholder-gray-400">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-medium mt-1.5 ml-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">Mật khẩu</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                               placeholder="••••••••"
                               class="w-full pl-11 pr-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium text-gray-900 placeholder-gray-400">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-medium mt-1.5 ml-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-green-600 shadow-sm focus:ring-green-500 w-4 h-4 cursor-pointer">
                        <span class="ml-2 text-sm text-gray-600 group-hover:text-green-700 font-medium transition">Ghi nhớ tôi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-green-600 hover:text-green-800 font-bold transition hover:underline" href="{{ route('password.request') }}">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>

                {{-- Nút Đăng nhập --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide">
                        Đăng nhập ngay <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </button>
                </div>
            </form>

            {{-- Nút Đăng ký --}}
            @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-gray-200/60 text-center">
                <p class="text-sm text-gray-600 font-medium">Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="text-green-600 font-bold hover:text-green-800 hover:underline transition ml-1">Đăng ký tại đây</a>
                </p>
            </div>
            @endif
            
        </div>
    </div>
</body>
</html>