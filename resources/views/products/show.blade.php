@extends('layouts.app')

@section('title', $product->title . ' - 2HAND')

@section('content')
<div class="max-w-6xl mx-auto px-4 pb-12">

    {{-- BREADCRUMB (Điều hướng) --}}
    <nav class="flex text-sm text-gray-500 mb-6 font-medium mt-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="hover:text-emerald-600 transition"><i class="fa-solid fa-house mr-2"></i>Trang chủ</a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fa-solid fa-chevron-right text-gray-400 text-xs mx-1"></i>
                    {{-- FIX: Đã thêm link dẫn về danh mục tương ứng thay vì link chết '#' --}}
                    <a href="{{ url('/?category_id=' . ($product->category_id ?? '')) }}" class="hover:text-emerald-600 ml-1 transition">{{ $product->category->name ?? 'Danh mục' }}</a>
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

    {{-- HIỂN THỊ THÔNG BÁO GIỎ HÀNG --}}
    @if(session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3 font-medium">
            <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i> {{ session('success') }}
        </div>
        <a href="{{ route('cart.index') }}" class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-emerald-700 transition shadow-sm">Xem giỏ hàng</a>
    </div>
    @endif
    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl shadow-sm mb-6 flex items-center gap-3 font-medium">
        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i> {{ session('error') }}
    </div>
    @endif

    {{-- KHỐI CHI TIẾT SẢN PHẨM --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100">

        {{-- CỘT TRÁI: ẢNH SẢN PHẨM --}}
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
                <button onclick="prevImg()" class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-emerald-50 hover:text-emerald-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10 outline-none">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="nextImg()" class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/90 text-gray-800 p-2 rounded-full shadow-md hover:bg-emerald-50 hover:text-emerald-600 transition-all opacity-0 group-hover:opacity-100 flex items-center justify-center z-10 w-10 h-10 outline-none">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                @endif
            </div>

            {{-- Ảnh thu nhỏ (Thumbnails) --}}
            <div class="flex justify-start gap-2 overflow-x-auto py-1 no-scrollbar">
                @if($product->images && $product->images->count() > 0)
                @foreach($product->images as $index => $img)
                <div class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden border-2 cursor-pointer transition-all thumb-item {{ $index === 0 ? 'border-emerald-500 ring-2 ring-emerald-100 opacity-100' : 'border-transparent opacity-50 hover:opacity-100' }}"
                    onclick="changeImage('{{ asset('storage/' . $img->image_path) }}', this, {{ $index }})">
                    <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                </div>
                @endforeach
                @endif
            </div>

            {{-- Công cụ tương tác dưới ảnh --}}
            <div class="flex justify-between items-center pt-2 text-sm text-gray-500 font-medium">
                <div class="flex gap-4">
                    @php
                        $isFavorited = Auth::check() ? Auth::user()->favorites->contains($product->id) : false;
                    @endphp
                    <button onclick="toggleFavorite({{ $product->id }}, this)" type="button" class="hover:text-red-600 transition flex items-center gap-1.5 outline-none group">
                        <i class="{{ $isFavorited ? 'fa-solid text-red-500' : 'fa-regular' }} fa-heart text-base transition-colors duration-300 group-hover:scale-110"></i> 
                        <span>{{ $isFavorited ? 'Đã lưu' : 'Lưu tin' }}</span>
                    </button>
                    <button type="button" class="hover:text-emerald-600 transition flex items-center gap-1.5 group"><i class="fa-solid fa-share-nodes group-hover:scale-110 transition-transform"></i> Chia sẻ</button>
                </div>
                
                {{-- FIX LOGIC: Ẩn nút Báo cáo nếu đây là sản phẩm của chính người đăng nhập --}}
                @if(Auth::id() !== $product->user_id)
                <button type="button" onclick="openReportModal()" class="hover:text-red-500 transition flex items-center gap-1 text-xs"><i class="fa-solid fa-flag"></i> Báo cáo tin</button>
                @endif
            </div>
        </div>

        {{-- CỘT PHẢI: THÔNG TIN SẢN PHẨM & NGƯỜI BÁN --}}
        <div class="lg:col-span-7 flex flex-col">
            <div>
                <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 leading-tight">{{ $product->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 mt-3 text-sm text-gray-500 font-medium">
                    <span class="flex items-center gap-1 bg-gray-100 px-2.5 py-1 rounded-md text-gray-700">
                        <i class="fa-regular fa-clock"></i> Đăng {{ $product->created_at->diffForHumans() }}
                    </span>
                    <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $product->location ?? 'Toàn quốc' }}</span>
                    <span class="flex items-center gap-1"><i class="fa-regular fa-eye"></i> {{ number_format($product->view_count ?? 0) }} lượt xem</span>
                </div>
            </div>

            <div class="bg-gradient-to-r from-emerald-50 to-green-50 p-6 rounded-2xl mt-6 border border-emerald-100 shadow-inner">
                <p class="text-emerald-700 text-sm font-bold mb-1 uppercase tracking-wide">Giá thanh lý</p>
                <div class="flex items-end gap-3">
                    <p class="text-4xl font-black text-red-600">{{ number_format($product->price) }} <span class="text-2xl underline decoration-2">đ</span></p>
                    @if($product->original_price && $product->original_price > $product->price)
                    <p class="text-lg text-gray-400 line-through font-semibold mb-1">{{ number_format($product->original_price) }} đ</p>
                    <span class="mb-2 text-xs font-bold text-red-500 bg-red-100 px-2 py-0.5 rounded">-{{ round((1 - $product->price / $product->original_price) * 100) }}%</span>
                    @endif
                </div>
            </div>

            {{-- THẺ THÔNG TIN SHOP --}}
            <div class="flex items-center justify-between p-4 mt-6 border border-gray-200 rounded-2xl hover:border-emerald-300 transition-colors group">
                <div class="flex items-center gap-4">
                    @if($product->user && $product->user->avatar)
                    <img src="{{ asset('storage/' . $product->user->avatar) }}" class="w-14 h-14 rounded-full object-cover border-2 border-emerald-100">
                    @else
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-700 font-bold text-2xl rounded-full flex items-center justify-center border-2 border-emerald-50 shrink-0 uppercase">
                        {{ substr($product->user->name ?? 'U', 0, 1) }}
                    </div>
                    @endif

                    <div>
                        <p class="font-bold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ $product->user->name ?? 'Người bán ẩn danh' }}</p>
                        <div class="flex items-center gap-2 text-xs text-gray-500 mt-1">
                            <span class="flex items-center text-yellow-500 font-bold"><i class="fa-solid fa-star mr-1"></i> 5.0</span>
                            <span>•</span>
                            <span>Hoạt động gần đây</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('shop.show', $product->user_id) }}" class="px-5 py-2.5 bg-white border-2 border-gray-200 text-gray-700 text-sm font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all">
                    Xem trang
                </a>
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="border border-gray-100 bg-gray-50/50 p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xl shadow-inner"><i class="fa-solid fa-medal"></i></div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider mb-0.5">Tình trạng</p>
                        <p class="font-bold text-gray-800 text-sm">Độ mới {{ $product->condition_pct }}%</p>
                    </div>
                </div>
                <div class="border border-gray-100 bg-gray-50/50 p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-full flex items-center justify-center text-xl shadow-inner"><i class="fa-solid fa-truck-fast"></i></div>
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-wider mb-0.5">Giao hàng</p>
                        <p class="font-bold text-gray-800 text-sm">Thỏa thuận</p>
                    </div>
                </div>
            </div>

            {{-- Khối nút bấm hành động (ĐÃ SỬA LỖI TỰ MUA HÀNG CỦA MÌNH) --}}
            <div class="flex gap-4 mt-8 mt-auto">
                @if(Auth::id() === $product->user_id)
                    {{-- Nếu là bài của mình -> Chỉ hiện nút chỉnh sửa --}}
                    <a href="{{ route('products.edit', $product->id) }}" class="flex-1 bg-yellow-500 text-white font-bold py-4 rounded-xl hover:bg-yellow-600 shadow-lg shadow-yellow-200 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-pen-to-square"></i> CHỈNH SỬA TIN ĐĂNG NÀY
                    </a>
                @else
                    {{-- Nếu là bài người khác -> Hiện nút Mua và Chat --}}
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1 ajax-add-to-cart">
                        @csrf
                        <button type="submit" class="w-full h-full bg-emerald-600 text-white font-bold py-4 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                            <i class="fa-solid fa-cart-plus text-lg"></i> THÊM VÀO GIỎ HÀNG
                        </button>
                    </form>
                    
                    {{-- FIX LỖI USER BỊ XÓA BẰNG TOÁN TỬ ?? --}}
                    <button type="button" onclick="startProductChat({{ $product->id }}, '{{ addslashes($product->user->name ?? 'Người bán') }}')" class="px-8 py-4 border-2 border-emerald-600 text-emerald-700 font-bold rounded-xl hover:bg-emerald-50 transition-all flex items-center justify-center gap-2 transform hover:-translate-y-0.5">
                        <i class="fa-solid fa-comment-dots text-lg"></i> NHẮN TIN
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- KHỐI THÔNG SỐ --}}
    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 mt-8">
        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-clipboard-list text-emerald-500"></i> Thông số kỹ thuật
        </h3>
        <div class="border-t border-gray-100 pt-2">
            @if(!empty($product->specifications))
                @php $lines = explode("\n", str_replace("\r", "", $product->specifications)); @endphp
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-1">
                @foreach($lines as $line)
                    @if(str_contains($line, ':'))
                        @php [$label, $value] = explode(':', $line, 2); @endphp
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 text-[15px]">
                            <span class="text-gray-500 font-medium">{{ trim($label) }}</span>
                            <span class="text-gray-900 font-bold text-right">{{ trim($value) }}</span>
                        </div>
                    @endif
                @endforeach
                </div>
            @else
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <p class="text-gray-500 text-sm font-medium"><i class="fa-regular fa-folder-open text-xl block mb-2 text-gray-300"></i> Người bán chưa cung cấp bảng thông số kỹ thuật chi tiết.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- KHỐI MÔ TẢ --}}
    <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 mt-8">
        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-align-left text-emerald-500"></i> Mô tả chi tiết
        </h3>
        <div class="prose prose-emerald max-w-none prose-p:leading-loose">
            <p class="text-gray-700 whitespace-pre-line text-[15px]">{{ $product->description }}</p>
        </div>
    </div>

</div>

{{-- ========================================== --}}
{{-- MODAL BÁO CÁO SẢN PHẨM                     --}}
{{-- ========================================== --}}
<div x-data="reportWidget()" 
     @open-report.window="isOpen = true"
     x-show="isOpen" 
     style="display: none;"
     class="fixed inset-0 z-[99999] flex items-center justify-center bg-gray-900/60 backdrop-blur-sm px-4">
    
    <div @click.away="isOpen = false" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-90 translate-y-4"
         class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
        
        {{-- Header Modal --}}
        <div class="px-6 py-5 border-b border-gray-100 bg-red-50/50 flex justify-between items-center">
            <h3 class="text-lg font-black text-red-600 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Báo cáo vi phạm
            </h3>
            <button @click="isOpen = false" class="text-gray-400 hover:text-gray-700 w-8 h-8 flex items-center justify-center rounded-full bg-white shadow-sm border border-gray-100 transition-colors">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        {{-- Form Nội Dung --}}
        <form @submit.prevent="submitReport" class="p-6 flex flex-col gap-5">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Lý do báo cáo <span class="text-red-500">*</span></label>
                <select x-model="reason" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3.5 text-sm font-bold focus:bg-white focus:border-red-400 focus:ring-4 focus:ring-red-50 outline-none transition-all cursor-pointer">
                    <option value="" disabled>-- Chọn lý do --</option>
                    <option value="Hàng giả, hàng nhái">Hàng giả, hàng nhái</option>
                    <option value="Sản phẩm có dấu hiệu lừa đảo">Sản phẩm có dấu hiệu lừa đảo</option>
                    <option value="Nội dung/Hình ảnh phản cảm">Nội dung/Hình ảnh phản cảm</option>
                    <option value="Sai danh mục/Thông tin không chính xác">Sai danh mục/Thông tin không chính xác</option>
                    <option value="Khác">Lý do khác...</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Chi tiết thêm (Không bắt buộc)</label>
                <textarea x-model="details" rows="3" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm font-medium focus:bg-white focus:border-red-400 focus:ring-4 focus:ring-red-50 outline-none transition-all resize-none" placeholder="Cung cấp thêm thông tin giúp ban quản trị dễ dàng xử lý..."></textarea>
            </div>

            <div class="mt-2 flex gap-3">
                <button type="button" @click="isOpen = false" class="flex-1 px-4 py-3 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                    Hủy bỏ
                </button>
                <button type="submit" :disabled="isSubmitting || !reason" class="flex-1 px-4 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 shadow-lg shadow-red-200 transition-all disabled:opacity-50 flex items-center justify-center gap-2">
                    <span x-show="!isSubmitting">Gửi báo cáo</span>
                    <i x-show="isSubmitting" class="fa-solid fa-spinner fa-spin"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // JS CHUYỂN ẢNH SẢN PHẨM
    const images = [
        @if($product->images && $product->images->count() > 0)
            @foreach($product->images as $img)
            "{{ asset('storage/' . $img->image_path) }}",
            @endforeach
        @endif
    ];
    let currentIndex = 0;

    function changeImage(src, element, index) {
        currentIndex = index;
        document.getElementById('main-display').src = src;
        document.querySelectorAll('.thumb-item').forEach(el => {
            el.classList.remove('border-emerald-500', 'ring-2', 'ring-emerald-100', 'opacity-100');
            el.classList.add('border-transparent', 'opacity-50');
        });
        if (element) {
            element.classList.remove('border-transparent', 'opacity-50');
            element.classList.add('border-emerald-500', 'ring-2', 'ring-emerald-100', 'opacity-100');
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

    // JS THÊM VÀO GIỎ HÀNG (AJAX)
    $(document).ready(function() {
        $('.ajax-add-to-cart').on('submit', function(e) {
            e.preventDefault();
            let form = $(this);
            $.ajax({
                type: "POST",
                url: form.attr('action'),
                data: form.serialize(),
                success: function(response) {
                    if (response.success) {
                        alert(response.message); 
                        window.dispatchEvent(new CustomEvent('cart-updated', { detail: { count: response.cart_count } }));
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    if(xhr.status === 401) {
                        window.location.href = "{{ route('login') }}";
                    } else {
                        alert("Đã xảy ra lỗi, vui lòng thử lại sau.");
                    }
                }
            });
        });
    });

    // JS MỞ PHÒNG CHAT
    function startProductChat(productId, sellerName) {
        @if(!Auth::check()) 
            window.location.href = '{{ route('login') }}'; 
            return;
        @endif
        
        fetch(`/chat/start/${productId}`, { 
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            }
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                window.dispatchEvent(new CustomEvent('load-conversation', { 
                    detail: { convId: data.conversation_id, partnerName: sellerName } 
                }));
            } else if(data.error) {
                alert(data.error);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Lỗi máy chủ, vui lòng thử lại sau.");
        });
    }

    // JS BÁO CÁO VI PHẠM
    function openReportModal() {
        @if(!Auth::check())
            window.location.href = '{{ route('login') }}';
            return;
        @endif
        window.dispatchEvent(new CustomEvent('open-report'));
    }

    document.addEventListener('alpine:init', () => {
        Alpine.data('reportWidget', () => ({
            isOpen: false,
            reason: '',
            details: '',
            isSubmitting: false,

            submitReport() {
                if (!this.reason) return;
                this.isSubmitting = true;

                let formData = new FormData();
                formData.append('reason', this.reason);
                formData.append('details', this.details);

                fetch(`/products/{{ $product->id }}/report`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    this.isSubmitting = false;
                    if (data.success) {
                        alert(data.success);
                        this.isOpen = false;
                        this.reason = '';
                        this.details = '';
                    } else if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(err => {
                    this.isSubmitting = false;
                    alert("Đã xảy ra lỗi kết nối, vui lòng thử lại sau.");
                });
            }
        }));
    });
</script>
@endsection