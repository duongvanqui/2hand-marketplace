<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận mật khẩu - 2HAND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 min-h-screen relative flex flex-col justify-center items-center overflow-x-hidden bg-gray-50 py-10">

    <div class="absolute top-0 -left-4 w-96 h-96 bg-red-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-50 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-orange-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-50 animate-blob animation-delay-2000"></div>

    <div class="relative z-10 w-full max-w-md px-4">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8 md:p-10">
            
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm border border-red-200">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-800">Khu vực bảo mật</h2>
            </div>

            <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed text-center">
                Đây là khu vực bảo mật an toàn của ứng dụng. Vui lòng xác nhận lại mật khẩu của bạn trước khi tiếp tục thao tác.
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
                @csrf

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">Mật khẩu hiện tại</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                               class="w-full pl-11 pr-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 transition-all font-medium text-gray-900">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-medium mt-1.5 ml-1"><i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-bold py-3.5 rounded-xl shadow-lg transition-all text-sm uppercase tracking-wide flex justify-center items-center gap-2">
                        Xác nhận mật khẩu <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>