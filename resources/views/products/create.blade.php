<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Tin Thanh Lý - 2HAND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

{{-- Xóa bỏ ảnh nền mờ, sử dụng nền màu xám nhạt (bg-gray-50) đồng bộ trang chủ --}}
<body class="font-sans text-gray-900 min-h-screen relative">
    
    {{-- ===== BẮT ĐẦU: NỀN TRANG INDEX MỜ ===== --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none select-none">
        <iframe src="{{ url('/') }}" class="w-full h-full scale-105" frameborder="0"></iframe>
        
        <div class="absolute inset-0 backdrop-blur-xl bg-white/70"></div>
    </div>
    {{-- ===== KẾT THÚC: NỀN TRANG INDEX MỜ ===== --}}

    {{-- Bọc toàn bộ nội dung hiện tại vào relative z-10 để nổi lên trên lớp nền --}}
    <div class="relative z-10 flex flex-col min-h-screen">
        
        {{-- ===== NAVBAR MINI ===== --}}
        <header class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 py-4 flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="text-green-700 text-2xl">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span class="text-2xl font-black text-green-700 tracking-tighter uppercase">2HAND</span>
                </a>
                
                <a href="{{ url('/') }}" class="text-gray-500 hover:text-red-500 font-bold flex items-center gap-1.5 transition-colors bg-gray-100 hover:bg-red-50 px-3 py-1.5 rounded-lg text-sm">
                    <i class="fa-solid fa-xmark"></i> Hủy đăng tin
                </a>
            </div>
        </header>

        {{-- ===== MAIN FORM ===== --}}
        <main class="max-w-3xl mx-auto px-4 py-8 w-full flex-grow">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 relative overflow-hidden">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Đăng tin thanh lý</h1>
                    <p class="text-gray-500 text-sm">Cung cấp thông tin chi tiết giúp món đồ của bạn được chốt nhanh hơn!</p>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 shadow-sm">
                    <div class="flex items-center gap-2 font-bold text-base mb-2 text-red-800">
                        <i class="fa-solid fa-circle-exclamation text-lg"></i>
                        Đăng tin chưa thành công, vui lòng kiểm tra lại:
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600 pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tiêu đề tin đăng <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ví dụ: iPhone 13 Pro Max 128GB VN/A Xanh Lá..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium text-gray-900 placeholder:font-normal">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                            <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all bg-white font-medium text-gray-700">
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    @if($category->children && $category->children->count() > 0)
                                        <optgroup label="{{ $category->name }}" class="font-bold text-green-700">
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }} class="font-medium text-gray-700">
                                                    {{ $child->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="font-bold text-gray-800">
                                            {{ $category->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tình trạng độ mới (%) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="condition_pct" value="{{ old('condition_pct') }}" min="1" max="100" required placeholder="Ví dụ: 95"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-bold pointer-events-none">%</div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Giá thanh lý (VNĐ) <span class="text-red-500">*</span></label>
                            <input type="number" name="price" value="{{ old('price') }}" required placeholder="Ví dụ: 1200000"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-bold text-green-700">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Khu vực giao dịch <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none"><i class="fa-solid fa-location-dot"></i></div>
                                <input type="text" name="location" value="{{ old('location') }}" required placeholder="Địa chỉ cụ thể, trường học..."
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all font-medium text-gray-700">
                            </div>
                        </div>
                    </div>

                    <div x-data="imageUploader()">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Hình ảnh sản phẩm (Tối đa 10 ảnh) <span class="text-red-500">*</span></label>
                        
                        <div class="mt-1 flex flex-col items-center justify-center px-6 pt-8 pb-8 border-2 border-gray-200 border-dashed rounded-xl hover:border-green-500 hover:bg-green-50/50 transition-all cursor-pointer relative group">
                            <div class="p-4 bg-green-50 rounded-full text-green-500 group-hover:bg-green-100 group-hover:scale-110 transition-all mb-3">
                                <i class="fa-solid fa-camera text-2xl"></i>
                            </div>
                            <div class="text-center">
                                <label class="block font-bold text-green-700 hover:text-green-800 text-sm mb-1 cursor-pointer">
                                    <span>Bấm để chọn ảnh (Giữ Ctrl để chọn nhiều)</span>
                                    <input type="file" name="images[]" multiple accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" @change="previewImages">
                                </label>
                                <p class="text-xs text-gray-400 mt-1">Hỗ trợ JPG, PNG, WEBP.</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-5 gap-3" id="image-preview-container" x-show="imageUrls.length > 0" style="display: none;">
                            <template x-for="(url, index) in imageUrls" :key="index">
                                <div class="relative aspect-square rounded-lg overflow-hidden border border-gray-200 shadow-sm group">
                                    <img :src="url" class="w-full h-full object-cover">
                                    <span x-show="index === 0" class="absolute bottom-0 left-0 w-full bg-green-600/80 text-white text-[9px] font-bold py-1 text-center uppercase tracking-wider backdrop-blur-sm">Ảnh bìa</span>
                                </div>
                            </template>
                        </div>
                        <p x-show="imageUrls.length > 0" class="text-xs text-right mt-2 font-medium" :class="imageUrls.length > 10 ? 'text-red-500' : 'text-green-600'" x-text="`Đã chọn: ${imageUrls.length}/10 ảnh`"></p>
                    </div>


                    <div class="border-t border-gray-100 pt-6 mt-4">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả chung về món đồ <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="4" required placeholder="Trình trạng hiện tại, phụ kiện đi kèm, lý do bán, chính sách đổi trả (nếu có)..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-sm leading-relaxed">{{ old('description') }}</textarea>
                    </div>

                    {{-- Đã thay đổi class CSS để đồng bộ với ô textarea phía trên --}}
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="specifications" class="block text-sm font-bold text-gray-700">Thông số kỹ thuật</label>
                            <span class="text-xs text-gray-400 font-medium">(Không bắt buộc nhập)</span>
                        </div>
                        <textarea 
                            id="specifications" 
                            name="specifications" 
                            rows="6" 
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100 transition-all text-sm leading-relaxed placeholder-gray-400"
                            placeholder="Nhập mỗi thông số trên một dòng theo định dạng 'Tên: Giá trị'.&#10;Ví dụ:&#10;Hãng: Huawei&#10;Dòng máy: P60 Pro&#10;Dung lượng: 256 GB"
                        >{{ old('specifications') }}</textarea>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-xl hover:bg-green-700 shadow-lg shadow-green-200 transition-all text-center flex items-center justify-center gap-2 text-lg uppercase tracking-wider">
                            <i class="fa-solid fa-paper-plane"></i> Đăng tin ngay
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4">Bằng việc đăng tin, bạn đồng ý với <a href="#" class="text-green-600 hover:underline">Quy định bán hàng</a> của 2HAND.</p>
                    </div>
                </form>
            </div>
        </main>

        <footer class="bg-white border-t border-gray-100 py-6 text-center text-xs text-gray-400 shrink-0">
            &copy; 2026 2HAND. All rights reserved.
        </footer>
    </div>

    <script>
        function imageUploader() {
            return {
                imageUrls: [],
                previewImages(event) {
                    this.imageUrls = [];
                    const files = event.target.files;
                    
                    if (files.length > 10) {
                        alert('Bạn chỉ được chọn tối đa 10 ảnh!');
                        event.target.value = '';
                        return;
                    }

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (file.type.startsWith('image/')) {
                            this.imageUrls.push(URL.createObjectURL(file));
                        }
                    }
                }
            }
        }
    </script>
</body>
</html>