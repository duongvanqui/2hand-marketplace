<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - 2HAND</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif !important; }
        
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

<body class="antialiased text-gray-900 min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-50">

    {{-- KỸ THUẬT TẠO NỀN KHÔNG CẦN ẢNH ĐÃ ĐƯỢC CHUYỂN SANG TÔNG XANH NGỌC --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-4000"></div>

    {{-- Khối Form Đăng Nhập (Hiệu ứng Kính mờ - Glassmorphism) --}}
    <div class="relative z-10 w-full max-w-md px-4">
        
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-8 md:p-10">
            
            {{-- ĐÃ SỬA: Logo Brand đồng bộ 100% với hệ thống --}}
            <div class="text-center mb-8">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center justify-center group outline-none">
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mb-3 shadow-lg shadow-emerald-200/50 transform transition-transform group-hover:scale-110 group-hover:rotate-3">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span class="text-4xl font-black text-gray-900 tracking-tighter uppercase">
                        2<span class="text-emerald-500">HAND</span>
                    </span>
                </a>
                <p class="text-gray-500 text-sm mt-3 font-medium">Chào mừng trở lại! Vui lòng đăng nhập.</p>
            </div>

            {{-- Thông báo lỗi hệ thống (Nếu có) --}}
            @if (session('status'))
                <div class="mb-5 font-medium text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 p-4 rounded-xl text-center flex items-center justify-center gap-2">
                    <i class="fa-solid fa-circle-check"></i> {{ session('status') }}
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
                               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all font-medium text-gray-900 placeholder-gray-400 shadow-sm">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-bold mt-1.5 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
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
                               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all font-medium text-gray-900 placeholder-gray-400 shadow-sm">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-bold mt-1.5 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                        <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500 w-4 h-4 cursor-pointer mt-0.5">
                        <span class="ml-2 text-sm text-gray-600 group-hover:text-emerald-700 font-bold transition-colors">Ghi nhớ tôi</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-emerald-600 hover:text-emerald-700 font-bold transition-colors hover:underline underline-offset-2" href="{{ route('password.request') }}">
                            Quên mật khẩu?
                        </a>
                    @endif
                </div>

                {{-- Nút Đăng nhập Gradient --}}
                <div class="pt-3">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                        Đăng nhập ngay <i class="fa-solid fa-arrow-right-to-bracket"></i>
                    </button>
                </div>
            </form>

            {{-- Nút Đăng ký --}}
            @if (Route::has('register'))
            <div class="mt-8 pt-6 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-500 font-medium">Chưa có tài khoản? 
                    <a href="{{ route('register') }}" class="text-emerald-600 font-black hover:text-emerald-700 hover:underline transition ml-1">Đăng ký tại đây</a>
                </p>
            </div>
            @endif
            
        </div>
    </div>
</body>
</html>