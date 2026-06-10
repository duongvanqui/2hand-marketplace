@extends('layouts.app')

@section('content')
<div class="flex min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

    {{-- SIDEBAR --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-white shadow-xl min-h-screen transition-all duration-300 flex flex-col border-r border-gray-200 shrink-0">
        <div class="p-4 border-b border-gray-100 flex items-center space-x-3 overflow-hidden">
            <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-bold shrink-0">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div x-show="sidebarOpen" class="overflow-hidden">
                <h2 class="text-sm font-bold text-gray-800 leading-tight truncate">{{ Auth::user()->name }}</h2>
                <span class="text-xs text-green-500 font-medium">
                    {{ Auth::user()->role === 'admin' ? 'Quản trị viên' : 'Thành viên' }}
                </span>
            </div>
        </div>
        <nav class="flex-1 p-3 space-y-1">
            <a href="{{ route('products.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-house text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Xem Trang Chủ</span>
            </a>

            <div class="border-t border-gray-100 my-2"></div>

            <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl font-medium transition-all group">
                <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 group-hover:scale-110 transition-transform"></i>
                <span x-show="sidebarOpen">Tổng quan</span>
            </a>

             <a href="{{ route('orders.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-clipboard-list text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Quản lý Đơn hàng</span>
            </a>

            <a href="{{ route('wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-emerald-500 rounded-xl transition-all group">
                <i class="fa-solid fa-wallet text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Ví 2HAND</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-gray-50 hover:text-gray-900 rounded-xl transition-all group">
                <i class="fa-solid fa-user-gear text-lg w-6 shrink-0 group-hover:scale-110 transition-transform text-center"></i>
                <span x-show="sidebarOpen">Cài đặt tài khoản</span>
            </a>

            @if(Auth::user()->role === 'admin')
            <div class="pt-4 my-2 border-t border-gray-100">
                <p x-show="sidebarOpen" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Quản trị hệ thống</p>

                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-chart-pie text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Tổng quan Admin</span>
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition">
                    <i class="fa-solid fa-users text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý Tài khoản</span>
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-list text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý Danh mục</span>
                </a>

                <a href="{{ route('admin.products.index') }}" class="flex items-center space-x-3 px-4 py-3 bg-emerald-50 text-emerald-600 font-semibold rounded-xl transition mt-1">
                    <i class="fa-solid fa-box text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản lý Sản phẩm</span>
                </a>

                <a href="{{ route('admin.wallet.index') }}" class="flex items-center space-x-3 px-4 py-3 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 rounded-xl transition mt-1">
                    <i class="fa-solid fa-vault text-lg w-6 shrink-0 text-center"></i>
                    <span x-show="sidebarOpen">Quản trị tài chính</span>
                </a>

            </div>
            @endif
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 p-8">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 bg-white rounded-lg shadow border border-gray-200 text-gray-600 hover:bg-gray-50 mr-2">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <h1 class="text-2xl font-bold text-gray-800">Quản Lý Sản Phẩm</h1>
            </div>
        </div>

        {{-- Flash --}}
        @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 text-green-700 rounded-xl border border-green-100 font-medium">
            <i class="fa-solid fa-circle-check mr-2"></i>{{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-100 font-medium">
            <i class="fa-solid fa-circle-exclamation mr-2"></i>{{ session('error') }}
        </div>
        @endif

        {{-- Thống kê nhanh --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase">Tổng tin</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-500">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
            </div>
            <a href="{{ route('admin.products.index', ['status' => 'pending']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border {{ request('status') === 'pending' ? 'border-yellow-300' : 'border-gray-100' }} flex items-center justify-between hover:border-yellow-300 transition">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase">Chờ duyệt</p>
                    <h3 class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-500">
                    <i class="fa-solid fa-clock"></i>
                </div>
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'approved']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border {{ request('status') === 'approved' ? 'border-green-300' : 'border-gray-100' }} flex items-center justify-between hover:border-green-300 transition">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase">Đã duyệt</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-1">{{ $stats['approved'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center text-green-500">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </a>
            <a href="{{ route('admin.products.index', ['status' => 'rejected']) }}"
                class="bg-white p-5 rounded-2xl shadow-sm border {{ request('status') === 'rejected' ? 'border-red-300' : 'border-gray-100' }} flex items-center justify-between hover:border-red-300 transition">
                <div>
                    <p class="text-xs text-gray-400 font-medium uppercase">Từ chối</p>
                    <h3 class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected'] }}</h3>
                </div>
                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center text-red-500">
                    <i class="fa-solid fa-circle-xmark"></i>
                </div>
            </a>
        </div>

        {{-- Bộ lọc --}}
        <div class="mb-6 bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Tìm theo tên sản phẩm, người bán..."
                    class="flex-1 px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 focus:ring-2 focus:ring-indigo-50">

                <select name="status" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 bg-white">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Chờ duyệt</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Từ chối</option>
                    <option value="sold"     {{ request('status') === 'sold'     ? 'selected' : '' }}>Đã bán</option>
                    <option value="hidden"   {{ request('status') === 'hidden'   ? 'selected' : '' }}>Đã ẩn</option>
                </select>

                <select name="category_id" class="px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-indigo-400 bg-white">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gray-800 text-white text-sm rounded-xl hover:bg-gray-700 transition">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i> Tìm
                    </button>
                    @if(request()->hasAny(['search','status','category_id']))
                    <a href="{{ route('admin.products.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-xl hover:bg-gray-200 flex items-center transition">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Bảng --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-400 text-xs font-semibold uppercase border-b border-gray-100">
                            <th class="p-4">STT</th>
                            <th class="p-4">Sản phẩm</th>
                            <th class="p-4">Người bán</th>
                            <th class="p-4">Giá</th>
                            <th class="p-4">Danh mục</th>
                            <th class="p-4">Ngày đăng</th>
                            <th class="p-4">Trạng thái</th>
                            <th class="p-4 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($products as $index => $product)
                        <tr class="hover:bg-gray-50/50 transition-all">
                            <td class="p-4 text-gray-500">{{ $products->firstItem() + $index }}</td>
                            <td class="p-4">
                                <a href="{{ route('admin.products.show', $product->id) }}"
                                    class="font-semibold text-gray-800 hover:text-indigo-600 transition-colors">
                                    {{ $product->title }}
                                </a>
                                @if($product->pushed_until && $product->pushed_until > now())
                                <div class="text-xs text-purple-500 mt-0.5">
                                    <i class="fa-solid fa-arrow-up mr-1"></i>Đẩy đến {{ $product->pushed_until->format('d/m H:i') }}
                                </div>
                                @endif
                                @if($product->status === 'rejected' && $product->rejection_reason)
                                <div class="text-xs text-red-400 mt-0.5 max-w-xs truncate" title="{{ $product->rejection_reason }}">
                                    <i class="fa-solid fa-circle-exclamation mr-1"></i>{{ $product->rejection_reason }}
                                </div>
                                @endif
                            </td>
                            <td class="p-4 text-gray-600">{{ $product->user->name ?? 'N/A' }}</td>
                            <td class="p-4 text-emerald-600 font-bold">{{ number_format($product->price) }}đ</td>
                            <td class="p-4 text-gray-500">{{ $product->category->name ?? 'N/A' }}</td>
                            <td class="p-4 text-gray-500">{{ $product->created_at->format('d/m/Y') }}</td>
                            <td class="p-4">
                                @php
                                    $colors = [
                                        'pending'  => 'bg-yellow-50 text-yellow-600',
                                        'approved' => 'bg-green-50 text-green-600',
                                        'rejected' => 'bg-red-50 text-red-600',
                                        'sold'     => 'bg-blue-50 text-blue-600',
                                        'hidden'   => 'bg-gray-100 text-gray-500',
                                    ];
                                    $labels = [
                                        'pending'  => 'Chờ duyệt',
                                        'approved' => 'Đã duyệt',
                                        'rejected' => 'Từ chối',
                                        'sold'     => 'Đã bán',
                                        'hidden'   => 'Đã ẩn',
                                    ];
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $colors[$product->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $labels[$product->status] ?? $product->status }}
                                </span>
                                @if($product->resubmit_count > 0)
                                <div class="text-xs text-gray-400 mt-0.5">Gửi lại: {{ $product->resubmit_count }}x</div>
                                @endif
                            </td>
                            <td class="p-4">
                                <div class="flex items-center justify-center space-x-1">

                                    {{-- Xem chi tiết --}}
                                    <a href="{{ route('admin.products.show', $product->id) }}"
                                        class="p-1.5 text-blue-500 hover:bg-blue-50 rounded-lg transition-all" title="Xem chi tiết">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>

                                    {{-- Duyệt --}}
                                    @if(in_array($product->status, ['pending', 'rejected']))
                                    <form action="{{ route('admin.products.approve', $product->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" onclick="return confirm('Duyệt tin này?')"
                                            class="p-1.5 text-green-500 hover:bg-green-50 rounded-lg transition-all" title="Duyệt">
                                            <i class="fa-solid fa-circle-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Từ chối --}}
                                    @if(in_array($product->status, ['pending', 'approved']))
                                    <button onclick="openRejectModal({{ $product->id }}, '{{ addslashes($product->title) }}')"
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Từ chối">
                                        <i class="fa-solid fa-circle-xmark"></i>
                                    </button>
                                    @endif

                                    {{-- Ẩn/Hiện --}}
                                    @if(in_array($product->status, ['approved', 'hidden']))
                                    <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                            class="p-1.5 rounded-lg transition-all {{ $product->status === 'approved' ? 'text-yellow-500 hover:bg-yellow-50' : 'text-gray-400 hover:bg-gray-50' }}"
                                            title="{{ $product->status === 'approved' ? 'Ẩn tin' : 'Hiện tin' }}">
                                            <i class="fa-solid {{ $product->status === 'approved' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Đẩy tin --}}
                                    <button onclick="openPushModal({{ $product->id }}, '{{ addslashes($product->title) }}')"
                                        class="p-1.5 text-purple-500 hover:bg-purple-50 rounded-lg transition-all" title="Đẩy tin">
                                        <i class="fa-solid fa-arrow-up"></i>
                                    </button>

                                    {{-- Xóa --}}
                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa tin này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-500 rounded-lg transition-all" title="Xóa">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="p-10 text-center text-gray-400">
                                <i class="fa-solid fa-box-open text-3xl mb-2 block"></i>
                                Không tìm thấy sản phẩm nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($products->hasPages())
            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </main>
</div>

{{-- Modal: Từ chối --}}
<div id="modal-reject" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Từ chối tin đăng</h3>
        <p id="reject-name" class="text-sm text-gray-500 mb-5"></p>
        <form id="form-reject" action="" method="POST">
            @csrf @method('PATCH')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Chọn lý do</label>
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
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-50 resize-none"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-reject')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 text-sm transition">Từ chối tin</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Đẩy tin --}}
<div id="modal-push" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Đẩy tin lên đầu</h3>
        <p id="push-name" class="text-sm text-gray-500 mb-5"></p>
        <form id="form-push" action="" method="POST">
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
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeModal('modal-push')"
                    class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 hover:bg-gray-50 text-sm transition">Hủy</button>
                <button type="submit"
                    class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 text-sm transition">
                    <i class="fa-solid fa-arrow-up mr-1"></i> Đẩy tin
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

function openRejectModal(id, title) {
    document.getElementById('reject-name').textContent = '"' + title + '"';
    document.getElementById('form-reject').action = '/admin/products/' + id + '/reject';
    document.getElementById('rejection_reason').value = '';
    openModal('modal-reject');
}

function openPushModal(id, title) {
    document.getElementById('push-name').textContent = '"' + title + '"';
    document.getElementById('form-push').action = '/admin/products/' + id + '/push';
    openModal('modal-push');
}

function selectDays(days, btn) {
    document.getElementById('push-days').value = days;
    document.querySelectorAll('.push-day-btn').forEach(b => {
        b.className = b.className.replace('border-purple-500 bg-purple-50 text-purple-700', '');
        b.classList.add('border-gray-200', 'text-gray-600');
    });
    btn.classList.remove('border-gray-200', 'text-gray-600');
    btn.classList.add('border-purple-500', 'bg-purple-50', 'text-purple-700');
}
</script>
@endsection