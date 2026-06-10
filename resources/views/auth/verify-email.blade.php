<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác minh Email - 2HAND</title>
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

    {{-- KỸ THUẬT TẠO NỀN KHÔNG CẦN ẢNH --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-green-300 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob"></div>
    <div class="absolute top-0 -right-4 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-60 animate-blob animation-delay-4000"></div>

    {{-- Khối Form Kính Mờ --}}
    <div class="relative z-10 w-full max-w-md px-4">
        <div class="bg-white/70 backdrop-blur-xl rounded-3xl shadow-2xl border border-white p-8 md:p-10 text-center">
            
            <div class="mb-6">
                <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center text-3xl mx-auto mb-4 shadow-sm border border-green-200">
                    <i class="fa-regular fa-envelope-open"></i>
                </div>
                <h2 class="text-2xl font-black text-gray-800">Xác minh Email</h2>
            </div>

            <div class="mb-6 text-sm text-gray-600 font-medium leading-relaxed">
                Cảm ơn bạn đã đăng ký! Trước khi bắt đầu, vui lòng xác minh địa chỉ email của bạn bằng cách nhấp vào liên kết chúng tôi vừa gửi qua email. Nếu không nhận được, chúng tôi sẽ gửi lại cho bạn.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-6 font-medium text-sm text-green-700 bg-green-50 border border-green-200 p-4 rounded-xl flex items-start gap-2 shadow-sm text-left">
                    <i class="fa-solid fa-circle-check text-green-500 text-lg mt-0.5"></i>
                    <span>Một liên kết xác minh mới đã được gửi đến địa chỉ email bạn đã cung cấp khi đăng ký.</span>
                </div>
            @endif

            <div class="mt-4 flex flex-col items-center justify-center gap-4">
                {{-- Nút gửi lại Email --}}
                <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2 text-sm uppercase tracking-wide">
                        <i class="fa-regular fa-paper-plane"></i> Gửi lại Email xác minh
                    </button>
                </form>

                {{-- Nút Đăng xuất --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-500 font-medium hover:text-red-500 transition underline decoration-gray-300 hover:decoration-red-500 underline-offset-4">
                        Đăng xuất tài khoản
                    </button>
                </form>
            </div>
            
        </div>
    </div>
</body>
</html>