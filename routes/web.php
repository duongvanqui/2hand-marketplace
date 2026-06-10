<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\ChatController;
use App\Models\Conversation;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// 1. Trang chủ danh sách sản phẩm
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// 2. Chi tiết sản phẩm theo slug
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

// 3. Dashboard cá nhân
Route::get('/dashboard', [ProductController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 4. Quản lý hồ sơ cá nhân
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 5. Đăng tin thanh lý sản phẩm
Route::middleware(['auth'])->group(function () {
    Route::get('/dang-tin', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dang-tin', [ProductController::class, 'store'])->name('products.store');
});

// 6. Nhóm chức năng Quản trị (Admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- QUẢN LÝ TÀI KHOẢN (USERS) ---
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('users.store');
    Route::patch('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::put('/users/update/{id}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/destroy/{id}', [AdminUserController::class, 'destroy'])->name('users.destroy');

    // --- QUẢN LÝ DANH MỤC (CATEGORIES) ---
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories/store', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/destroy/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // --- QUẢN LÝ SẢN PHẨM (ADMIN PRODUCTS) ---
    // Route::prefix('products')->name('products.')->group(function () {
    //     Route::get('/', [AdminProductController::class, 'index'])->name('index');
    //     Route::patch('/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
    //     Route::post('/{id}/push', [AdminProductController::class, 'pushTin'])->name('push');
    //     Route::delete('/{id}/destroy', [AdminProductController::class, 'destroy'])->name('destroy');
    // });
    Route::prefix('products')->name('products.')->group(function () {
        // Danh sách + tìm kiếm + lọc
        Route::get('/', [AdminProductController::class, 'index'])->name('index');

        // Xem chi tiết / preview
        Route::get('/{id}', [AdminProductController::class, 'show'])->name('show');

        // Duyệt tin
        Route::patch('/{id}/approve', [AdminProductController::class, 'approve'])->name('approve');

        // Từ chối tin
        Route::patch('/{id}/reject', [AdminProductController::class, 'reject'])->name('reject');

        // Ẩn / Hiện tin
        Route::patch('/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');

        // Đẩy tin lên đầu
        Route::patch('/{id}/push', [AdminProductController::class, 'pushTin'])->name('push');

        // Xóa tin
        Route::delete('/{id}/destroy', [AdminProductController::class, 'destroy'])->name('destroy');
    });

});

// 7.Giỏ hàng:
Route::middleware('auth')->group(function () {
    // Giỏ hàng
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

    // Chốt đơn & Quản lý
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/orders/{id}/ship', [OrderController::class, 'shipOrder'])->name('orders.ship');
    Route::post('/orders/{id}/confirm', [OrderController::class, 'confirmReceived'])->name('orders.confirm');
});

// Quản lý Ví
Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

// Quản lý Ví (Admin)
Route::middleware(['auth', 'admin'])->prefix('admin/wallet')->name('admin.wallet.')->group(function () {
    Route::get('/', [AdminWalletController::class, 'index'])->name('index');
    Route::patch('/{id}/approve', [AdminWalletController::class, 'approve'])->name('approve');
    Route::patch('/{id}/reject', [AdminWalletController::class, 'reject'])->name('reject');
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    // ĐÃ BỔ SUNG: Route cho trang Master Dashboard của Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Các route cũ của bạn giữ nguyên...
    Route::get('/wallet', [AdminWalletController::class, 'index'])->name('wallet.index');
    Route::patch('/wallet/approve/{id}', [AdminWalletController::class, 'approve'])->name('wallet.approve');
    // ...
});

// Chat
Route::middleware(['auth'])->group(function () {
    // ... 
    
    // Nơi lấy danh sách tin nhắn cũ
    Route::get('/chat/{conversationId}/messages', [ChatController::class, 'fetchMessages'])->name('chat.messages');
    
    // Nơi nhận tin nhắn mới khi bấm nút Gửi
    Route::post('/chat/{conversationId}/messages', [ChatController::class, 'sendMessage'])->name('chat.send');
});

// Route để mở trang giao diện Chat
Route::get('/chat/{conversationId}', function ($conversationId) {
    $conversation = Conversation::findOrFail($conversationId);
    return view('chat.show', compact('conversation'));
})->name('chat.show');

// Route để bắt đầu hoặc tiếp tục chat về một sản phẩm
Route::post('/chat/start/{product}', [App\Http\Controllers\ChatController::class, 'startConversation'])
    ->name('chat.start')
    ->middleware('auth');

// Trang danh sách hộp thư
Route::get('/tin-nhan', [App\Http\Controllers\ChatController::class, 'index'])
    ->name('chat.index')
    ->middleware('auth');   

require __DIR__ . '/auth.php';
