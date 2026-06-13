@extends('layouts.admin')

@section('title', 'Ví 2HAND của bạn')
@section('header_title', 'Quản lý Ví 2HAND')

@section('content')
<div class="pb-10">
    
    {{-- HIỂN THỊ THÔNG BÁO --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-check"></i></div>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-triangle-exclamation"></i></div>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- ============================================== --}}
        {{-- CỘT TRÁI: THÔNG TIN SỐ DƯ & NÚT RÚT TIỀN --}}
        {{-- ============================================== --}}
        <div class="lg:col-span-1 space-y-6">
            
            {{-- Thẻ Số Dư (Design kiểu Credit Card) --}}
            <div class="relative bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-3xl p-8 shadow-xl shadow-emerald-200/50 text-white overflow-hidden transform transition-transform hover:-translate-y-1 duration-300">
                {{-- Họa tiết trang trí (Decorations) --}}
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-teal-900 opacity-20 rounded-full blur-xl"></div>
                <i class="fa-brands fa-nfc-symbol absolute top-8 right-8 text-3xl opacity-50"></i>

                <div class="relative z-10">
                    <p class="text-emerald-50 font-semibold mb-2 text-sm uppercase tracking-wider flex items-center gap-2">
                        <i class="fa-solid fa-wallet"></i> Số dư khả dụng
                    </p>
                    <h2 class="text-4xl font-black mb-8 tracking-tight drop-shadow-sm">
                        {{ number_format($user->balance) }} <span class="text-xl font-bold opacity-80">VND</span>
                    </h2>
                    
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] text-emerald-100 uppercase tracking-widest mb-0.5">Chủ ví</p>
                            <p class="font-bold text-lg tracking-wide uppercase">{{ $user->name }}</p>
                        </div>
                        <div class="text-emerald-100/50 font-black text-2xl italic">
                            2HAND
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Yêu cầu Rút tiền --}}
            <div class="bg-white p-6 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-money-bill-transfer text-emerald-500"></i> Tạo lệnh rút tiền
                </h3>
                
                <form action="{{ route('wallet.withdraw') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Số tiền cần rút (Tối thiểu 50k)</label>
                        <div class="relative mt-1.5">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-gray-400 font-bold">₫</span>
                            </div>
                            <input type="number" name="amount" min="50000" max="{{ $user->balance }}" required 
                                class="w-full pl-10 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition-all font-bold text-gray-800" 
                                placeholder="VD: 100000">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase tracking-wide">Thông tin nhận tiền</label>
                        <textarea name="bank_info" required rows="3" 
                            class="w-full mt-1.5 p-4 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition-all text-sm text-gray-800 resize-none" 
                            placeholder="VD: Vietcombank&#10;STK: 0123456789&#10;Tên: DUONG VAN QUI"></textarea>
                    </div>

                    <button type="submit" 
                        class="w-full font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2
                        {{ $user->balance < 50000 ? 'bg-gray-200 text-gray-400 cursor-not-allowed' : 'bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5' }}" 
                        {{ $user->balance < 50000 ? 'disabled' : '' }}>
                        <i class="fa-solid fa-paper-plane"></i> Gửi Yêu Cầu Rút
                    </button>
                    
                    @if($user->balance < 50000)
                        <p class="text-[11px] text-center text-red-500 font-medium mt-2"><i class="fa-solid fa-circle-info"></i> Số dư của bạn chưa đạt mức tối thiểu để rút.</p>
                    @endif
                </form>
            </div>
        </div>

        {{-- ============================================== --}}
        {{-- CỘT PHẢI: LỊCH SỬ GIAO DỊCH --}}
        {{-- ============================================== --}}
        <div class="lg:col-span-2">
            <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 h-full flex flex-col">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-50">
                    <h3 class="font-bold text-xl text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-clock-rotate-left text-emerald-500"></i> Lịch sử giao dịch
                    </h3>
                </div>
                
                <div class="space-y-4 flex-1">
                    
                    {{-- DANH SÁCH TIỀN RA (RÚT TIỀN) --}}
                    @foreach($withdrawals as $w)
                        <div class="flex items-center justify-between p-4 bg-white rounded-2xl border border-gray-100 transition-all hover:shadow-md hover:border-gray-200 group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-arrow-up-right-dots text-lg"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 text-sm md:text-base leading-tight">Yêu cầu rút tiền</p>
                                    <p class="text-[11px] text-gray-400 mt-1 font-medium"><i class="fa-regular fa-clock mr-1"></i> {{ $w->created_at->format('H:i - d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-red-500 text-base md:text-lg">-{{ number_format($w->amount) }}<span class="text-xs ml-0.5">đ</span></p>
                                <div class="mt-1">
                                    @if($w->status == 'pending') 
                                        <span class="inline-flex items-center gap-1 text-[10px] bg-yellow-50 text-yellow-700 px-2 py-1 rounded-md font-bold border border-yellow-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Đang xử lý
                                        </span>
                                    @elseif($w->status == 'approved') 
                                        <span class="inline-flex items-center gap-1 text-[10px] bg-emerald-50 text-emerald-700 px-2 py-1 rounded-md font-bold border border-emerald-100">
                                            <i class="fa-solid fa-check"></i> Thành công
                                        </span>
                                    @elseif($w->status == 'rejected')
                                        <span class="inline-flex items-center gap-1 text-[10px] bg-red-50 text-red-700 px-2 py-1 rounded-md font-bold border border-red-100">
                                            <i class="fa-solid fa-xmark"></i> Bị từ chối
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- DANH SÁCH TIỀN VÀO (BÁN HÀNG) --}}
                    @foreach($incomeHistory as $order)
                        <div class="flex items-center justify-between p-4 bg-emerald-50/30 rounded-2xl border border-emerald-100/50 transition-all hover:shadow-md hover:border-emerald-200 group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <i class="fa-solid fa-arrow-down-long text-lg"></i>
                                </div>
                                <div class="max-w-[150px] sm:max-w-[250px] lg:max-w-[300px]">
                                    <p class="font-bold text-gray-800 text-sm md:text-base leading-tight truncate">Tiền hàng: {{ $order->product->title }}</p>
                                    <p class="text-[11px] text-gray-500 mt-1 font-medium truncate"><i class="fa-solid fa-box-open mr-1 text-gray-400"></i> Người mua đã xác nhận</p>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="font-black text-emerald-600 text-base md:text-lg">+{{ number_format($order->seller_amount) }}<span class="text-xs ml-0.5">đ</span></p>
                                <p class="text-[10px] text-gray-400 font-medium mt-1" title="Đã trừ {{ number_format($order->fee_amount) }}đ phí sàn">Phí sàn: -{{ number_format($order->fee_amount) }}đ</p>
                            </div>
                        </div>
                    @endforeach

                    {{-- TRẠNG THÁI TRỐNG --}}
                    @if($withdrawals->isEmpty() && $incomeHistory->isEmpty())
                        <div class="text-center py-16 bg-gray-50/50 rounded-2xl border border-dashed border-gray-200 h-full flex flex-col items-center justify-center">
                            <div class="w-20 h-20 bg-white rounded-full shadow-sm flex items-center justify-center mb-4">
                                <i class="fa-solid fa-receipt text-4xl text-gray-300"></i>
                            </div>
                            <h4 class="font-bold text-gray-700 mb-1">Chưa có giao dịch</h4>
                            <p class="text-sm text-gray-500 font-medium max-w-xs">Ví của bạn hiện tại chưa có biến động số dư nào. Đăng bán sản phẩm để bắt đầu nhé!</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection