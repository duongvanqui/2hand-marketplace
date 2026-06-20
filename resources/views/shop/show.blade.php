@extends('layouts.app')

@section('title', 'Cửa hàng của ' . $user->name . ' - 2HAND')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- ======================================================== --}}
    {{-- 1. HEADER SHOP: BỘ MẶT CÔNG KHAI LỘT XÁC                  --}}
    {{-- ======================================================== --}}
    <div x-data="{ 
            isFollowing: {{ $isFollowing ? 'true' : 'false' }}, 
            followersCount: {{ $followersCount }},
            toggleFollow() {
                @if(!Auth::check())
                    window.location.href = '{{ route('login') }}';
                    return;
                @endif
                
                fetch('{{ route('follow.toggle') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ user_id: {{ $user->id }} })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.error) return;
                    this.isFollowing = data.isFollowing;
                    this.followersCount = data.followersCount;
                });
            }
        }" 
        class="bg-white rounded-[2rem] p-6 md:p-10 mb-8 shadow-[0_8px_30px_rgba(0,0,0,0.04)] border border-gray-100 flex flex-col md:flex-row items-center md:items-start gap-8 relative overflow-hidden">
        
        {{-- Background Gradient làm nền --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-gradient-to-bl from-emerald-100/60 to-transparent rounded-full blur-3xl -z-10 -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-gradient-to-tr from-yellow-50/50 to-transparent rounded-full blur-2xl -z-10 translate-y-1/3 -translate-x-1/3"></div>
        
        {{-- Ảnh đại diện --}}
        <div class="relative shrink-0 z-10">
            <div class="w-28 h-28 md:w-36 md:h-36 rounded-full border-4 border-white shadow-xl bg-white overflow-hidden flex items-center justify-center">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-emerald-100 to-green-200 text-emerald-700 flex items-center justify-center text-5xl font-black">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            
            @if($averageRating >= 4.5)
                <div class="absolute bottom-1 -right-2 bg-yellow-400 text-white text-[10px] px-2.5 py-1 rounded-full font-bold border-2 border-white shadow-sm flex items-center gap-1 z-20">
                    <i class="fa-solid fa-medal"></i> Uy tín
                </div>
            @endif
        </div>

        {{-- Thông số uy tín công khai --}}
        <div class="flex-1 text-center md:text-left pt-2 z-10 w-full">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">{{ $user->name }}</h1>
            
            <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 md:gap-5">
                <div class="bg-gray-50/80 backdrop-blur border border-gray-100 px-4 py-2.5 rounded-2xl flex flex-col items-center md:items-start min-w-[110px]">
                    <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Đánh giá</span>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-star text-yellow-400 text-base"></i>
                        <span class="text-gray-900 font-black text-lg leading-none">{{ number_format($averageRating, 1) }}</span>
                    </div>
                </div>

                <div class="bg-gray-50/80 backdrop-blur border border-gray-100 px-4 py-2.5 rounded-2xl flex flex-col items-center md:items-start min-w-[110px]">
                    <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Sản phẩm</span>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-box-open text-emerald-500 text-base"></i>
                        <span class="text-gray-900 font-black text-lg leading-none">{{ $products->total() }}</span>
                    </div>
                </div>

                <div class="bg-gray-50/80 backdrop-blur border border-gray-100 px-4 py-2.5 rounded-2xl flex flex-col items-center md:items-start min-w-[110px]">
                    <span class="text-[11px] text-gray-500 font-bold uppercase tracking-wider mb-1">Người theo dõi</span>
                    <div class="flex items-center gap-1.5">
                        <i class="fa-solid fa-users text-blue-500 text-base"></i>
                        <span class="text-gray-900 font-black text-lg leading-none" x-text="followersCount">0</span>
                    </div>
                </div>
            </div>
            <p class="text-xs text-gray-400 font-medium mt-4"><i class="fa-regular fa-calendar-check mr-1"></i> Tham gia từ {{ $user->created_at->format('m/Y') }}</p>
        </div>

        {{-- Nút Nhắn tin / Theo dõi --}}
        @if(!$isOwner)
        <div class="shrink-0 flex flex-row md:flex-col gap-3 w-full md:w-auto mt-2 md:mt-0 z-10">
            <button type="button" onclick="startDirectChat({{ $user->id }}, '{{ addslashes($user->name) }}')" class="flex-1 md:flex-none w-full px-8 py-3.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-black rounded-xl hover:from-emerald-600 hover:to-green-700 shadow-lg shadow-emerald-200 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                <i class="fa-solid fa-comment-dots text-lg"></i> Nhắn tin ngay
            </button>
            
            <button @click="toggleFollow()" 
                    type="button" 
                    class="flex-1 md:flex-none px-8 py-3 border-2 font-bold rounded-xl transition-all flex items-center justify-center gap-2"
                    :class="isFollowing ? 'border-gray-200 text-gray-600 bg-gray-50 hover:bg-gray-100 hover:text-red-500 hover:border-red-200' : 'border-emerald-500 text-emerald-600 hover:bg-emerald-50'">
                <i class="fa-solid" :class="isFollowing ? 'fa-user-check' : 'fa-user-plus'"></i> 
                <span x-text="isFollowing ? 'Đang theo dõi' : 'Theo dõi shop'"></span>
            </button>
        </div>
        @endif
    </div>

    {{-- 2. HỆ THỐNG TABS SẢN PHẨM & ĐÁNH GIÁ --}}
    <div x-data="{ tab: 'products' }">
        
        <div class="flex p-1.5 space-x-2 bg-gray-100/80 rounded-2xl w-full md:w-fit mb-8 border border-gray-200/60 overflow-x-auto no-scrollbar shadow-inner">
            <button @click="tab = 'products'" :class="tab === 'products' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fa-solid fa-boxes-stacked"></i> Sản phẩm đang bán ({{ $products->total() }})
            </button>
            <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'bg-white text-emerald-600 shadow-sm' : 'text-gray-500 hover:text-gray-800'" class="px-8 py-2.5 rounded-xl text-sm font-bold transition-all whitespace-nowrap flex items-center gap-2">
                <i class="fa-solid fa-star-half-stroke"></i> Đánh giá của khách hàng ({{ $totalReviews }})
            </button>
        </div>

        {{-- TAB NỘI DUNG 1: LƯỚI SẢN PHẨM --}}
        <div x-show="tab === 'products'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            @if($products->isEmpty())
                <div class="text-center py-24 bg-white rounded-[2rem] border border-gray-100 shadow-sm">
                    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-inner">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-black text-gray-800 mb-1">Chưa có sản phẩm</h3>
                    <p class="text-gray-400 font-medium text-sm">Shop này hiện chưa đăng bán sản phẩm nào.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-5">
                    @foreach($products as $product)
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group relative">
                        <button onclick="toggleFavorite({{ $product->id }}, this)" type="button" class="absolute top-2 right-2 z-10 w-8 h-8 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform outline-none text-gray-400 hover:text-red-500">
                            <i class="{{ (Auth::check() && Auth::user()->favorites->contains($product->id)) ? 'fa-solid text-red-500' : 'fa-regular' }} fa-heart text-lg transition-colors"></i>
                        </button>

                        <a href="{{ route('products.show', $product->slug) }}" class="block">
                            <div class="w-full aspect-[4/3] bg-gray-50 flex items-center justify-center overflow-hidden border-b border-gray-50">
                                @if($product->images && $product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                @else
                                    <i class="fa-regular fa-image text-3xl text-gray-300"></i>
                                @endif
                            </div>
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-emerald-600 transition-colors">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
                            </h3>
                            <div class="mt-auto">
                                <p class="text-base font-black text-red-600 mb-2">{{ number_format($product->price) }} đ</p>
                                <div class="flex items-center justify-between text-[11px] text-gray-500 font-medium">
                                    <span class="truncate max-w-[60%]"><i class="fa-solid fa-location-dot mr-1 text-gray-400"></i> {{ $product->location ?? 'Toàn quốc' }}</span>
                                    <span class="bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">Mới {{ $product->condition_pct }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->appends(request()->query())->links() }}</div>
            @endif
        </div>

        {{-- TAB NỘI DUNG 2: ĐÁNH GIÁ THỰC TẾ --}}
        <div x-show="tab === 'reviews'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4">
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="divide-y divide-gray-50">
                    @forelse($reviews as $review)
                        <div class="p-6 md:p-8 hover:bg-gray-50/50 transition-colors">
                            <div class="flex gap-4">
                                <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-black shrink-0 border border-blue-100 shadow-sm">
                                    {{ substr($review->sender->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between items-start mb-1">
                                        <h4 class="font-bold text-gray-900 text-base">{{ $review->sender->name }}</h4>
                                        <span class="text-xs text-gray-400 font-medium flex items-center gap-1"><i class="fa-regular fa-clock"></i> {{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-yellow-400 text-xs gap-0.5 mb-3">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="fa-star {{ $i <= $review->rating ? 'fa-solid' : 'fa-regular text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    
                                    @if($review->comment)
                                        <p class="text-gray-800 text-sm leading-relaxed mb-4">"{{ $review->comment }}"</p>
                                    @else
                                        <p class="text-gray-400 text-sm italic mb-4">Người mua không để lại nhận xét bằng văn bản.</p>
                                    @endif
                                    
                                    @if($review->order && $review->order->product)
                                    <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 p-2.5 rounded-xl w-full max-w-sm group hover:border-emerald-200 transition-colors">
                                        <div class="w-10 h-10 bg-white rounded-lg overflow-hidden shrink-0 border border-gray-100">
                                            @if($review->order->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $review->order->product->images->first()->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-gray-300"><i class="fa-regular fa-image"></i></div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[10px] text-gray-500 font-bold uppercase mb-0.5">Sản phẩm đã mua</p>
                                            <a href="{{ route('products.show', $review->order->product->slug) }}" class="text-xs font-bold text-gray-800 truncate block hover:text-emerald-600 transition-colors">
                                                {{ $review->order->product->title }}
                                            </a>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-24">
                            <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-inner">
                                <i class="fa-solid fa-star-half-stroke text-4xl text-yellow-300"></i>
                            </div>
                            <h3 class="text-lg font-black text-gray-800 mb-1">Chưa có đánh giá</h3>
                            <p class="text-gray-400 font-medium text-sm">Shop này chưa nhận được đánh giá nào từ người mua.</p>
                        </div>
                    @endforelse
                </div>
                @if($reviews->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/50">{{ $reviews->appends(request()->query())->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script>
    function startDirectChat(userId, userName) {
        @if(!Auth::check())
            window.location.href = '{{ route('login') }}';
            return;
        @endif

        fetch(`/chat/user/${userId}`, {
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
                    detail: {
                        convId: data.conversation_id,
                        partnerName: data.partner_name || userName
                    }
                }));
            } else if(data.error) {
                alert(data.error);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Lỗi đường truyền, vui lòng thử lại.");
        });
    }
</script>
@endsection