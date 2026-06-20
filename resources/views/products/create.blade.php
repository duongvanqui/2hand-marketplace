<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Tin Thanh Lý - 2HAND</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body class="font-sans text-gray-900 min-h-screen relative bg-gray-100">
    
    {{-- ===== NỀN TRANG INDEX MỜ ===== --}}
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none select-none">
        <iframe src="{{ url('/') }}" class="w-full h-full scale-105" frameborder="0"></iframe>
        <div class="absolute inset-0 backdrop-blur-xl bg-white/80"></div>
    </div>

    <div class="relative z-10 flex flex-col min-h-screen">
        
        {{-- ===== NAVBAR MINI (Đã đồng bộ Logo) ===== --}}
        <header class="bg-white/90 backdrop-blur-md border-b border-gray-200 sticky top-0 z-50 shadow-sm">
            <div class="max-w-4xl mx-auto px-4 py-3 flex justify-between items-center">
                <a href="{{ url('/') }}" class="flex items-center gap-3" title="Về trang chủ">
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-green-600 rounded-xl flex items-center justify-center text-white text-xl shadow-lg shadow-green-200 transform transition-transform hover:scale-110 hover:rotate-3">
                        <i class="fa-solid fa-hand-holding-hand"></i>
                    </div>
                    <span class="text-2xl font-black text-gray-900 tracking-tighter uppercase whitespace-nowrap">
                        2<span class="text-emerald-500">HAND</span>
                    </span>
                </a>
                
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-red-600 font-bold flex items-center gap-2 transition-colors bg-gray-100 hover:bg-red-50 px-4 py-2 rounded-xl text-sm border border-gray-200 hover:border-red-200 shadow-sm">
                    <i class="fa-solid fa-xmark text-lg"></i> Hủy đăng tin
                </a>
            </div>
        </header>

        {{-- ===== MAIN FORM ===== --}}
        <main class="max-w-4xl mx-auto px-4 py-8 w-full flex-grow">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                
                {{-- Tiêu đề trang --}}
                <div class="text-center pt-10 pb-6 px-6">
                    <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-3 tracking-tight">Đăng tin thanh lý</h1>
                    <p class="text-gray-500 font-medium">Cung cấp thông tin chi tiết giúp món đồ của bạn được chốt nhanh hơn!</p>
                </div>

                {{-- Hiển thị Lỗi (Nếu có) --}}
                @if ($errors->any())
                <div class="mx-6 md:mx-8 mb-6 bg-red-50 border-2 border-red-200 text-red-700 px-6 py-5 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-3 font-black text-lg mb-2 text-red-800">
                        <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
                        Đăng tin chưa thành công!
                    </div>
                    <ul class="list-disc list-inside space-y-1 text-sm text-red-600 font-medium pl-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- FORM ĐĂNG TIN --}}
                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="p-6 md:p-8 space-y-8 bg-gray-50/50 border-t border-gray-100">
                        
                        {{-- 1. THÔNG TIN CƠ BẢN --}}
                        <div>
                            <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-cube text-emerald-500"></i> Thông tin cơ bản</h3>
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Tiêu đề tin đăng <span class="text-red-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Ví dụ: iPhone 13 Pro Max 256GB VN/A Xanh Lá..." class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 placeholder:text-gray-400">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                                        <select name="category_id" required class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 cursor-pointer">
                                            <option value="">-- Chọn danh mục --</option>
                                            @foreach($categories as $category)
                                                @if($category->children && $category->children->count() > 0)
                                                    <optgroup label="{{ $category->name }}" class="font-bold text-emerald-700">
                                                        @foreach($category->children as $child)
                                                            <option value="{{ $child->id }}" {{ old('category_id') == $child->id ? 'selected' : '' }} class="font-medium text-gray-900">
                                                                {{ $child->name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @else
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="font-medium text-gray-900">
                                                        {{ $category->name }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Tình trạng (Độ mới %) <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="condition_pct" min="1" max="100" value="{{ old('condition_pct') }}" required placeholder="Ví dụ: 95" class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900">
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-bold pointer-events-none">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- 2. TÀI CHÍNH & KHU VỰC --}}
                        <div>
                            <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-money-bill-wave text-emerald-500"></i> Tài chính & Khu vực</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Giá thanh lý (VNĐ) <span class="text-red-500">*</span></label>
                                    <input type="number" name="price" value="{{ old('price') }}" required placeholder="Ví dụ: 1500000" class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold text-emerald-600 text-lg tracking-wide">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Khu vực giao dịch <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none"><i class="fa-solid fa-location-dot"></i></div>
                                        <input type="text" name="location" value="{{ old('location') }}" required placeholder="Địa chỉ cụ thể, quận/huyện..." class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- 3. HÌNH ẢNH SẢN PHẨM --}}
                        <div>
                            <h3 class="text-base font-black text-gray-800 mb-1 flex items-center gap-2"><i class="fa-solid fa-images text-emerald-500"></i> Hình ảnh sản phẩm</h3>
                            <p class="text-xs text-gray-500 font-medium mb-4">Tải lên tối đa 10 ảnh. Click vào một ảnh bất kỳ để chọn làm <span class="text-emerald-600 font-bold">Ảnh bìa</span>.</p>

                            <div class="border-2 border-dashed border-gray-400 rounded-2xl bg-white p-5 transition-colors relative" id="drop-zone">
                                
                                <input type="file" id="image-input" name="images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden">
                                <input type="hidden" name="cover_image_index" id="cover-index" value="0">

                                {{-- Trạng thái rỗng --}}
                                <div id="empty-state" class="py-12 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/50 rounded-xl transition-colors">
                                    <div class="w-16 h-16 bg-white shadow border border-gray-200 rounded-full flex items-center justify-center text-emerald-500 text-2xl mb-3">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <p class="font-bold text-gray-800">Kéo thả ảnh vào đây hoặc <span class="text-emerald-600">tải lên từ máy</span></p>
                                    <p class="text-xs text-gray-400 mt-1">Định dạng JPG, PNG, WEBP</p>
                                </div>

                                {{-- Lưới chứa ảnh --}}
                                <div id="preview-grid" class="grid grid-cols-2 md:grid-cols-5 gap-4 hidden">
                                    <div id="add-more-btn" class="aspect-square rounded-xl border-2 border-dashed border-gray-400 flex flex-col items-center justify-center text-gray-500 cursor-pointer hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-colors bg-white shadow-sm">
                                        <i class="fa-solid fa-plus text-2xl mb-1"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-wider">Thêm ảnh</span>
                                    </div>
                                </div>
                                
                                <p id="image-count-text" class="text-xs text-right mt-4 font-bold text-gray-500 hidden">Đã chọn: 0/10 ảnh</p>
                            </div>
                        </div>

                        <hr class="border-gray-200">

                        {{-- 4. MÔ TẢ CHI TIẾT --}}
                        <div>
                            <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-align-left text-emerald-500"></i> Mô tả chi tiết</h3>
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả chung <span class="text-red-500">*</span></label>
                                    <textarea name="description" rows="5" required placeholder="Tình trạng hiện tại, phụ kiện đi kèm, lý do bán, chính sách bảo hành (nếu có)..." class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 leading-relaxed placeholder:text-gray-400">{{ old('description') }}</textarea>
                                </div>

                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <label class="block text-sm font-bold text-gray-700">Thông số kỹ thuật</label>
                                        <span class="text-xs text-gray-400 font-bold">(Không bắt buộc)</span>
                                    </div>
                                    <textarea name="specifications" rows="8" placeholder="Nhập mỗi thông số 1 dòng. Ví dụ:&#10;Màn hình: 6.7 inch&#10;Chip: Snapdragon 8 Gen 2&#10;Dung lượng: 256GB&#10;Pin: 5000 mAh" class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 leading-relaxed placeholder:text-gray-400">{{ old('specifications') }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- THANH ACTION --}}
                    <div class="p-6 md:p-8 bg-white border-t border-gray-200">
                        <button type="submit" class="w-full bg-emerald-600 text-white font-black py-4 rounded-xl hover:bg-emerald-700 shadow-lg shadow-emerald-200/50 transition-all text-center flex items-center justify-center gap-3 text-lg uppercase tracking-wider">
                            <i class="fa-solid fa-paper-plane"></i> Đăng tin thanh lý ngay
                        </button>
                        <p class="text-center text-xs text-gray-500 font-medium mt-4">
                            Bằng việc đăng tin, bạn đồng ý với <a href="#" class="text-emerald-600 hover:underline">Quy định bán hàng</a> của 2HAND.
                        </p>
                    </div>
                </form>
            </div>
        </main>

        <footer class="bg-white/80 backdrop-blur border-t border-gray-200 py-6 text-center text-sm font-bold text-gray-400 shrink-0">
            &copy; 2026 2HAND. All rights reserved.
        </footer>
    </div>

    {{-- SCRIPTS XỬ LÝ ẢNH --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let selectedFiles = [];
            
            const fileInput = document.getElementById('image-input');
            const emptyState = document.getElementById('empty-state');
            const previewGrid = document.getElementById('preview-grid');
            const addMoreBtn = document.getElementById('add-more-btn');
            const countText = document.getElementById('image-count-text');
            const coverIndexInput = document.getElementById('cover-index');

            emptyState.addEventListener('click', () => fileInput.click());
            addMoreBtn.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', function(e) {
                const files = Array.from(e.target.files);
                const validImageFiles = files.filter(file => file.type.startsWith('image/'));

                if (selectedFiles.length + validImageFiles.length > 10) {
                    alert('Bạn chỉ được chọn tối đa 10 hình ảnh!');
                    validImageFiles.splice(10 - selectedFiles.length); 
                }

                selectedFiles = [...selectedFiles, ...validImageFiles];
                
                // Mặc định chọn ảnh đầu tiên làm ảnh bìa nếu chưa có ảnh nào
                if (selectedFiles.length > 0 && coverIndexInput.value === "") {
                    coverIndexInput.value = "0";
                }

                renderPreviews();
                
                // Reset input file (Quan trọng để có thể chọn lại cùng 1 file nếu vừa xóa)
                const dataTransfer = new DataTransfer();
                selectedFiles.forEach(file => dataTransfer.items.add(file));
                fileInput.files = dataTransfer.files;
            });

            function renderPreviews() {
                if(selectedFiles.length > 0) {
                    emptyState.classList.add('hidden');
                    previewGrid.classList.remove('hidden');
                    countText.classList.remove('hidden');
                } else {
                    emptyState.classList.remove('hidden');
                    previewGrid.classList.add('hidden');
                    countText.classList.add('hidden');
                    coverIndexInput.value = "0"; // Reset ảnh bìa
                }

                document.querySelectorAll('.thumb-wrapper').forEach(el => el.remove());

                selectedFiles.forEach((file, index) => {
                    const url = URL.createObjectURL(file);
                    const isCover = (coverIndexInput.value === index.toString());

                    const wrapper = document.createElement('div');
                    wrapper.className = `thumb-wrapper relative aspect-square rounded-xl overflow-hidden shadow-sm cursor-pointer group border-2 ${isCover ? 'border-emerald-500 ring-4 ring-emerald-50' : 'border-gray-200'}`;
                    
                    wrapper.onclick = function() { setCover(index, this); }

                    wrapper.innerHTML = `
                        <img src="${url}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                        <div class="cover-badge absolute bottom-0 left-0 w-full bg-emerald-500/90 backdrop-blur text-white text-[10px] font-bold py-1.5 text-center ${isCover ? 'block' : 'hidden'}">ẢNH BÌA</div>
                        <div class="overlay absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity items-center justify-center ${isCover ? 'hidden' : 'flex'}">
                            <span class="text-white text-[10px] font-bold uppercase tracking-wider border border-white px-2 py-1 rounded shadow">Chọn làm bìa</span>
                        </div>
                        <button type="button" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity transform hover:scale-110 shadow-md">
                            <i class="fa-solid fa-xmark text-xs pointer-events-none"></i>
                        </button>
                    `;

                    wrapper.querySelector('button').onclick = function(e) {
                        e.stopPropagation();
                        selectedFiles.splice(index, 1);
                        
                        // Cập nhật lại files trong input
                        const dataTransfer = new DataTransfer();
                        selectedFiles.forEach(f => dataTransfer.items.add(f));
                        fileInput.files = dataTransfer.files;

                        if (coverIndexInput.value == index) {
                            coverIndexInput.value = selectedFiles.length > 0 ? "0" : "";
                        } else if (coverIndexInput.value > index) {
                            coverIndexInput.value--;
                        }
                        
                        renderPreviews();
                    }

                    previewGrid.insertBefore(wrapper, addMoreBtn);
                });

                countText.textContent = `Đã chọn: ${selectedFiles.length}/10 ảnh`;
                addMoreBtn.style.display = selectedFiles.length >= 10 ? 'none' : 'flex';
            }

            function setCover(index, element) {
                coverIndexInput.value = index;

                document.querySelectorAll('.thumb-wrapper').forEach(el => {
                    el.classList.remove('border-emerald-500', 'ring-4', 'ring-emerald-50');
                    el.classList.add('border-gray-200');
                    el.querySelector('.cover-badge').classList.replace('block', 'hidden');
                    el.querySelector('.overlay').classList.replace('hidden', 'flex');
                });

                element.classList.remove('border-gray-200');
                element.classList.add('border-emerald-500', 'ring-4', 'ring-emerald-50');
                element.querySelector('.cover-badge').classList.replace('hidden', 'block');
                element.querySelector('.overlay').classList.replace('flex', 'hidden');
            }
        });
    </script>
</body>
</html>