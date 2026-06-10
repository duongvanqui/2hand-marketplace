@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white font-bold shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-orange-500 font-medium">Quản trị viên</span>
            </div>
        </div>
        <nav class="flex-1 p-3 space-y-1">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-orange-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>
            <div class="border-t border-gray-100 my-2"></div>
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-orange-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 rounded-xl transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>
            @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quản trị hệ thống</p>
                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Tài khoản</span>
                </a>
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-indigo-50 hover:text-indigo-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-list text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Danh mục</span>
                </a>
                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-indigo-50 text-indigo-600 font-semibold rounded-xl transition mt-1">
                    <i class="fa-solid fa-newspaper text-lg w-6 shrink-0"></i>
                    <span x-show="sidebarOpen">Quản lý Sản phẩm</span>
                </a>
            </div>
            @endif
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 p-8 overflow-y-auto">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-3">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <a href="{{ route('admin.products.index') }}" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Chi tiết tin đăng</h1>
            </div>

            {{-- Hành động nhanh --}}
            <div class="flex items-center gap-2">
                @if(in_array($product->status, ['pending', 'rejected']))
                <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" onclick="return confirm('Duyệt tin này?')"
                        class="px-4 py-2 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-medium transition">
                        <i class="fa-solid fa-circle-check mr-1"></i> Duyệt tin
                    </button>
                </form>
                @endif
                @if(in_array($product->status, ['pending', 'approved']))
                <button onclick="openModal('modal-reject')"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm font-medium transition">
                    <i class="fa-solid fa-circle-xmark mr-1"></i> Từ chối
                </button>
                @endif
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 font-medium">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Nội dung tin (2/3) --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Ảnh --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Hình ảnh sản phẩm</h3>
                    @if($product->images && $product->images->count() > 0)
                    <div class="grid grid-cols-4 gap-2">
                        @foreach($product->images as $img)
                        <div class="aspect-square rounded-xl overflow-hidden border border-gray-100">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="py-8 text-center text-gray-400 bg-gray-50 rounded-xl">
                        <i class="fa-solid fa-image text-3xl mb-2 block"></i>
                        Chưa có ảnh sản phẩm
                    </div>
                    @endif
                </div>

                {{-- Thông tin --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between mb-4">
                        <h2 class="text-xl font-bold text-gray-900 flex-1 mr-4">{{ $product->title }}</h2>
                        @php
                            $colors = ['pending'=>'bg-yellow-50 text-yellow-600','approved'=>'bg-green-50 text-green-600','rejected'=>'bg-red-50 text-red-600','sold'=>'bg-blue-50 text-blue-600','hidden'=>'bg-gray-100 text-gray-500'];
                            $labels = ['pending'=>'Chờ duyệt','approved'=>'Đã duyệt','rejected'=>'Từ chối','sold'=>'Đã bán','hidden'=>'Đã ẩn'];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap {{ $colors[$product->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $labels[$product->status] ?? $product->status }}
                        </span>
                    </div>

                    <div class="flex items-center gap-3 mb-6">
                        <span class="text-2xl font-bold text-orange-500">{{ number_format($product->price) }}đ</span>
                        @if($product->original_price)
                        <span class="text-base text-gray-400 line-through">{{ number_format($product->original_price) }}đ</span>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-3 mb-6 text-sm">
                        <div class="flex items-center gap-2 text-gray-600 bg-gray-50 rounded-xl p-3">
                            <i class="fa-solid fa-tag w-4 text-gray-400"></i>
                            <div>
                                <p class="text-xs text-gray-400">Danh mục</p>
                                <p class="font-medium">{{ $product->category->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 bg-gray-50 rounded-xl p-3">
                            <i class="fa-solid fa-location-dot w-4 text-gray-400"></i>
                            <div>
                                <p class="text-xs text-gray-400">Khu vực</p>
                                <p class="font-medium">{{ $product->location ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 bg-gray-50 rounded-xl p-3">
                            <i class="fa-solid fa-star w-4 text-gray-400"></i>
                            <div>
                                <p class="text-xs text-gray-400">Tình trạng</p>
                                <p class="font-medium">{{ $product->condition_pct }}% mới</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 text-gray-600 bg-gray-50 rounded-xl p-3">
                            <i class="fa-solid fa-eye w-4 text-gray-400"></i>
                            <div>
                                <p class="text-xs text-gray-400">Lượt xem</p>
                                <p class="font-medium">{{ number_format($product->view_count) }}</p>
                            </div>
                        </div>
                    </div>

                    @if($product->description)
                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-2">Mô tả sản phẩm</h3>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Panel phải (1/3) --}}
            <div class="space-y-4">

                {{-- Người đăng --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Người đăng tin</h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-orange-100 flex items-center justify-center text-orange-600 font-bold shrink-0">
                            {{ substr($product->user->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">{{ $product->user->name ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">{{ $product->user->email ?? '' }}</p>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 space-y-1.5">
                        <div class="flex justify-between">
                            <span>Ngày đăng:</span>
                            <span class="font-medium text-gray-700">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Cập nhật:</span>
                            <span class="font-medium text-gray-700">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($product->resubmit_count > 0)
                        <div class="flex justify-between">
                            <span>Gửi lại:</span>
                            <span class="font-medium text-orange-600">{{ $product->resubmit_count }} lần</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Lịch sử duyệt --}}
                @if($product->reviewed_at)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <h3 class="text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Lịch sử duyệt</h3>
                    <div class="text-xs text-gray-500 space-y-2">
                        <div class="flex justify-between">
                            <span>Admin xử lý:</span>
                            <span class="font-medium text-gray-700">{{ $product->reviewer->name ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Thời điểm:</span>
                            <span class="font-medium text-gray-700">{{ $product->reviewed_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Kết quả:</span>
                            <span class="font-medium {{ $product->status === 'approved' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $labels[$product->status] ?? $product->status }}
                            </span>
                        </div>
                    </div>
                    @if($product->rejection_reason)
                    <div class="mt-3 p-3 bg-red-50 rounded-xl">
                        <p class="text-xs font-semibold text-red-700 mb-1">Lý do từ chối:</p>
                        <p class="text-xs text-red-600">{{ $product->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Hành động --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-2">
                    <h3 class="text-sm font-bold text-gray-700 mb-3 uppercase tracking-wider">Hành động</h3>

                    @if(in_array($product->status, ['pending', 'rejected']))
                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" onclick="return confirm('Duyệt tin này?')"
                            class="w-full py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 text-sm font-medium transition">
                            <i class="fa-solid fa-circle-check mr-2"></i>Duyệt tin
                        </button>
                    </form>
                    @endif

                    @if(in_array($product->status, ['pending', 'approved']))
                    <button onclick="openModal('modal-reject')"
                        class="w-full py-2.5 bg-red-50 text-red-600 border border-red-200 rounded-xl hover:bg-red-100 text-sm font-medium transition">
                        <i class="fa-solid fa-circle-xmark mr-2"></i>Từ chối tin
                    </button>
                    @endif

                    @if(in_array($product->status, ['approved', 'hidden']))
                    <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full py-2.5 bg-yellow-50 text-yellow-600 border border-yellow-200 rounded-xl hover:bg-yellow-100 text-sm font-medium transition">
                            <i class="fa-solid {{ $product->status === 'approved' ? 'fa-eye-slash' : 'fa-eye' }} mr-2"></i>
                            {{ $product->status === 'approved' ? 'Ẩn tin' : 'Hiện tin' }}
                        </button>
                    </form>
                    @endif

                    <button onclick="openModal('modal-push')"
                        class="w-full py-2.5 bg-purple-50 text-purple-600 border border-purple-200 rounded-xl hover:bg-purple-100 text-sm font-medium transition">
                        <i class="fa-solid fa-arrow-up mr-2"></i>Đẩy tin lên đầu
                    </button>

                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                        onsubmit="return confirm('Xóa tin này? Hành động không thể hoàn tác.')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-gray-50 text-gray-500 border border-gray-200 rounded-xl hover:bg-red-50 hover:text-red-500 hover:border-red-200 text-sm font-medium transition">
                            <i class="fa-solid fa-trash-can mr-2"></i>Xóa tin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

{{-- Modal: Từ chối --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Từ chối tin đăng</h3>
        <form action="{{ route('admin.products.reject', $product->id) }}" method="POST">
            @csrf @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Lý do từ chối</label>
                <select class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm mb-2 focus:outline-none focus:border-red-400"
                    onchange="if(this.value) document.getElementById('rejection_reason').value = this.value">
                    <option value="">-- Chọn lý do có sẵn --</option>
                    <option value="Ảnh sản phẩm không rõ nét hoặc thiếu ảnh.">Ảnh không rõ nét / thiếu ảnh</option>
                    <option value="Mô tả sản phẩm không đầy đủ hoặc không chính xác.">Mô tả không đầy đủ</option>
                    <option value="Giá bán không hợp lý so với thực tế.">Giá không hợp lý</option>
                    <option value="Nội dung vi phạm quy định của sàn.">Vi phạm quy định</option>
                    <option value="Danh mục không phù hợp với sản phẩm.">Sai danh mục</option>
                </select>
                <textarea id="rejection_reason" name="rejection_reason" rows="3" required
                    placeholder="Nhập lý do chi tiết..."
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-50 resize-none">{{ $product->rejection_reason }}</textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-reject')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm">Từ chối</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Đẩy tin --}}
<div id="modal-push" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5">Đẩy tin lên đầu</h3>
        <form action="{{ route('admin.products.push', $product->id) }}" method="POST">
            @csrf @method('PATCH')
            <input type="hidden" name="days" id="push-days" value="3">
            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Thời gian đẩy tin</label>
                <div class="grid grid-cols-3 gap-2">
                    @foreach([1 => '1 ngày', 3 => '3 ngày', 7 => '7 ngày'] as $d => $label)
                    <button type="button" onclick="selectDays({{ $d }}, this)"
                        class="push-day-btn py-2.5 border-2 rounded-xl text-sm font-medium transition {{ $d === 3 ? 'border-purple-500 bg-purple-50 text-purple-700' : 'border-gray-200 text-gray-600 hover:border-purple-300' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
                @if($product->pushed_until && $product->pushed_until > now())
                <p class="text-xs text-purple-600 mt-2">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    Đang đẩy đến: {{ $product->pushed_until->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-push')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 text-sm">
                    <i class="fa-solid fa-arrow-up mr-1"></i> Đẩy tin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function selectDays(days, btn) {
    document.getElementById('push-days').value = days;
    document.querySelectorAll('.push-day-btn').forEach(b => {
        b.classList.remove('border-purple-500', 'bg-purple-50', 'text-purple-700');
        b.classList.add('border-gray-200', 'text-gray-600');
    });
    btn.classList.remove('border-gray-200', 'text-gray-600');
    btn.classList.add('border-purple-500', 'bg-purple-50', 'text-purple-700');
}
</script>
@endsection