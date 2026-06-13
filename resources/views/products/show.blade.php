{{-- 1. Gọi file Khung xương dùng chung --}}
@extends('layouts.app')

{{-- 2. Truyền tiêu đề động cho tab trình duyệt --}}
@section('title', $product->title . ' - 2HAND')

{{-- 3. Phần nội dung riêng của trang Chi tiết sản phẩm --}}
@section('content')
<div class="max-w-6xl mx-auto px-4 pb-12">

    {{-- BREADCRUMB (Điều hướng) --}}
    <nav class="flex text-sm text-gray-500 mb-6 font-medium mt-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="hover:text-green-600 transition"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                    <a href="#" class="hover:text-green-600 ml-1 transition">{{ $product->category->name ?? 'Danh mục' }}</a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                    <span class="text-gray-400 ml-1 truncate max-w-[200px]">{{ $product->title }}</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- HIỂN THỊ THÔNG BÁO GIỎ HÀNG BÊN TRÊN SẢN PHẨM --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3 font-medium">
            <i class="fa-solid fa-circle-check text-green-500 text-xl"></i> {{ session('success') }}
        </div>
        <a href="{{ route('cart.index') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-green-700 transition">Xem giỏ hàng</a>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center gap-3 font-medium">
        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i> {{ session('error') }}
    </div>
    @endif

    {{-- KHỐI CHI TIẾT SẢN PHẨM --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">

        {{-- 1. CỘT TRÁI: ẢNH SẢN PHẨM --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="relative aspect-square bg-gray-50 rounded-2xl overflow-hidden border border-gray-100 group flex items-center justify-center">
                @if($product->images && $product->images->count() > 0)
                <img id="main-display" src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-contain transition-all duration-500 transform scale-95 group-hover:scale-100" alt="{{ $product->title }}">
                @else
                <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-100 font-medium">
                    <div class="text-center">
                        <i class="fa-regular fa-image text-4xl mb-2 block text-gray-300"></i>
                        Chưa có hình ảnh
                    </div>
                </div>
                @endif

                @if($product->images && $product->images->count() > 1)
                <button onclick="prevImg()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-white hover:text-green-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="nextImg()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-white hover:text-green-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                @endif
            </div>

            {{-- Ảnh thu nhỏ (Thumbnails) --}}
            <div class="flex justify-start gap-2 overflow-x-auto py-1 no-scrollbar">
                @if($product->images && $product->images->count() > 0)
                @foreach($product->images as $index => $img)
                <div class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 cursor-pointer transition-all thumb-item {{ $index === 0 ? 'border-green-500 ring-2 ring-green-100' : 'border-transparent opacity-60 hover:opacity-100' }}"
                    onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this, {{ $index }})">
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
                @endif
            </div>

            {{-- Công cụ chia sẻ / Báo cáo --}}
            <div class="flex justify-between items-center pt-2 text-sm text-gray-500 font-medium">
                <div class="flex gap-4">
                    <button class="hover:text-green-600 transition flex items-center gap-1"><i class="fa-regular fa-heart"></i> Lưu tin</button>
                    <button class="hover:text-green-600 transition flex items-center gap-1"><i class="fa-solid fa-share-nodes"></i> Chia sẻ</button>
                </div>
                <button class="hover:text-red-500 transition flex items-center gap-1 text-xs"><i class="fa-solid fa-flag"></i> Báo cáo tin</button>
            </div>
        </div>

        {{-- 2. CỘT PHẢI: THÔNG TIN SẢN PHẨM & NGƯỜI BÁN --}}
        <div class="lg:col-span-7 flex flex-col">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">{{ $product->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded text-gray-700">
                        <i class="fa-regular fa-clock"></i> Đăng {{ $product->created_at->diffForHumans() }}
                    </span>
                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $product->location }}</span>
                    <span class="flex items-center gap-1"><i class="fa-regular fa-eye"></i> {{ $product->view_count }} lượt xem</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-2xl mt-6 border border-green-100">
                <p class="text-gray-600 text-sm font-medium mb-1">Giá thanh lý:</p>
                <div class="flex items-end gap-3">
                    <p class="text-4xl font-black text-red-600">{{ number_format($product->price) }} <span class="text-2xl underline decoration-2">đ</span></p>
                    @if($product->original_price && $product->original_price > $product->price)
                    <p class="text-lg text-gray-400 line-through font-semibold mb-1">{{ number_format($product->original_price) }} đ</p>
                    @endif
                </div>
            </div>

            {{-- THẺ NGƯỜI BÁN --}}
            <div class="flex items-center justify-between p-4 mt-6 border border-gray-200 rounded-xl">
                <div class="flex items-center gap-4">
                    @if($product->user && $product->user->avatar)
                    <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-12 h-12 rounded-full object-cover border border-green-200">
                    @else
                    <div class="w-12 h-12 bg-green-100 text-green-700 font-bold text-xl rounded-full flex items-center justify-center border border-green-200 shrink-0">
                        {{ substr($product->user->name ?? 'U', 0, 1) }}
                    </div>
                    @endif

                    <div>
                        <p class="font-bold text-gray-900">{{ $product->user->name ?? 'Người bán ẩn danh' }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                            <span class="flex items-center text-yellow-500"><i class="fa-solid fa-star mr-1"></i> 5.0</span>
                            <span>•</span>
                            <span>Hoạt động 5 phút trước</span>
                        </div>
                    </div>
                </div>
                <a href="#" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-bold rounded-lg hover:bg-gray-50 transition">
                    Xem trang
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="border border-gray-200 p-4 rounded-xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-medal"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Tình trạng</p>
                        <p class="font-bold text-gray-800">Độ mới {{ $product->condition_pct }}%</p>
                    </div>
                </div>
                <div class="border border-gray-200 p-4 rounded-xl flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-50 text-purple-500 rounded-full flex items-center justify-center text-lg"><i class="fa-solid fa-truck-fast"></i></div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase font-bold">Giao hàng</p>
                        <p class="font-bold text-gray-800">Thỏa thuận</p>
                    </div>
                </div>
            </div>

            {{-- Nút Hành động --}}
            <div class="flex gap-4 mt-8 mt-auto">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1 ajax-add-to-cart">
                    @csrf
                    <button type="submit" class="w-full bg-green-600 text-white font-bold py-3.5 rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-cart-plus"></i> THÊM VÀO GIỎ HÀNG
                    </button>
                </form>
                <button type="button" onclick="startChatWithSeller({{ $product->id }}, '{{ addslashes($product->user->name) }}')" class="px-6 py-3.5 border-2 border-green-600 text-green-700 font-bold rounded-xl hover:bg-green-50 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-comment-dots"></i> NHẮN TIN
                </button>
            </div>
        </div>
    </div>

    {{-- 3. KHỐI BẢNG THÔNG SỐ KỸ THUẬT --}}
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 mt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-green-500 pl-3">Thông số kỹ thuật</h3>

        <div class="border-t border-gray-200">
            @if(!empty($product->specifications))
            @php
            $lines = explode("\n", str_replace("\r", "", $product->specifications));
            @endphp

            @foreach($lines as $line)
            @if(str_contains($line, ':'))
            @php
            [$label, $value] = explode(':', $line, 2);
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 py-3 border-b border-gray-100 text-[15px] items-center">
                <span class="text-gray-500 font-medium md:col-span-1 mb-1 md:mb-0">{{ trim($label) }}</span>
                <span class="text-gray-900 font-medium md:col-span-3">{{ trim($value) }}</span>
            </div>
            @endif
            @endforeach
            @else
            <p class="text-gray-500 text-sm py-4 italic">Người bán chưa cung cấp bảng thông số kỹ thuật.</p>
            @endif
        </div>
    </div>

    {{-- 4. KHỐI MÔ TẢ CHI TIẾT --}}
    <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 mt-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 border-l-4 border-green-500 pl-3">Mô tả sản phẩm</h3>
        <div class="prose prose-green max-w-none">
            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
        </div>
    </div>

</div>
@endsection

{{-- 4. Phần Script (Kịch bản JS) riêng của trang Chi tiết --}}
@section('scripts')
<script>
    // Logic Slide Ảnh
    const images = [
        @foreach($product->images as $img)
        "{{ asset('storage/' . $img->image_path) }}",
        @endforeach
    ];

    let currentIndex = 0;

    function changeImage(src, element, index) {
        currentIndex = index;
        document.getElementById('main-display').src = src;

        document.querySelectorAll('.thumb-item').forEach(el => {
            el.classList.remove('border-green-500', 'ring-2', 'ring-green-100', 'opacity-100');
            el.classList.add('border-transparent', 'opacity-60');
        });

        if (element) {
            element.classList.remove('border-transparent', 'opacity-60');
            element.classList.add('border-green-500', 'ring-2', 'ring-green-100', 'opacity-100');
        }
    }

    function prevImg() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
        updateSlider();
    }

    function nextImg() {
        if (images.length <= 1) return;
        currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
        updateSlider();
    }

    function updateSlider() {
        const thumbElements = document.querySelectorAll('.thumb-item');
        if (thumbElements.length > 0) {
            changeImage(images[currentIndex], thumbElements[currentIndex], currentIndex);
        }
    }

    // Logic Giỏ hàng & Nhắn tin
    $(document).ready(function() {
        $('.ajax-add-to-cart').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            let url = form.attr('action');

            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        $('.cart-counter').text(response.cartCount);
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    window.location.href = "{{ route('login') }}";
                }
            });
        });
    });

    function openGlobalChat(productId) {
        console.log("Đang gọi cổng chat cho sản phẩm: " + productId);
        $.ajax({
            type: "POST",
            url: `/chat/start/${productId}`,
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response.success) {
                    console.log("Nhận kết quả phòng chat ID từ server: " + response.conversation_id);
                    window.dispatchEvent(new CustomEvent('open-chat', { 
                        detail: response.conversation_id 
                    }));
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    alert("Vui lòng đăng nhập để thực hiện nhắn tin!");
                    window.location.href = "{{ route('login') }}";
                } else {
                    alert("Không thể thực hiện tác vụ (Bạn không thể tự chat với chính mình).");
                }
            }
        });
    }

    function startChatWithSeller(productId, sellerName) {
        // Gọi API tạo/mở phòng chat
        fetch(`/chat/start/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Gắn lệnh gọi Khung chat lơ lửng bật lên đúng người bán đó
                window.dispatchEvent(new CustomEvent('open-direct-chat', {
                    detail: {
                        convId: data.conversation_id,
                        partnerName: sellerName
                    }
                }));
            } else if(data.error) {
                alert(data.error); // Báo lỗi nếu tự nhắn cho chính mình
            }
        }).catch(err => console.error(err));
    }
</script>
@endsection