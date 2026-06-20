@extends('layouts.admin')

@section('title', 'Hộp thư tin nhắn - 2HAND')
@section('header_title', 'Hộp thư tin nhắn')

@section('content')
{{-- KHỐI CONTAINER LỚN PHONG CÁCH MODERN UI ĐỒNG BỘ FLOATING CHAT --}}
<div class="bg-white rounded-3xl shadow-[0_20px_50px_-15px_rgba(0,0,0,0.1)] border-2 border-gray-100 flex overflow-hidden h-[calc(100vh-160px)]">

    {{-- ========================================== --}}
    {{-- CỘT TRÁI: DANH SÁCH CUỘC TRÒ CHUYỆN       --}}
    {{-- ========================================== --}}
    <div class="w-1/3 min-w-[320px] max-w-[380px] bg-gray-50/50 border-r-2 border-gray-100 flex flex-col z-10 relative shadow-[5px_0_15px_-5px_rgba(0,0,0,0.03)]">

        {{-- Header Hộp thư Logo 2HAND --}}
        <div class="p-5 border-b-2 border-gray-100 bg-white/80 backdrop-blur-md sticky top-0 shrink-0 z-20 flex justify-between items-center">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 bg-gradient-to-br from-green-400 to-emerald-600 rounded-xl flex items-center justify-center text-white text-sm shadow-md shadow-emerald-200/50 border border-emerald-400/50">
                    <i class="fa-solid fa-hand-holding-hand"></i>
                </div>
                <span class="text-2xl font-black uppercase tracking-tighter">
                    <span class="text-gray-900">2</span><span class="bg-gradient-to-r from-green-50 to-emerald-600 bg-clip-text text-transparent">HAND</span>
                </span>
            </div>
            <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-500 flex items-center justify-center font-bold text-xs shadow-inner border border-gray-200" title="Tổng số cuộc trò chuyện">
                {{ $conversations->count() }}
            </div>
        </div>

        {{-- Danh sách item cuộn --}}
        <div id="full-chat-list" class="flex-1 overflow-y-auto no-scrollbar p-3 space-y-2">
            @if($conversations->isEmpty())
                <div id="full-empty-list-notice" class="text-center text-gray-400 h-full flex flex-col items-center justify-center py-10">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3 border-2 border-white shadow-sm">
                        <i class="fa-regular fa-comments text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-xs font-bold text-gray-500">Hộp thư trống</p>
                </div>
            @else
                @foreach($conversations as $conv)
                    @php
                        $isBuyer = $conv->buyer_id === Auth::id();
                        $partner = $isBuyer ? $conv->seller : $conv->buyer;
                        $avatarUrl = $partner->avatar ? asset('storage/' . $partner->avatar) : '';
                        $firstLetter = substr($partner->name, 0, 1);

                        $roleText = $conv->product ? ($isBuyer ? 'Người bán' : 'Người mua') : '';
                        $unreadInConv = \App\Models\Message::where('conversation_id', $conv->id)->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();

                        // Kiểm tra khóa chat nếu sản phẩm đã bán cho người khác
                        $locked = false;
                        if($conv->product && $conv->product->status === 'sold' && $conv->buyer_id != $conv->product->buyer_id) {
                            $locked = true; 
                        }
                    @endphp

                    <div onclick="loadFullConversation({{ $conv->id }}, '{{ addslashes($partner->name) }}', {{ $locked ? 'true' : 'false' }})"
                         class="conv-item-full p-3 rounded-2xl border-2 border-transparent hover:border-emerald-100 hover:bg-emerald-50/50 cursor-pointer transition-all flex items-center gap-3 relative group"
                         id="full-conv-{{ $conv->id }}"
                         data-name="{{ $partner->name }}">

                        {{-- Avatar tròn trịa viền nổi --}}
                        <div class="w-12 h-12 bg-gradient-to-tr from-emerald-100 to-teal-50 text-emerald-600 rounded-2xl flex items-center justify-center font-black text-lg shrink-0 border-2 border-white shadow-sm overflow-hidden relative">
                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}" class="w-full h-full object-cover">
                            @else
                                {{ $firstLetter }}
                            @endif
                        </div>

                        {{-- Nội dung text vắn tắt --}}
                        <div class="flex-1 overflow-hidden space-y-1">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-1.5 truncate pr-1">
                                    <h4 class="font-black text-gray-900 text-sm truncate">{{ $partner->name }}</h4>
                                    @if($unreadInConv > 0)
                                        <span id="unread-badge-{{ $conv->id }}" class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-md border border-white animate-pulse">
                                            {{ $unreadInConv > 99 ? '99+' : $unreadInConv }}
                                        </span>
                                    @endif
                                </div>
                                @if($roleText)
                                    <span class="text-[8px] font-bold bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded-md whitespace-nowrap">{{ $roleText }}</span>
                                @endif
                            </div>
                            
                            @if($conv->product)
                                <p class="text-[10px] text-emerald-600 font-bold truncate">
                                    <i class="fa-solid fa-box-open text-emerald-400 mr-1"></i> {{ $conv->product->title }}
                                </p>
                            @else
                                <p class="text-[10px] text-blue-500 font-bold truncate">
                                    <i class="fa-solid fa-comments text-blue-400 mr-1"></i> Trò chuyện trực tiếp
                                </p>
                            @endif
                        </div>

                        @if($locked)
                            <div class="absolute inset-0 bg-gray-50/50 backdrop-blur-[1px] flex items-center justify-center rounded-2xl">
                                <i class="fa-solid fa-lock text-gray-400 text-xl drop-shadow-md"></i>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- CỘT PHẢI: KHUNG TRÒ CHUYỆN CHI TIẾT        --}}
    {{-- ========================================== --}}
    <div class="flex-1 flex flex-col bg-white relative h-full">

        {{-- Trạng thái trống khi chưa chọn phòng chat --}}
        <div id="full-empty-state" class="absolute inset-0 z-20 bg-gray-50/40 backdrop-blur-sm flex flex-col items-center justify-center text-gray-400">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mb-4 border-4 border-emerald-50 shadow-md">
                <i class="fa-solid fa-paper-plane text-4xl text-emerald-400 ml-[-4px] mt-[4px]"></i>
            </div>
            <p class="text-sm font-bold text-gray-500 bg-white px-6 py-2 rounded-full shadow-sm border border-gray-100">Chọn cuộc trò chuyện để bắt đầu</p>
        </div>

        {{-- HEADER PHÒNG CHAT CHỌN --}}
        <div class="px-5 py-4 border-b-2 border-gray-100 bg-white flex justify-between items-center h-[70px] shrink-0 shadow-[0_5px_15px_-10px_rgba(0,0,0,0.05)]">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 border border-emerald-100 flex items-center justify-center hidden" id="active-chat-avatar">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
                <div>
                    <h3 id="full-partner-name" class="font-black text-gray-900 text-lg leading-tight">...</h3>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="flex items-center gap-1.5 text-[10px] text-emerald-500 font-bold">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_4px_#10b981]"></span> Đang hoạt động
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="deleteFullChat()" id="delete-chat-btn" disabled class="w-9 h-9 rounded-xl bg-white border-2 border-transparent hover:bg-red-50 hover:text-red-500 hover:border-red-200 text-gray-400 flex items-center justify-center shadow-sm disabled:opacity-0 transition-all" title="Xóa đoạn chat">
                    <i class="fa-solid fa-trash-can text-sm"></i>
                </button>
            </div>
        </div>

        {{-- KHU VỰC HIỂN THỊ TIN NHẮN gradient --}}
        <div id="full-chat-box" class="flex-1 overflow-y-auto p-5 bg-gray-50/30 flex flex-col gap-3 shadow-inner"></div>

        {{-- KHU VỰC PREVIEW XEM TRƯỚC ẢNH CHỌN --}}
        <div id="image-preview-container" class="hidden px-5 py-3 bg-white border-t-2 border-gray-100 shrink-0">
            <div class="relative inline-block">
                <img id="image-preview" src="" class="h-16 rounded-xl border-2 border-gray-200 object-cover shadow-sm">
                <button type="button" id="remove-image-btn" class="absolute -top-2.5 -right-2.5 w-6 h-6 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600 shadow-md border-2 border-white transition-transform hover:scale-110">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        {{-- THÔNG BÁO KHÓA CHAT KHI SẢN PHẨM ĐÃ BÁN --}}
        <div id="locked-chat-alert" class="hidden px-5 py-4 bg-gray-100 border-t-2 border-gray-200 text-center text-gray-500 font-bold text-sm">
            <i class="fa-solid fa-lock mr-1"></i> Sản phẩm đã bán. Phòng chat đã bị đóng.
        </div>

        {{-- FORM NHẬP TIN NHẮN (ĐỒNG BỘ ICON ẢNH XANH DƯƠNG NỔI BẬT) --}}
        <div id="full-form-container" class="px-5 py-4 border-t-2 border-gray-100 bg-white shrink-0">
            <form id="full-chat-form" class="flex items-center gap-3">
                
                {{-- Nút chọn ảnh Xanh Dương Nổi bật đồng bộ hoàn hảo --}}
                <label id="chat-image-label" class="p-2.5 bg-blue-50 border-2 border-blue-200 text-blue-500 hover:text-blue-600 hover:bg-blue-100 hover:border-blue-300 rounded-xl cursor-pointer transition-all shadow-sm flex items-center justify-center opacity-50 pointer-events-none" title="Gửi ảnh">
                    <i class="fa-solid fa-camera-retro text-lg"></i>
                    <input type="file" id="image-input" accept="image/png, image/jpeg, image/webp" class="hidden" disabled>
                </label>

                <input type="text" id="full-message-input" disabled class="flex-grow bg-white border-2 border-gray-200 rounded-2xl px-4 py-2.5 text-sm font-medium focus:border-emerald-500 focus:ring-4 focus:ring-emerald-50 outline-none disabled:opacity-50 transition-all shadow-sm" placeholder="Viết tin nhắn..." autocomplete="off">
                
                <button type="submit" id="full-submit-btn" disabled class="p-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-xl shadow-md shadow-emerald-200/50 hover:from-emerald-600 hover:to-green-700 transition-all disabled:opacity-50 disabled:shadow-none flex items-center justify-center transform hover:-translate-y-0.5 active:translate-y-0">
                    <i class="fa-solid fa-paper-plane text-lg ml-[-2px] mt-[2px]"></i>
                </button>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<style>
    /* CSS Active Item đồng bộ */
    .conv-item-full.active-conv {
        background-color: #ecfdf5 !important;
        border-color: #a7f3d0 !important;
    }
</style>

<script>
    let activeFullConvId = null;
    const currentUserId = {{ Auth::id() }};

    const fullChatBox = document.getElementById('full-chat-box');
    const fullChatForm = document.getElementById('full-chat-form');
    const fullMsgInput = document.getElementById('full-message-input');
    const fullSubmitBtn = document.getElementById('full-submit-btn');
    const imageInput = document.getElementById('image-input');
    const previewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');
    const deleteChatBtn = document.getElementById('delete-chat-btn');
    const activeChatAvatar = document.getElementById('active-chat-avatar');
    const formContainer = document.getElementById('full-form-container');
    const lockedAlert = document.getElementById('locked-chat-alert');

    // Xử lý hiện ảnh xem trước khi người dùng bấm chọn file
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });

    // Nút hủy xóa ảnh đã chọn
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        previewContainer.classList.add('hidden');
        imagePreview.src = '';
    });

    // Hàm chèn mã HTML tin nhắn (Bong bóng chat Modern UI Gradient giống hệt Floating)
    function appendFullMessage(msg, isMe) {
        const alignWrapper = isMe ? 'flex justify-end' : 'flex justify-start';
        const roundedClass = isMe ? 'rounded-2xl rounded-tr-sm' : 'rounded-2xl rounded-tl-sm';
        const bubbleClass = isMe 
            ? 'bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-md shadow-emerald-200 border border-emerald-400' 
            : 'bg-white text-gray-800 shadow-sm border-2 border-gray-100';

        let contentHTML = '';
        if (msg.image_path && msg.image_path.startsWith('data:image')) {
            contentHTML += `<img src="${msg.image_path}" class="rounded-xl mb-1.5 max-w-[180px] object-cover border-2 border-white/20 shadow-sm">`;
        } else if (msg.image_path) {
            contentHTML += `<a href="/storage/${msg.image_path}" target="_blank"><img src="/storage/${msg.image_path}" class="rounded-xl mb-1.5 max-w-[180px] object-cover border-2 border-white/20 shadow-sm hover:opacity-90 transition"></a>`;
        }

        if (msg.message) {
            const safeMsg = msg.message.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/\n/g, '<br>');
            contentHTML += `<p class="text-[13px] font-medium leading-relaxed">${safeMsg}</p>`;
        }

        const html = `
            <div class="${alignWrapper} animate-fade-in-up mt-1">
                <div class="max-w-[80%] px-4 py-2.5 ${roundedClass} ${bubbleClass}">
                    ${contentHTML}
                </div>
            </div>`;
        fullChatBox.insertAdjacentHTML('beforeend', html);
        fullChatBox.scrollTop = fullChatBox.scrollHeight;
    }

    // Hàm click kích hoạt tải phòng chat
    window.loadFullConversation = function(convId, partnerName, isLocked = false) {
        activeFullConvId = convId;
        document.getElementById('full-empty-state').style.display = 'none';
        activeChatAvatar.classList.remove('hidden');
        document.getElementById('full-partner-name').innerText = partnerName;
        deleteChatBtn.disabled = false;

        // Bật tắt trạng thái khóa nếu sản phẩm đã bán nhầm cho người khác
        if (isLocked) {
            formContainer.classList.add('hidden');
            lockedAlert.classList.remove('hidden');
        } else {
            formContainer.classList.remove('hidden');
            lockedAlert.classList.add('hidden');
            fullMsgInput.disabled = false;
            fullSubmitBtn.disabled = false;
            imageInput.disabled = false;
            document.getElementById('chat-image-label').classList.remove('opacity-50', 'pointer-events-none');
        }

        // Đổi màu chọn cho thanh danh sách bên trái
        document.querySelectorAll('.conv-item-full').forEach(el => el.classList.remove('active-conv'));
        const activeListItem = document.getElementById(`full-conv-${convId}`);
        if(activeListItem) activeListItem.classList.add('active-conv');

        // Xóa thông báo số lượng chưa đọc tạm thời
        const badge = document.getElementById(`unread-badge-${convId}`);
        if (badge) {
            badge.style.display = 'none';
            if (typeof window.syncUnreadCount === 'function') {
                setTimeout(window.syncUnreadCount, 500);
            }
        }

        fullChatBox.innerHTML = '<div class="text-center text-gray-400 mt-24"><i class="fa-solid fa-circle-notch fa-spin text-4xl mb-3 text-emerald-300"></i><p class="font-bold">Đang kết nối...</p></div>';

        // Gọi API tải lịch sử tin nhắn
        fetch(`/chat/${convId}/messages`)
            .then(res => res.json())
            .then(messages => {
                fullChatBox.innerHTML = '<div class="text-center my-6 relative"><div class="absolute inset-0 flex items-center"><div class="w-full border-t-2 border-gray-100"></div></div><span class="relative bg-gray-50 px-4 py-1.5 text-[9px] font-black text-gray-400 rounded-full uppercase tracking-widest border-2 border-gray-100">Bắt đầu nhắn tin</span></div>';
                messages.forEach(msg => appendFullMessage(msg, msg.sender_id === currentUserId));
            });
    };

    // Luồng xử lý gửi dữ liệu Form bằng AJAX (Fetch API) tránh văng trang, hiển thị tức thì
    fullChatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = fullMsgInput.value.trim();
        const file = imageInput.files[0];

        if ((!text && !file) || !activeFullConvId) return;

        let formData = new FormData();
        if (text) formData.append('message', text);
        if (file) formData.append('image', file);

        // Reset dữ liệu khung nhập lập tức tăng trải nghiệm UI/UX mượt
        fullMsgInput.value = '';
        removeImageBtn.click();

        fetch(`/chat/${activeFullConvId}/messages`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                appendFullMessage(data.message, true); // Render trực tiếp kết quả trả về
            } else if (data.error) {
                alert("Lỗi: " + data.error);
            }
        })
        .catch(() => alert("Đường truyền gián đoạn hoặc tệp ảnh vượt dung lượng cho phép!"));
    });

    // Luồng lắng nghe sóng Realtime qua Kênh cá nhân user.{id} (Đồng bộ tuyệt đối)
    if (typeof window.Echo !== 'undefined' && currentUserId) {
        window.Echo.private(`user.${currentUserId}`)
            .listen('.message.sent', (e) => {
                const msg = e.message;
                
                // 1. Nếu đang mở khung chat đúng phòng này
                if (activeFullConvId === msg.conversation_id) {
                    appendFullMessage(msg, false);
                    fetch(`/chat/${msg.conversation_id}/messages`); // Gọi API để cập nhật trạng thái "Đã đọc"
                } 
                // 2. Nếu đang đóng hoặc mở phòng khác -> Nảy chấm đỏ & đẩy lên đầu list trái
                else {
                    const existingItem = document.getElementById(`full-conv-${msg.conversation_id}`);
                    if (existingItem) {
                        let badge = document.getElementById(`unread-badge-${msg.conversation_id}`);
                        if (badge) {
                            let current = parseInt(badge.innerText.replace('+', '')) || 0;
                            badge.innerText = current + 1 > 99 ? '99+' : current + 1;
                            badge.style.display = 'inline-block';
                        } else {
                            const titleContainer = existingItem.querySelector('.flex.items-center.gap-2.truncate');
                            if(titleContainer) {
                                titleContainer.insertAdjacentHTML('beforeend', `<span id="unread-badge-${msg.conversation_id}" class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full leading-none shrink-0 shadow-sm animate-pulse border border-white">1</span>`);
                            }
                        }
                        existingItem.parentNode.prepend(existingItem); // Đẩy lên Top đầu tiên
                    } else {
                        // Nhận diện người mới hoàn toàn -> Đồng bộ gọi AJAX tải lại sidebar ngầm không reload trang
                        fetch(window.location.href)
                            .then(res => res.text())
                            .then(html => {
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const freshSidebar = doc.getElementById('full-chat-list');
                                if (freshSidebar) {
                                    document.getElementById('full-chat-list').innerHTML = freshSidebar.innerHTML;
                                }
                            });
                    }
                }
            });
    }

    // Hàm xóa vĩnh viễn đoạn chat
    window.deleteFullChat = function() {
        if(!confirm('Xóa vĩnh viễn cuộc hội thoại này?')) return;
        fetch(`/chat/${activeFullConvId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                location.reload();
            }
        });
    }
</script>
@endsection