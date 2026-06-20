@extends('layouts.admin')

@section('title', 'Tin đã lưu - 2HAND')
@section('header_title', 'Tin đã lưu (Yêu thích)')

@section('content')
<div class="pb-10">

    @if($favorites->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
            @foreach($favorites as $product)
            <div class="bg-white rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-gray-100 overflow-hidden flex flex-col group relative">
                
                {{-- Nút Trái tim (Đã lưu) --}}
                <button onclick="toggleFavoritePage({{ $product->id }}, this)" class="absolute top-3 right-3 z-10 w-9 h-9 bg-white/90 backdrop-blur rounded-full flex items-center justify-center shadow-sm hover:scale-110 transition-transform outline-none border border-red-50">
                    <i class="fa-solid fa-heart text-red-500 text-lg transition-colors"></i>
                </button>

                <a href="{{ route('products.show', $product->slug) }}" class="block">
                    <div class="w-full aspect-[4/3] bg-gray-50 flex items-center justify-center overflow-hidden border-b border-gray-50">
                        @if($product->images && $product->images->count() > 0)
                            <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="text-center text-gray-300">
                                <i class="fa-regular fa-image text-4xl mb-1 block"></i>
                            </div>
                        @endif
                    </div>
                </a>

                <div class="p-4 flex flex-col flex-grow">
                    <h3 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-2 group-hover:text-green-600 transition-colors">
                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->title }}</a>
                    </h3>
                    <div class="mt-auto">
                        <p class="text-lg font-black text-red-600 mb-2">{{ number_format($product->price, 0, ',', '.') }} đ</p>
                        <div class="flex items-center justify-between text-[11px] text-gray-500 font-medium">
                            <span class="flex items-center gap-1"><i class="fa-solid fa-location-dot"></i> {{ $product->location ?? 'Toàn quốc' }}</span>
                            <span class="bg-gray-100 px-2 py-0.5 rounded text-gray-600">{{ $product->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $favorites->links() }}
        </div>
    @else
        <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm flex flex-col items-center">
            <div class="w-28 h-28 bg-red-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-inner">
                <i class="fa-solid fa-heart-crack text-5xl text-red-300"></i>
            </div>
            <h3 class="text-lg font-black text-gray-800 mb-2">Chưa có tin lưu nào</h3>
            <p class="text-gray-500 font-medium mb-6">Bạn chưa "thả tim" bất kỳ sản phẩm nào trên hệ thống.</p>
            <a href="{{ url('/') }}" class="bg-green-600 text-white font-bold px-8 py-3 rounded-xl hover:bg-green-700 transition-colors shadow-md shadow-green-200">
                Khám phá ngay
            </a>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    function toggleFavoritePage(productId, btnElement) {
        fetch('{{ route('favorites.toggle') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                if(!data.isFavorited) {
                    // Hiệu ứng thu nhỏ và mờ dần khi bỏ tim
                    let card = btnElement.closest('.group');
                    card.style.transition = "all 0.4s cubic-bezier(0.4, 0, 0.2, 1)";
                    card.style.opacity = "0";
                    card.style.transform = "scale(0.8)";
                    
                    // Xóa hẳn thẻ HTML sau khi chạy xong hiệu ứng (400ms)
                    setTimeout(() => {
                        card.remove();
                        // Nếu xóa hết sạch thì tự load lại trang để hiện thông báo "Chưa có tin lưu"
                        if(document.querySelectorAll('.group').length === 0) {
                            window.location.reload();
                        }
                    }, 400);
                }
            }
        });
    }
</script>
@endsection