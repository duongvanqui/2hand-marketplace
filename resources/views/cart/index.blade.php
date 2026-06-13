@extends('layouts.app')

@section('title', 'Giỏ hàng của bạn - 2HAND')

@section('content')
{{-- ĐÃ BỔ SUNG: Wrapper tạo nền Glassmorphism (Kính mờ) --}}
<div class="relative min-h-screen bg-gray-50 overflow-hidden">
    {{-- Các khối màu gradient chuyển động phía sau --}}
    <div class="absolute top-0 -left-4 w-96 h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-50"></div>
    <div class="absolute -bottom-8 right-20 w-96 h-96 bg-teal-200 rounded-full mix-blend-multiply filter blur-[80px] opacity-50"></div>

    <main class="max-w-7xl mx-auto px-4 py-8 flex-grow w-full relative z-10">

        {{-- BREADCRUMB --}}
        <nav class="flex text-sm text-gray-500 mb-6 font-medium" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ url('/') }}" class="hover:text-green-600 transition"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                        <span class="text-gray-900 ml-1 font-bold">Giỏ hàng của bạn</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 flex items-center gap-3">
                Giỏ hàng của bạn <span class="bg-green-100 text-green-700 text-sm font-bold px-3 py-1 rounded-full">{{ count($cartItems) }} món</span>
            </h1>
        </div>

        {{-- HIỂN THỊ THÔNG BÁO --}}
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl shadow-sm font-medium mb-8 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-sm font-medium mb-8 flex items-center gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i> {{ session('error') }}
        </div>
        @endif

        @if(count($cartItems) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- CỘT TRÁI: DANH SÁCH SẢN PHẨM --}}
            <div class="lg:col-span-7 space-y-5">
                @foreach($cartItems as $item)
                @php
                $product = $item->product;
                @endphp

                @if(!$product) @continue @endif

                {{-- ĐÃ BỔ SUNG: bg-white/80 và backdrop-blur-xl cho sản phẩm --}}
                <div class="bg-white/80 backdrop-blur-xl p-5 rounded-3xl shadow-sm border {{ $product->status === 'sold' ? 'border-red-200 bg-red-50/40' : 'border-white hover:shadow-md transition-shadow' }} flex gap-5 items-center relative group">

                    <a href="{{ route('products.show', $product->slug) }}" class="w-24 h-24 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100 block {{ $product->status === 'sold' ? 'opacity-50 grayscale' : '' }}">
                        @if($product->images->count() > 0)
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" alt="{{ $product->title }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image text-3xl"></i></div>
                        @endif
                    </a>

                    <div class="flex-grow flex flex-col justify-between h-24 py-1">
                        <div>
                            <a href="{{ route('products.show', $product->slug) }}" class="text-lg font-bold text-gray-800 hover:text-green-600 transition line-clamp-2 leading-snug pr-8">
                                {{ $product->title }}
                            </a>

                            @if($product->status === 'sold')
                            <span class="inline-block mt-2 text-xs font-bold text-red-600 bg-red-100 px-2 py-1 rounded border border-red-200"><i class="fa-solid fa-lock mr-1"></i> Đã bán cho người khác</span>
                            @else
                            <span class="inline-block mt-2 text-xs font-semibold text-gray-500 bg-gray-100 px-2 py-1 rounded">Số lượng: 1 (Duy nhất)</span>
                            @endif
                        </div>
                        <p class="font-black text-xl {{ $product->status === 'sold' ? 'line-through text-gray-400' : 'text-red-600' }}">{{ number_format($product->price) }} <span class="underline decoration-2 text-base">đ</span></p>
                    </div>

                    <form action="{{ route('cart.remove', $product->id) }}" method="POST" class="absolute top-4 right-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500 hover:bg-red-50 w-8 h-8 rounded-full flex items-center justify-center transition-colors" title="Xóa sản phẩm">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- CỘT PHẢI: FORM CHỐT ĐƠN --}}
            <div class="lg:col-span-5">
                {{-- ĐÃ BỔ SUNG: bg-white/80 và backdrop-blur-xl cho form --}}
                <form action="{{ route('orders.checkout.store') }}" method="POST" class="bg-white/80 backdrop-blur-xl p-6 rounded-3xl shadow-xl border border-white sticky top-24">
                    @csrf
                    
                    <h3 class="font-bold text-lg text-gray-900 border-b border-gray-200 pb-4 mb-5">Thông tin giao hàng & Thanh toán</h3>

                    <div class="space-y-4 mb-6">
                        {{-- ĐÃ BỔ SUNG: Trường Nhập Tên Người Nhận --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Tên người nhận <span class="text-red-500">*</span></label>
                            <input type="text" name="receiver_name" value="{{ Auth::check() ? Auth::user()->name : '' }}" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all bg-white/50" placeholder="Nhập họ và tên">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Số điện thoại người nhận <span class="text-red-500">*</span></label>
                            {{-- Tự động lấy số điện thoại (phone) từ tài khoản --}}
                            <input type="text" name="phone_number" value="{{ Auth::check() ? Auth::user()->phone : '' }}" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all bg-white/50" placeholder="VD: 0987654321">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Địa chỉ giao hàng <span class="text-red-500">*</span></label>
                            {{-- Tự động lấy địa chỉ (address) từ tài khoản --}}
                            <textarea name="shipping_address" required rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all bg-white/50" placeholder="Số nhà, Tên đường, Phường/Xã, Quận/Huyện...">{{ Auth::check() ? Auth::user()->address : '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Phương thức thanh toán <span class="text-red-500">*</span></label>
                            <select name="payment_method" required class="w-full px-4 py-2 border border-gray-200 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-100 outline-none transition-all bg-white/50 cursor-pointer">
                                {{-- ĐÃ BỔ SUNG: Tùy chọn COD --}}
                                <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                                <option value="banking">Thanh toán bảo đảm qua Chuyển khoản ngân hàng</option>
                                <option value="wallet">Thanh toán qua Ví điện tử (Momo/ZaloPay)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6 bg-white/50 p-4 rounded-xl border border-gray-100">
                        <div class="flex justify-between items-center text-gray-600 text-sm">
                            <span>Tạm tính</span>
                            <span class="font-bold">{{ number_format($total) }} đ</span>
                        </div>
                        <div class="flex justify-between items-center text-gray-600 text-sm">
                            <span>Phí giao hàng</span>
                            <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded">Thỏa thuận</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-end border-t border-gray-200 pt-5 mb-6">
                        <span class="font-bold text-gray-800 text-lg">Tổng thanh toán</span>
                        <div class="text-right">
                            <span class="block text-3xl font-black text-red-600">{{ number_format($total) }} đ</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-4 rounded-xl shadow-lg shadow-green-200 transition-all uppercase tracking-wide flex justify-center items-center gap-2 mb-4 text-sm {{ $total == 0 ? 'opacity-50 cursor-not-allowed' : '' }}" {{ $total == 0 ? 'disabled' : '' }}>
                        Tiến hành chốt đơn <i class="fa-solid fa-shield-halved"></i>
                    </button>

                    <p class="text-xs text-center text-gray-500 font-medium">Tiền của bạn sẽ được 2HAND giữ an toàn cho đến khi bạn xác nhận đã nhận được hàng đúng mô tả.</p>
                </form>
            </div>
        </div>
        @else
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-sm border border-white p-16 text-center max-w-2xl mx-auto mt-10 relative z-10">
            <div class="w-32 h-32 bg-green-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="fa-solid fa-cart-shopping text-6xl text-green-300 animate-bounce"></i>
            </div>
            <h2 class="text-2xl font-black text-gray-800 mb-3">Giỏ hàng của bạn đang trống!</h2>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">Có vẻ như bạn chưa chọn được món đồ nào. Hãy lướt một vòng chợ để tìm những deal cực hời nhé!</p>
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-green-200 transition-all">
                <i class="fa-solid fa-store"></i> Tiếp tục mua sắm
            </a>
        </div>
        @endif
    </main>
</div>
@endsection