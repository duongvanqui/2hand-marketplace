@extends('layouts.admin')

@section('title', 'Tất cả thông báo - 2HAND')

{{-- Thanh Breadcrumb điều hướng --}}
@section('header_title')
<div class="flex flex-col">
    <span class="text-2xl font-black text-gray-900 tracking-tight">
        Tất cả thông báo
    </span>
    <div class="text-sm text-gray-500 font-medium mt-1 flex items-center gap-2">
        <a href="{{ url('/') }}" class="hover:text-emerald-600 transition-colors">Trang chủ</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <a href="{{ route('dashboard') }}" class="hover:text-emerald-600 transition-colors">Quản lý cá nhân</a>
        <i class="fa-solid fa-angle-right text-[10px] text-gray-400 mx-1"></i>
        <span class="text-gray-900 font-bold">Thông báo</span>
    </div>
</div>
@endsection

@section('header_actions')
<button onclick="markAllAsReadFull()" class="px-5 py-2.5 bg-white text-emerald-600 font-bold rounded-xl text-sm hover:bg-emerald-50 transition-all shadow-sm border border-emerald-100 flex items-center gap-2">
    <i class="fa-solid fa-check-double"></i> Đánh dấu tất cả đã đọc
</button>
@endsection

@section('content')
<div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[calc(100vh-170px)] flex flex-col">
    
    <div class="p-6 border-b border-gray-50 flex items-center justify-between shrink-0 bg-gray-50/30">
        <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
            <i class="fa-solid fa-bell text-emerald-500"></i> Lịch sử thông báo của bạn
        </h3>
    </div>

    <div class="flex-1 flex flex-col">
        @if($notifications->count() == 0)
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400 py-20">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 border border-gray-100 shadow-inner">
                    <i class="fa-regular fa-bell-slash text-4xl text-gray-300"></i>
                </div>
                <h4 class="text-lg font-black text-gray-600 mb-1">Trống rỗng!</h4>
                <p class="text-sm font-medium">Bạn chưa nhận được thông báo nào từ hệ thống.</p>
            </div>
        @else
            <div class="divide-y divide-gray-50">
                @foreach($notifications as $noti)
                    @php
                        $isRead = $noti->read_at !== null;
                        $type = $noti->data['type'] ?? 'info';
                        
                        $colorClass = match($type) {
                            'success' => 'bg-green-100 text-green-600 border-green-200/50',
                            'warning' => 'bg-orange-100 text-orange-600 border-orange-200/50',
                            'danger'  => 'bg-red-100 text-red-500 border-red-200/50',
                            default   => 'bg-blue-100 text-blue-600 border-blue-200/50',
                        };

                        // =================================================================
                        // [BẢO MẬT LOGIC]: BẺ LÁI LINK ĐỂ TRÁNH LỖI VÒNG LẶP (ERR_TOO_MANY_REDIRECTS)
                        // =================================================================
                        $notiTitle = strtolower($noti->data['title'] ?? '');
                        $notiMsg = strtolower($noti->data['message'] ?? '');
                        
                        $notiUrl = route('notifications.click', $noti->id);
                        $isRedirectPrevented = false;

                        // Nếu là SP bị khóa / từ chối -> Đẩy về trang "Sản phẩm của tôi"
                        if (str_contains($notiMsg, 'khóa') || str_contains($notiMsg, 'vi phạm') || str_contains($notiTitle, 'từ chối') || str_contains($notiMsg, 'từ chối')) {
                            $notiUrl = route('my.products');
                            $isRedirectPrevented = true;
                        } 
                        // Nếu là đẩy tin -> Đẩy ra trang chủ
                        elseif (str_contains($notiTitle, 'đẩy lên trang đầu') || str_contains($notiTitle, 'đẩy tin')) {
                            $notiUrl = url('/');
                            $isRedirectPrevented = true;
                        }
                    @endphp

                    <div class="noti-wrapper relative group transition-all duration-300 border-b border-gray-50 {{ $isRead ? 'bg-white hover:bg-gray-50' : 'bg-blue-50/30 hover:bg-blue-50/50' }}">
                        
                        @if(!$isRead)
                            <div class="full-noti-bar absolute inset-y-0 left-0 w-1 bg-blue-500 z-10"></div>
                        @endif

                        {{-- Bắt sự kiện click để đổi UI (tắt chấm xanh) nếu dùng link bẻ lái --}}
                        <a href="{{ $notiUrl }}" 
                           @if($isRedirectPrevented && !$isRead) onclick="markAsReadUI(this)" @endif
                           class="flex items-start gap-4 p-5 pr-14 cursor-pointer">
                            <div class="w-12 h-12 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform shadow-sm border mt-1">
                                <i class="fa-solid {{ $noti->data['icon'] ?? 'fa-bell' }} text-lg"></i>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base font-bold {{ $isRead ? 'text-gray-700' : 'text-gray-900' }} mb-1">
                                    {{ $noti->data['title'] ?? 'Thông báo' }}
                                </h4>
                                <p class="text-sm {{ $isRead ? 'text-gray-500' : 'text-gray-700' }} leading-relaxed max-w-4xl">
                                    {!! $noti->data['message'] ?? '' !!}
                                </p>
                                <p class="text-xs {{ $isRead ? 'text-gray-400' : 'text-blue-600' }} font-bold mt-2 flex items-center gap-1.5">
                                    <i class="fa-regular fa-clock"></i> {{ $noti->created_at->diffForHumans() }} 
                                    <span class="text-gray-300 font-normal mx-1">•</span> 
                                    <span class="text-gray-400 font-medium">{{ $noti->created_at->format('H:i - d/m/Y') }}</span>
                                </p>
                            </div>
                        </a>

                        {{-- Nút Xóa --}}
                        <button onclick="deleteNotification('{{ $noti->id }}', this)" class="absolute right-5 top-1/2 -translate-y-1/2 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full opacity-0 group-hover:opacity-100 transition-all shadow-sm" title="Xóa thông báo này">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            
            @if($notifications->hasPages())
                <div class="p-6 border-t border-gray-100 bg-gray-50 shrink-0">
                    {{ $notifications->links() }}
                </div>
            @endif
        @endif
    </div>
</div>

<script>
    // Hàm UI: Tạo cảm giác đã đọc ngay lập tức khi click vào các link bị bẻ lái
    function markAsReadUI(element) {
        const wrapper = element.closest('.noti-wrapper');
        if (wrapper) {
            wrapper.classList.remove('bg-blue-50/30', 'hover:bg-blue-50/50');
            wrapper.classList.add('bg-white', 'hover:bg-gray-50');
            
            const bar = wrapper.querySelector('.full-noti-bar');
            if(bar) bar.remove();
            
            const title = wrapper.querySelector('h4');
            const msg = wrapper.querySelector('p.leading-relaxed');
            const time = wrapper.querySelector('p.text-xs');
            
            if(title) { title.classList.remove('text-gray-900'); title.classList.add('text-gray-700'); }
            if(msg) { msg.classList.remove('text-gray-700'); msg.classList.add('text-gray-500'); }
            if(time) { time.classList.remove('text-blue-600'); time.classList.add('text-gray-400'); }
        }
    }

    function markAllAsReadFull() {
        if(!confirm('Đánh dấu tất cả thông báo là đã đọc?')) return;
        
        fetch('/notifications/mark-as-read', {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                document.querySelectorAll('.noti-wrapper').forEach(el => markAsReadUI(el.querySelector('a')));
                window.dispatchEvent(new CustomEvent('update-noti-count', { detail: 0 }));
            }
        });
    }

    function deleteNotification(id, btnElement) {
        if(!confirm('Bạn có chắc chắn muốn xóa thông báo này?')) return;

        fetch(`/notifications/${id}`, {
            method: 'DELETE',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                'Accept': 'application/json'
            }
        }).then(res => res.json())
        .then(data => {
            if(data.success) {
                const wrapper = btnElement.closest('.noti-wrapper');
                wrapper.style.transition = "all 0.3s ease-out";
                wrapper.style.opacity = '0';
                wrapper.style.transform = 'scale(0.95)';
                setTimeout(() => wrapper.remove(), 300);
            }
        });
    }
</script>
@endsection