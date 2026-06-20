@extends('layouts.admin')

@section('title', 'Chỉnh sửa tin đăng - 2HAND')
@section('header_title', 'Chỉnh sửa tin đăng')

@section('back_button')
<a href="{{ route('my.products') }}" class="w-10 h-10 bg-white rounded-xl shadow-sm border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-all flex items-center justify-center outline-none">
    <i class="fa-solid fa-arrow-left"></i>
</a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">
    
    {{-- CẢNH BÁO LUẬT CHỈNH SỬA (Đã làm nổi bật mạnh mẽ) --}}
    <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-6 flex items-start gap-5 shadow-md">
        <div class="mt-0.5 text-amber-500 text-3xl"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div>
            <h4 class="font-black text-amber-900 text-lg">Lưu ý quan trọng</h4>
            <p class="text-sm text-amber-800 mt-2 leading-relaxed font-medium">
                Vì lý do kiểm duyệt an toàn, sau khi bạn nhấn <strong class="text-amber-900 bg-amber-200/50 px-1 py-0.5 rounded">"Cập nhật & Chờ duyệt"</strong>, tin đăng này sẽ tạm thời bị ẩn khỏi hệ thống và chuyển về trạng thái <strong class="text-amber-900 bg-amber-200/50 px-1 py-0.5 rounded">Chờ duyệt</strong>. Quản trị viên sẽ xử lý lại tin của bạn trong thời gian sớm nhất.
            </p>
        </div>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 md:p-8 space-y-8 bg-gray-50/20">
            
            {{-- 1. THÔNG TIN CƠ BẢN --}}
            <div>
                <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-cube text-emerald-500"></i> Thông tin cơ bản</h3>
                
                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tiêu đề tin đăng <span class="text-red-500">*</span></label>
                        {{-- Viền đậm rõ nét: border-gray-300 hover:border-gray-400 --}}
                        <input type="text" name="title" value="{{ old('title', $product->title) }}" required placeholder="Ví dụ: iPhone 13 Pro Max 256GB VN/A Xanh Lá..." class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 placeholder:text-gray-400">
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
                                                <option value="{{ $child->id }}" {{ old('category_id', $product->category_id) == $child->id ? 'selected' : '' }} class="font-medium text-gray-900">
                                                    {{ $child->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @else
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }} class="font-medium text-gray-900">
                                            {{ $category->name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tình trạng (Độ mới %) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="condition_pct" min="1" max="100" value="{{ old('condition_pct', $product->condition_pct) }}" required class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 font-bold pointer-events-none">%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- 2. TÀI CHÍNH & GIAO DỊCH --}}
            <div>
                <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-money-bill-wave text-emerald-500"></i> Tài chính & Khu vực</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Giá thanh lý (VNĐ) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-semibold text-emerald-600 text-lg tracking-wide">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Khu vực giao dịch <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-400 pointer-events-none"><i class="fa-solid fa-location-dot"></i></div>
                            <input type="text" name="location" value="{{ old('location', $product->location) }}" required placeholder="Quận/Huyện, Tỉnh/Thành phố..." class="w-full pl-10 pr-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- 3. HÌNH ẢNH (KÉO CẢ ẢNH CŨ VÀ MỚI VÀO) --}}
            <div>
                <h3 class="text-base font-black text-gray-800 mb-1 flex items-center gap-2"><i class="fa-solid fa-images text-emerald-500"></i> Hình ảnh sản phẩm</h3>
                <p class="text-xs text-gray-500 font-medium mb-4">Tải lên tối đa 10 ảnh. Click vào một ảnh bất kỳ để chọn làm <span class="text-emerald-600 font-bold">Ảnh bìa</span>.</p>

                <div class="border-2 border-dashed border-gray-400 rounded-2xl bg-white p-5 transition-colors relative" id="drop-zone">
                    
                    <input type="file" id="image-input" name="new_images[]" multiple accept="image/jpeg,image/png,image/webp" class="hidden">
                    <div id="deleted-images-container"></div>
                    <input type="hidden" name="cover_image_id" id="cover-id" value="{{ $product->images->first()->id ?? '' }}">
                    <input type="hidden" name="cover_new_index" id="cover-index" value="">

                    <div id="empty-state" class="py-12 flex flex-col items-center justify-center cursor-pointer hover:bg-emerald-50/50 rounded-xl transition-colors {{ $product->images->count() > 0 ? 'hidden' : '' }}">
                        <div class="w-16 h-16 bg-white shadow border border-gray-200 rounded-full flex items-center justify-center text-emerald-500 text-2xl mb-3">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <p class="font-bold text-gray-800">Kéo thả ảnh vào đây hoặc <span class="text-emerald-600">tải lên từ máy</span></p>
                        <p class="text-xs text-gray-400 mt-1">Định dạng JPG, PNG, WEBP</p>
                    </div>

                    <div id="preview-grid" class="grid grid-cols-2 md:grid-cols-5 gap-4 {{ $product->images->count() > 0 ? '' : 'hidden' }}">
                        
                        @foreach($product->images as $index => $img)
                        <div class="thumb-wrapper existing-thumb relative aspect-square rounded-xl overflow-hidden shadow-sm cursor-pointer group border-2 {{ $index === 0 ? 'border-emerald-500 ring-4 ring-emerald-50' : 'border-gray-200' }}" 
                             id="existing-img-{{ $img->id }}"
                             onclick="setCover('existing', {{ $img->id }}, this)">
                            
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                            
                            <div class="cover-badge absolute bottom-0 left-0 w-full bg-emerald-500/90 backdrop-blur text-white text-[10px] font-bold py-1.5 text-center {{ $index === 0 ? 'block' : 'hidden' }}">
                                ẢNH BÌA
                            </div>
                            
                            <div class="overlay absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity items-center justify-center {{ $index === 0 ? 'hidden' : 'flex' }}">
                                <span class="text-white text-[10px] font-bold uppercase tracking-wider border border-white px-2 py-1 rounded shadow">Chọn làm bìa</span>
                            </div>

                            <button type="button" onclick="deleteExisting({{ $img->id }}, event, this)" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity transform hover:scale-110 shadow-md">
                                <i class="fa-solid fa-xmark text-xs pointer-events-none"></i>
                            </button>
                        </div>
                        @endforeach

                        <div id="add-more-btn" class="aspect-square rounded-xl border-2 border-dashed border-gray-400 flex flex-col items-center justify-center text-gray-500 cursor-pointer hover:border-emerald-500 hover:text-emerald-600 hover:bg-emerald-50 transition-colors bg-white shadow-sm">
                            <i class="fa-solid fa-plus text-2xl mb-1"></i>
                            <span class="text-[10px] font-bold uppercase tracking-wider">Thêm ảnh</span>
                        </div>
                    </div>
                    
                    <p id="image-count-text" class="text-xs text-right mt-4 font-bold text-gray-500">Đã chọn: {{ $product->images->count() }}/10 ảnh</p>
                </div>
            </div>

            <hr class="border-gray-200">

            {{-- 4. MÔ TẢ CHI TIẾT --}}
            <div>
                <h3 class="text-base font-black text-gray-800 mb-5 flex items-center gap-2"><i class="fa-solid fa-align-left text-emerald-500"></i> Mô tả chi tiết</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Mô tả chung <span class="text-red-500">*</span></label>
                        <textarea name="description" rows="5" required placeholder="Tình trạng hiện tại, phụ kiện đi kèm, lý do bán, chính sách bảo hành (nếu có)..." class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 leading-relaxed placeholder:text-gray-400">{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-sm font-bold text-gray-700">Thông số kỹ thuật</label>
                            <span class="text-xs text-gray-400 font-bold">(Không bắt buộc)</span>
                        </div>
                        {{-- CHIỀU CAO Ô NHẬP ĐÃ TĂNG LÊN rows="8" --}}
                        <textarea name="specifications" rows="8" placeholder="Nhập mỗi thông số 1 dòng. Ví dụ:&#10;Màn hình: 6.7 inch&#10;Chip: Snapdragon 8 Gen 2&#10;Dung lượng: 256GB&#10;Pin: 5000 mAh" class="w-full px-4 py-3 bg-white border-2 border-gray-300 hover:border-gray-400 shadow-sm rounded-xl focus:outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 transition-all font-medium text-gray-900 leading-relaxed placeholder:text-gray-400">{{ old('specifications', $product->specifications) }}</textarea>
                    </div>
                </div>
            </div>

        </div>

        {{-- Thanh Action --}}
        <div class="p-6 md:p-8 border-t border-gray-200 bg-white flex justify-end gap-3 items-center">
            <a href="{{ route('my.products') }}" class="px-6 py-3 bg-gray-100 border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                Hủy bỏ
            </a>
            <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-200/50 flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Cập nhật & Chờ duyệt
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    let existingCount = {{ $product->images->count() }};
    let selectedNewFiles = [];

    const fileInput = document.getElementById('image-input');
    const emptyState = document.getElementById('empty-state');
    const previewGrid = document.getElementById('preview-grid');
    const addMoreBtn = document.getElementById('add-more-btn');
    const countText = document.getElementById('image-count-text');

    const coverIdInput = document.getElementById('cover-id');
    const coverIndexInput = document.getElementById('cover-index');

    emptyState.addEventListener('click', () => fileInput.click());
    addMoreBtn.addEventListener('click', () => fileInput.click());

    function deleteExisting(id, event, element) {
        event.stopPropagation();
        element.closest('.existing-thumb').style.display = 'none';
        document.getElementById('deleted-images-container').innerHTML += `<input type="hidden" name="deleted_images[]" value="${id}">`;
        
        existingCount--;
        updateCountText();
    }

    fileInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const validImageFiles = files.filter(file => file.type.startsWith('image/'));

        if (existingCount + selectedNewFiles.length + validImageFiles.length > 10) {
            alert('Bạn chỉ được có tổng cộng tối đa 10 hình ảnh!');
            validImageFiles.splice(10 - (existingCount + selectedNewFiles.length)); 
        }

        selectedNewFiles = [...selectedNewFiles, ...validImageFiles];
        renderNewPreviews();
    });

    function renderNewPreviews() {
        if(existingCount + selectedNewFiles.length > 0) {
            emptyState.classList.add('hidden');
            previewGrid.classList.remove('hidden');
        }

        document.querySelectorAll('.new-thumb').forEach(el => el.remove());

        selectedNewFiles.forEach((file, index) => {
            const url = URL.createObjectURL(file);
            const isCover = (coverIndexInput.value === index.toString() && coverIdInput.value === "");

            const wrapper = document.createElement('div');
            wrapper.className = `thumb-wrapper new-thumb relative aspect-square rounded-xl overflow-hidden shadow-sm cursor-pointer group border-2 ${isCover ? 'border-emerald-500 ring-4 ring-emerald-50' : 'border-gray-200'}`;
            
            wrapper.onclick = function() {
                setCover('new', index, this);
            }

            wrapper.innerHTML = `
                <img src="${url}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                <div class="cover-badge absolute bottom-0 left-0 w-full bg-emerald-500/90 backdrop-blur text-white text-[10px] font-bold py-1.5 text-center ${isCover ? 'block' : 'hidden'}">ẢNH BÌA</div>
                <div class="overlay absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity items-center justify-center ${isCover ? 'hidden' : 'flex'}">
                    <span class="text-white text-[10px] font-bold uppercase tracking-wider border border-white px-2 py-1 rounded shadow">Chọn làm bìa</span>
                </div>
                <button type="button" class="absolute top-2 right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity transform hover:scale-110 shadow-md" data-index="${index}">
                    <i class="fa-solid fa-xmark text-xs pointer-events-none"></i>
                </button>
            `;

            wrapper.querySelector('button').onclick = function(e) {
                e.stopPropagation();
                selectedNewFiles.splice(index, 1);
                if(coverIndexInput.value == index && coverIdInput.value == "") coverIndexInput.value = "";
                renderNewPreviews();
            }

            previewGrid.insertBefore(wrapper, addMoreBtn);
        });

        updateCountText();
    }

    function setCover(type, value, element) {
        if (type === 'existing') {
            coverIdInput.value = value;
            coverIndexInput.value = "";
        } else {
            coverIndexInput.value = value;
            coverIdInput.value = "";
        }

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

    function updateCountText() {
        let total = existingCount + selectedNewFiles.length;
        countText.textContent = `Đã chọn: ${total}/10 ảnh`;
        addMoreBtn.style.display = total >= 10 ? 'none' : 'flex';
        
        if(total === 0) {
            emptyState.classList.remove('hidden');
            previewGrid.classList.add('hidden');
        }
    }
</script>
@endsection