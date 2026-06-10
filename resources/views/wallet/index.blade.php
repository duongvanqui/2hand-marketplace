@extends('layouts.app')

@section('title', 'Ví 2HAND của bạn')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- SIDEBAR BÊN TRÁI (HIỆU ỨNG THU PHÓNG GIỐNG DASHBOARD) --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0 uppercase">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="transition-opacity duration-300 overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-green-500 font-medium">
                    {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                </span>
            </div>
        </div>

        <nav class="flex-1 p-3 space-y-1">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            {{-- Nút Tổng quan (Chuyển về trạng thái bình thường) --}}
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>

            {{-- Nút Quản lý Đơn hàng --}}
            <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-clipboard-list text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Quản lý Đơn hàng</span>
            </a>

            {{-- NÚT VÍ 2HAND (ĐANG ACTIVE - MÀU XANH) --}}
            <a href="{{ route('wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 rounded-xl font-medium transition-all">
                <i class="fa-solid fa-wallet text-lg w-6 shrink-0 text-center"></i>
                <span x-show="sidebarOpen">Ví 2HAND</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quản trị hệ thống</p>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Tổng quan Admin</span>
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý Tài khoản</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-list text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý Danh mục</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-box text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý sản phẩm</span>
                </a>
                
                <a href="{{ route('admin.wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-vault text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản trị tài chính</span>
                </a>
            </div>
            @endif
        </nav>
    </aside>

    {{-- NỘI DUNG CHÍNH (VÍ 2HAND) --}}
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                {{-- Nút bấm thu/phóng Sidebar --}}
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2 transition-all">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Quản lý Ví 2HAND</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 text-emerald-800 p-4 rounded-xl mb-6 font-bold"><i class="fa-solid fa-check"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-800 p-4 rounded-xl mb-6 font-bold"><i class="fa-solid fa-triangle-exclamation"></i> {{ session('error') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- CỘT TRÁI: THÔNG TIN SỐ DƯ & NÚT RÚT TIỀN --}}
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-3xl p-6 shadow-lg shadow-emerald-200 text-white relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <p class="text-emerald-100 font-medium mb-1"><i class="fa-solid fa-wallet"></i> Số dư khả dụng</p>
                    <h2 class="text-4xl font-black mb-6">{{ number_format($user->balance) }} <span class="text-xl">VND</span></h2>
                    <p class="text-xs text-emerald-100">Chủ ví: {{ $user->name }}</p>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 mb-4 border-b pb-2">Tạo lệnh rút tiền</h3>
                    <form action="{{ route('wallet.withdraw') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Số tiền cần rút (Tối thiểu 50k)</label>
                            <input type="number" name="amount" min="50000" max="{{ $user->balance }}" required class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-emerald-500 transition-all" placeholder="VD: 100000">
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Thông tin nhận tiền</label>
                            <textarea name="bank_info" required rows="3" class="w-full mt-1 p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-emerald-500 transition-all" placeholder="VD: Vietcombank - 0123456789 - DUONG VAN QUI"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-black transition-all shadow-md {{ $user->balance < 50000 ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg' }}" {{ $user->balance < 50000 ? 'disabled' : '' }}>
                            Gửi Yêu Cầu Rút
                        </button>
                    </form>
                </div>
            </div>

            {{-- CỘT PHẢI: LỊCH SỬ GIAO DỊCH --}}
            <div class="lg:col-span-2">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 h-full">
                    <h3 class="font-bold text-lg text-gray-800 mb-6">Lịch sử giao dịch</h3>
                    
                    <div class="space-y-4">
                        {{-- Hiển thị các lệnh rút tiền (Tiền ra) --}}
                        @foreach($withdrawals as $w)
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 transition-all hover:shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-arrow-up"></i></div>
                                    <div>
                                        <p class="font-bold text-gray-800 leading-tight">Rút tiền về ngân hàng</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $w->created_at->format('H:i d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-black text-red-600">-{{ number_format($w->amount) }}đ</p>
                                    @if($w->status == 'pending') <span class="text-[10px] bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded font-bold">Đang xử lý</span>
                                    @elseif($w->status == 'approved') <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-bold">Thành công</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Hiển thị tiền bán hàng (Tiền vào) --}}
                        @foreach($incomeHistory as $order)
                            <div class="flex items-center justify-between p-4 bg-emerald-50/30 rounded-xl border border-emerald-100 transition-all hover:shadow-sm">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i class="fa-solid fa-arrow-down"></i></div>
                                    <div>
                                        <p class="font-bold text-gray-800 leading-tight">Bán: {{ Str::limit($order->product->title, 40) }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Người mua đã xác nhận nhận hàng</p>
                                    </div>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-black text-emerald-600">+{{ number_format($order->seller_amount) }}đ</p>
                                    <span class="text-[10px] text-gray-400">Đã trừ {{ number_format($order->fee_amount) }}đ phí</span>
                                </div>
                            </div>
                        @endforeach

                        @if($withdrawals->isEmpty() && $incomeHistory->isEmpty())
                            <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <i class="fa-solid fa-receipt text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500 font-medium">Chưa có giao dịch nào phát sinh.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection