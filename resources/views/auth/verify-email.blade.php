<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh Email - 2HAND</title>
    
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
        <div class="bg-white/80 backdrop-blur-xl rounded-[2rem] shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-white p-8 md:p-10 text-center">
            
            <div class="mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-green-600 rounded-2xl flex items-center justify-center text-white text-3xl mx-auto mb-4 shadow-lg shadow-emerald-200/50 transform transition-transform hover:scale-110">
                    <i class="fa-regular fa-envelope-open"></i>
                </div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight">Xác minh Email</h2>
            </div>

            <div class="mb-6 text-sm text-gray-500 font-medium leading-relaxed">
                Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào liên kết chúng tôi vừa gửi qua email. Nếu không nhận được, chúng tôi sẽ gửi lại cho bạn.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-medium text-sm text-emerald-700 bg-emerald-50 border border-emerald-100 p-4 rounded-xl flex items-start gap-2 shadow-sm text-left">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg mt-0.5"></i>
                    <span>Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.</span>
                </div>
            @endif

            <div class="mt-8 flex flex-col items-center justify-center gap-4">
                {{-- Nút gửi lại Email --}}
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wider">
                        <i class="fa-regular fa-paper-plane"></i> Gửi lại Email xác minh
                    </button>
                </form>

                {{-- Nút Đăng xuất --}}
                <form method="POST" action="{{ route('logout') }}" class="mt-2 w-full">
                    @csrf
                    <button type="submit" class="w-full text-sm text-gray-500 font-medium hover:text-red-500 hover:bg-red-50 py-2.5 rounded-xl transition-colors">
                        Đăng xuất tài khoản
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>