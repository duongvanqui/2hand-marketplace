@extends('layouts.app')

@section('title', 'Quản lý đơn hàng - 2HAND')

@section('content')
{{-- Gộp 2 biến Alpine.js: Điều khiển Menu và Điều khiển Tab --}}
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true, tab: 'buying' }">

    {{-- ================= SIDEBAR (MENU TRÁI) ================= --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-emerald-500 flex items-center justify-center text-white font-bold shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="transition-opacity duration-300 overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-emerald-500 font-medium">
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

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>

             <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-clipboard-list text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-wallet text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
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

    {{-- ================= CONTENT (NỘI DUNG BÊN PHẢI) ================= --}}
    <main class="flex-1 p-8 overflow-y-auto">
        
        {{-- Header của Content --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2 outline-none">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Quản lý đơn hàng</h1>
            </div>
        </div>

        {{-- HIỂN THỊ THÔNG BÁO --}}
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl shadow-sm font-medium mb-6 flex items-center gap-3">
                <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-xl shadow-sm font-medium mb-6 flex items-center gap-3">
                <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i> {{ session('error') }}
            </div>
        @endif

        {{-- THANH CHUYỂN TAB --}}
        <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 inline-flex mb-6">
            <button @click="tab = 'buying'" :class="tab === 'buying' ? 'bg-emerald-50 text-emerald-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm transition-all outline-none">
                <i class="fa-solid fa-bag-shopping mr-1"></i> Đơn mua ({{ count($buyingOrders) }})
            </button>
            <button @click="tab = 'selling'" :class="tab === 'selling' ? 'bg-emerald-50 text-emerald-600 font-bold' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-2 rounded-lg text-sm transition-all outline-none">
                <i class="fa-solid fa-store mr-1"></i> Đơn bán ({{ count($sellingOrders) }})
            </button>
        </div>

        {{-- TAB 1: ĐƠN HÀNG TÔI MUA --}}
        <div x-show="tab === 'buying'" class="space-y-4">
            @forelse($buyingOrders as $order)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 hover:border-emerald-200 transition-colors">
                    <div class="flex gap-4 items-center">
                        <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                            @if($order->product && $order->product->images && $order->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50"><i class="fa-regular fa-image text-2xl"></i></div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 text-lg">{{ $order->product ? $order->product->title : 'Sản phẩm không tồn tại' }}</h4>
                            <p class="text-xs text-gray-500 mt-1">Người bán: <span class="font-semibold">{{ $order->seller->name }}</span></p>
                            
                            <div class="mt-2 text-sm flex items-center gap-2">
                                <span class="font-black text-red-600">{{ number_format($order->total_amount) }}đ</span>
                                @if($order->payment_method === 'cod')
                                    <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-gray-200">COD</span>
                                @else
                                    <span class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase border border-blue-100">Bảo đảm</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Nút trạng thái --}}
                    <div class="flex flex-col items-end gap-2 w-full md:w-auto pt-4 md:pt-0 border-t md:border-0 border-gray-50">
                        @if($order->status === 'pending_shipping' || $order->status === 'paid_escrow')
                            <span class="bg-blue-50 text-blue-700 text-xs font-bold px-4 py-2 rounded-lg border border-blue-100"><i class="fa-solid fa-hourglass-half mr-1"></i> Chờ gửi hàng</span>
                        @elseif($order->status === 'shipped')
                            <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-4 py-2 rounded-lg border border-yellow-100 mb-1"><i class="fa-solid fa-truck-fast mr-1"></i> Đang giao hàng</span>
                            <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" onclick="return confirm('Bạn chắc chắn đã nhận đúng hàng?')" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-4 py-2 rounded-lg text-xs transition shadow-sm">
                                    <i class="fa-solid fa-circle-check mr-1"></i> ĐÃ NHẬN ĐƯỢC HÀNG
                                </button>
                            </form>
                        @elseif($order->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-4 py-2 rounded-lg border border-emerald-100"><i class="fa-solid fa-check-double mr-1"></i> Hoàn tất</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-3xl border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fa-solid fa-box-open text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Bạn chưa mua đơn hàng nào.</p>
                </div>
            @endempty
        </div>

        {{-- TAB 2: ĐƠN HÀNG TÔI BÁN --}}
        <div x-show="tab === 'selling'" class="space-y-4" style="display: none;">
            @forelse($sellingOrders as $order)
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col xl:flex-row justify-between items-start gap-4 hover:border-emerald-200 transition-colors">
                    
                    {{-- SP INFO --}}
                    <div class="flex gap-4 items-start w-full xl:w-2/5">
                        <div class="w-20 h-20 bg-gray-50 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                            @if($order->product && $order->product->images && $order->product->images->count() > 0)
                                <img src="{{ asset('storage/' . $order->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50"><i class="fa-regular fa-image text-2xl"></i></div>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 line-clamp-2">{{ $order->product ? $order->product->title : 'Sản phẩm không tồn tại' }}</h4>
                            <div class="text-sm mt-2">
                                <span class="font-black text-gray-900">{{ number_format($order->total_amount) }}đ</span>
                                <span class="ml-2 text-[11px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100">Thực nhận: {{ number_format($order->seller_amount) }}đ</span>
                            </div>
                        </div>
                    </div>

                    {{-- GIAO ĐẾN --}}
                    <div class="w-full xl:w-2/5 bg-gray-50 p-3 rounded-xl border border-gray-100 text-sm">
                        <p class="font-bold text-gray-800 mb-1 border-b border-gray-200 pb-1"><i class="fa-solid fa-location-dot text-red-500 mr-1"></i> Giao đến</p>
                        <p class="mt-1 text-gray-600"><span class="font-semibold text-gray-900">{{ $order->receiver_name }}</span> - {{ $order->phone_number }}</p>
                        <p class="text-gray-500 truncate" title="{{ $order->shipping_address }}">{{ $order->shipping_address }}</p>
                    </div>

                    {{-- TRẠNG THÁI --}}
                    <div class="flex flex-col items-end gap-2 w-full xl:w-1/5 pt-2 xl:pt-0">
                        @if($order->status === 'pending_shipping' || $order->status === 'paid_escrow')
                            @if($order->payment_method === 'cod')
                                <span class="text-gray-600 text-xs font-bold mb-2"><i class="fa-solid fa-hand-holding-dollar mr-1"></i> Khách chọn COD</span>
                            @else
                                <span class="text-blue-600 text-xs font-bold mb-2"><i class="fa-solid fa-shield-halved mr-1"></i> Đã nhận cọc Web</span>
                            @endif
                            <form action="{{ route('orders.ship', $order->id) }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-xs transition shadow-sm">
                                    XÁC NHẬN ĐÃ GỬI HÀNG
                                </button>
                            </form>
                        @elseif($order->status === 'shipped')
                            <span class="bg-yellow-50 text-yellow-700 text-xs font-bold px-4 py-2 rounded-lg border border-yellow-100"><i class="fa-solid fa-truck-fast mr-1"></i> Đang vận chuyển</span>
                        @elseif($order->status === 'completed')
                            <span class="bg-emerald-50 text-emerald-700 text-xs font-bold px-4 py-2 rounded-lg border border-emerald-100"><i class="fa-solid fa-check-double mr-1"></i> Hoàn tất</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white rounded-3xl border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-300">
                        <i class="fa-solid fa-store-slash text-3xl"></i>
                    </div>
                    <p class="text-gray-500 font-medium">Bạn chưa có đơn bán nào.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection