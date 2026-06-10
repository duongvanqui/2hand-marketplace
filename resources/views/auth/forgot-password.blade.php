<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu - 2HAND</title>
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

<body class="font-sans antialiased text-gray-900 min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-50">

    {{-- KỸ THUẬT TẠO NỀN KHÔNG CẦN ẢNH (Đồng bộ với trang Login) --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-4000"></div>

    {{-- Khối Form Quên Mật Khẩu --}}
    <div class="relative z-10 w-full max-w-md px-4">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8 md:p-10">
            
            {{-- Logo Brand --}}
            <div class="text-center mb-6">
                <a href="{{ url('/') }}" class="inline-flex flex-col items-center justify-center">
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-2xl mb-2 shadow-sm border border-green-200">
                        <i class="fa-solid fa-unlock-keyhole"></i>
                    </div>
                    <span class="text-3xl font-black text-green-700 tracking-tighter uppercase">2HAND</span>
                </a>
            </div>

            {{-- Dòng thông báo hướng dẫn --}}
            <div class="mb-6 text-sm text-gray-500 font-medium text-center leading-relaxed">
                Quên mật khẩu? Không sao cả. Chỉ cần cho chúng tôi biết email của bạn, hệ thống sẽ gửi một liên kết để bạn đặt lại mật khẩu mới.
            </div>

            {{-- Thông báo trạng thái gửi email thành công --}}
            @if (session('status'))
                <div class="mb-6 font-medium text-sm text-green-700 bg-green-50 border border-green-200 p-4 rounded-xl flex items-center gap-2 shadow-sm">
                    <i class="fa-solid fa-circle-check text-green-500 text-lg"></i>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email của bạn</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                               placeholder="nhapemail@gmail.com"
                               class="w-full pl-11 pr-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium text-gray-900 placeholder-gray-400">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-medium mt-1.5 ml-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nút Gửi liên kết --}}
                <div class="pt-2">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide">
                        <i class="fa-regular fa-paper-plane"></i> Gửi liên kết đặt lại
                    </button>
                </div>
            </form>

            {{-- Quay lại đăng nhập --}}
            <div class="mt-8 pt-6 border-t border-gray-200/60 text-center">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 font-medium hover:text-green-700 transition flex items-center justify-center gap-1.5">
                    <i class="fa-solid fa-arrow-left"></i> Quay lại trang đăng nhập
                </a>
            </div>
            
        </div>
    </div>
</body>
</html>