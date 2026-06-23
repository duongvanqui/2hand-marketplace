<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu - 2HAND</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        body { font-family: 'Inter', sans-serif !important; }
        
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

<body class="antialiased text-gray-900 min-h-screen relative flex items-center justify-center overflow-hidden bg-gray-50 py-10">

    {{-- KỸ THUẬT TẠO NỀN ĐỒNG BỘ --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-emerald-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 animate-blob animation-delay-4000"></div>

    <div class="relative z-10 w-full max-w-md px-4">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-8 md:p-10">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg shadow-emerald-200/50 transform transition-transform hover:scale-110">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight mb-2">Tạo mật khẩu mới</h2>
                <p class="text-gray-500 text-sm font-medium">Hãy nhập mật khẩu mới cho tài khoản của bạn.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email Address --}}
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" 
                               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all font-medium text-gray-900 shadow-sm">
                    </div>
                    @error('email')
                        <p class="text-red-500 text-xs font-bold mt-1.5 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Mật khẩu mới --}}
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1.5">Mật khẩu mới</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all font-medium text-gray-900 shadow-sm">
                    </div>
                    @error('password')
                        <p class="text-red-500 text-xs font-bold mt-1.5 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                    @enderror
                </div>

                {{-- Xác nhận mật khẩu --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1.5">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••"
                               class="w-full pl-11 pr-4 py-3 bg-white border border-gray-200 rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 transition-all font-medium text-gray-900 shadow-sm">
                    </div>
                    @error('password_confirmation')
                        <p class="text-red-500 text-xs font-bold mt-1.5 ml-1 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-3">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 transition-all text-sm uppercase tracking-wider">
                        Lưu mật khẩu mới
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>