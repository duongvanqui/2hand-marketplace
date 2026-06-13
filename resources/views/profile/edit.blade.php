@extends('layouts.admin')

@section('title', 'Cài đặt tài khoản - 2HAND')
@section('header_title', 'Cài đặt tài khoản cá nhân')

@section('content')
<div class="pb-10">
    
    {{-- HIỂN THỊ THÔNG BÁO --}}
    @if (session('status') === 'profile-updated')
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl shadow-sm font-bold mb-8 flex items-center gap-3 animate-fade-in-down">
            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0"><i class="fa-solid fa-check"></i></div>
            Cập nhật thông tin tài khoản thành công!
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- ============================================== --}}
        {{-- FORM 1: THÔNG TIN CÁ NHÂN --}}
        {{-- ============================================== --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-300">
            <header class="mb-8 border-b border-gray-50 pb-5">
                <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm"><i class="fa-solid fa-id-badge"></i></div>
                    Hồ sơ cá nhân
                </h2>
                <p class="text-sm text-gray-500 mt-2 ml-10">Cập nhật ảnh đại diện và thông tin liên hệ của bạn để người mua dễ dàng nhận diện.</p>
            </header>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('patch')

                {{-- KHU VỰC AVATAR --}}
                <div x-data="{ preview: null }" class="flex items-center gap-6 p-4 bg-gray-50/50 rounded-2xl border border-gray-100 border-dashed">
                    <div class="relative w-24 h-24 rounded-full border-4 border-white shadow-md overflow-hidden flex-shrink-0 bg-emerald-50 group">
                        <template x-if="preview">
                            <img :src="preview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!preview">
                            @if(Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-emerald-500 text-3xl font-bold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                        </template>
                    </div>
                    <div>
                        <label class="cursor-pointer bg-white px-5 py-2.5 border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition-all inline-flex items-center gap-2 group">
                            <i class="fa-solid fa-cloud-arrow-up text-gray-400 group-hover:text-emerald-500 transition-colors"></i> Tải ảnh mới lên
                            <input type="file" name="avatar" class="hidden" accept="image/*" @change="preview = URL.createObjectURL($event.target.files[0])">
                        </label>
                        <p class="text-[11px] text-gray-400 mt-2.5 font-medium"><i class="fa-solid fa-circle-info mr-1"></i>Định dạng: JPG, PNG. Tối đa 2MB.</p>
                    </div>
                </div>

                {{-- HỌ VÀ TÊN --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Họ và tên</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-user"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition-all font-bold text-gray-800">
                    </div>
                </div>

                {{-- EMAIL (Chỉ đọc) --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Email (Tài khoản đăng nhập)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required readonly title="Email không thể thay đổi"
                            class="w-full pl-11 pr-4 py-3 bg-gray-100 border border-gray-200 rounded-xl outline-none text-gray-500 font-medium cursor-not-allowed">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-red-400 text-xs font-medium">
                            <i class="fa-solid fa-lock mr-1"></i> Không thể đổi
                        </div>
                    </div>
                </div>

                {{-- SỐ ĐIỆN THOẠI --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Số điện thoại liên hệ</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="Nhập số điện thoại của bạn..." 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition-all font-bold text-gray-800">
                    </div>
                </div>

                {{-- ĐỊA CHỈ --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Địa chỉ mặc định</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <input type="text" name="address" value="{{ old('address', Auth::user()->address) }}" placeholder="Địa chỉ giao dịch, nhận hàng..." 
                            class="w-full pl-11 pr-4 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-emerald-400 focus:ring-4 focus:ring-emerald-50 transition-all font-bold text-gray-800">
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-50">
                    <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-bold py-3.5 px-8 rounded-xl shadow-lg shadow-emerald-200 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Lưu thông tin cá nhân
                    </button>
                </div>
            </form>
        </div>

        {{-- ============================================== --}}
        {{-- FORM 2: ĐỔI MẬT KHẨU --}}
        {{-- ============================================== --}}
        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-gray-100 h-fit hover:shadow-lg hover:border-gray-300 transition-all duration-300">
            <header class="mb-8 border-b border-gray-50 pb-5">
                <h2 class="text-xl font-black text-gray-900 flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-sm"><i class="fa-solid fa-shield-halved"></i></div>
                    Bảo mật mật khẩu
                </h2>
                <p class="text-sm text-gray-500 mt-2 ml-10">Đảm bảo tài khoản của bạn đang sử dụng mật khẩu dài, ngẫu nhiên để giữ an toàn.</p>
            </header>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                {{-- MẬT KHẨU HIỆN TẠI --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Mật khẩu hiện tại</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="current_password" required 
                            class="w-full pl-11 pr-12 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all font-medium text-gray-800">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-500 transition-colors outline-none">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="text-red-500 text-xs font-bold mt-2 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- MẬT KHẨU MỚI --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Mật khẩu mới</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required 
                            class="w-full pl-11 pr-12 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all font-medium text-gray-800">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-500 transition-colors outline-none">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                    @error('password', 'updatePassword')
                        <p class="text-red-500 text-xs font-bold mt-2 flex items-center gap-1"><i class="fa-solid fa-circle-exclamation"></i> {{ $message }}</p>
                    @enderror
                </div>

                {{-- XÁC NHẬN MẬT KHẨU --}}
                <div x-data="{ show: false }">
                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-2">Xác nhận mật khẩu mới</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <i class="fa-solid fa-check-double"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password_confirmation" required 
                            class="w-full pl-11 pr-12 py-3 bg-gray-50/80 border border-gray-200 rounded-xl outline-none focus:bg-white focus:border-gray-400 focus:ring-4 focus:ring-gray-100 transition-all font-medium text-gray-800">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-emerald-500 transition-colors outline-none">
                            <i class="fa-solid" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-4 border-t border-gray-50">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-3.5 px-8 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-arrows-rotate"></i> Đổi mật khẩu
                    </button>

                    @if (session('status') === 'password-updated')
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" x-transition class="flex items-center gap-1.5 text-sm text-green-600 font-bold bg-green-50 px-3 py-1.5 rounded-lg border border-green-100">
                            <i class="fa-solid fa-check"></i> Đã thay đổi
                        </div>
                    @endif
                </div>
            </form>
        </div>

    </div>
</div>
@endsection