@extends('layouts.admin')

@section('title', 'Quản trị tài chính - Admin')

{{-- Đẩy tiêu đề lên thanh Header dùng chung --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight flex items-center gap-2">
        Quản trị Tài chính & Doanh thu
    </span>
    <p class="text-sm text-gray-500 font-medium mt-1">Kiểm soát dòng tiền phí sàn và các yêu cầu rút tiền từ người dùng</p>
</div>
@endsection

@section('content')
<div class="pb-10">

    {{-- ========================================== --}}
    {{-- KHỐI HIỂN THỊ THÔNG BÁO FLASH --}}
    {{-- ========================================== --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-check"></i></div>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded-2xl shadow-sm font-bold mb-6 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-xmark"></i></div>
            {{ session('error') }}
        </div>
    @endif

    {{-- ========================================== --}}
    {{-- 1. THỐNG KÊ TÀI CHÍNH (2 CARDS LỚN) --}}
    {{-- ========================================== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
        
        {{-- Card: Tổng Doanh Thu --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between relative overflow-hidden z-10">
            <div class="absolute right-0 top-0 w-40 h-40 bg-emerald-50 rounded-full blur-3xl -z-10 group-hover:bg-emerald-100 transition-colors"></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                    Tổng phí sàn thu được (3%)
                </p>
                <h3 class="text-4xl font-black text-gray-900">{{ number_format($totalRevenue) }}<span class="text-2xl text-gray-400 font-bold ml-1">đ</span></h3>
                <p class="text-[11px] font-bold text-emerald-600 mt-3 bg-emerald-50 px-2.5 py-1.5 rounded-lg border border-emerald-100 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-vault"></i> Tiền đã cộng vào tài khoản hệ thống
                </p>
            </div>
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-tr from-emerald-500 to-green-400 text-white flex items-center justify-center text-3xl md:text-4xl shadow-lg shadow-emerald-200 group-hover:scale-110 group-hover:-rotate-6 transition-transform shrink-0">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
        </div>
        
        {{-- Card: Lệnh rút tiền --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 hover:shadow-lg transition-all group flex items-center justify-between relative overflow-hidden z-10">
            <div class="absolute right-0 top-0 w-40 h-40 bg-blue-50 rounded-full blur-3xl -z-10 group-hover:bg-blue-100 transition-colors"></div>
            <div>
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 flex items-center gap-2">
                    Lệnh rút tiền chờ duyệt
                </p>
                <h3 class="text-4xl font-black text-gray-900">{{ count($pendingWithdrawals) }}<span class="text-xl text-gray-400 font-bold ml-2 tracking-normal">yêu cầu</span></h3>
                <p class="text-[11px] font-bold text-blue-600 mt-3 bg-blue-50 px-2.5 py-1.5 rounded-lg border border-blue-100 inline-flex items-center gap-1.5">
                    <i class="fa-solid fa-bell animate-pulse"></i> Cần Admin xử lý chuyển khoản
                </p>
            </div>
            <div class="w-16 h-16 md:w-20 md:h-20 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-500 text-white flex items-center justify-center text-3xl md:text-4xl shadow-lg shadow-blue-200 group-hover:scale-110 group-hover:rotate-6 transition-transform shrink-0">
                <i class="fa-solid fa-money-bill-transfer"></i>
            </div>
        </div>

    </div>

    {{-- ========================================== --}}
    {{-- 2. BẢNG DANH SÁCH YÊU CẦU RÚT TIỀN --}}
    {{-- ========================================== --}}
    <div class="bg-white rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] border border-gray-100 overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
            <h3 class="font-black text-gray-800 text-lg flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Yêu cầu rút tiền chờ xử lý
            </h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-500 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                        <th class="p-5 w-16 text-center font-black text-gray-400">#</th>
                        <th class="p-5">Người yêu cầu</th>
                        <th class="p-5">Số tiền rút</th>
                        <th class="p-5">Thông tin ngân hàng nhận</th>
                        <th class="p-5 text-center">Hành động</th>
                    </tr>
                </thead>
                <tbody class="text-sm divide-y divide-gray-50">
                    @forelse($pendingWithdrawals as $index => $w)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="p-5 text-center font-bold text-gray-400">{{ $index + 1 }}</td>
                        
                        {{-- Cột Người Yêu Cầu --}}
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 font-black text-sm flex items-center justify-center shrink-0 border border-blue-100 shadow-sm">
                                    {{ substr($w->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $w->user->name }}</p>
                                    <p class="text-[11px] text-gray-500">{{ $w->user->email }}</p>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $w->created_at->format('H:i d/m/Y') }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Cột Số Tiền --}}
                        <td class="p-5">
                            <span class="text-lg font-black text-red-500">{{ number_format($w->amount) }}<span class="text-sm underline ml-0.5">đ</span></span>
                        </td>

                        {{-- Cột Ngân hàng --}}
                        <td class="p-5">
                            <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100 text-sm font-medium text-gray-800 whitespace-pre-line leading-relaxed max-w-sm">
                                <i class="fa-solid fa-building-columns text-gray-400 mr-1.5"></i> {{ $w->bank_info }}
                            </div>
                        </td>

                        {{-- Cột Hành động --}}
                        <td class="p-5">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                
                                {{-- Nút Duyệt --}}
                                <form action="{{ route('admin.wallet.approve', $w->id) }}" method="POST" onsubmit="return confirm('CẢNH BÁO: Bạn XÁC NHẬN đã chuyển khoản thành công số tiền này cho người dùng qua ứng dụng Ngân hàng của bạn?')">
                                    @csrf @method('PATCH')
                                    <button class="flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-500 hover:text-white rounded-xl text-xs font-bold transition-all border border-emerald-100 hover:border-emerald-500 shadow-sm" title="Duyệt chuyển tiền">
                                        <i class="fa-solid fa-check"></i> Duyệt chi
                                    </button>
                                </form>

                                {{-- Nút Từ chối --}}
                                <form action="{{ route('admin.wallet.reject', $w->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn TỪ CHỐI lệnh rút tiền này? Tiền sẽ được hoàn lại vào ví 2HAND của người dùng.')">
                                    @csrf @method('PATCH')
                                    <button class="flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-600 hover:bg-red-500 hover:text-white rounded-xl text-xs font-bold transition-all border border-red-100 hover:border-red-500 shadow-sm" title="Từ chối lệnh rút">
                                        <i class="fa-solid fa-xmark"></i> Từ chối
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-16 text-center text-gray-400">
                            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-emerald-100 shadow-inner">
                                <i class="fa-solid fa-check-double text-3xl text-emerald-500"></i>
                            </div>
                            <p class="font-bold text-gray-700 text-lg mb-1">Tuyệt vời!</p>
                            <p class="font-medium text-gray-500 text-sm">Hiện tại không có yêu cầu rút tiền nào đang tồn đọng.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection