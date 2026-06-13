<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChatController;

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminDashboardController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| KHU VỰC CÔNG KHAI (PUBLIC ROUTES)
|--------------------------------------------------------------------------
*/
Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');

/*
|--------------------------------------------------------------------------
| KHU VỰC NGƯỜI DÙNG (YÊU CẦU ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    
    // 1. Dashboard & Profile
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2. Đăng tin sản phẩm
    Route::get('/dang-tin', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dang-tin', [ProductController::class, 'store'])->name('products.store');

    // 3. Hệ thống Thông báo (Notifications)
    Route::get('/notifications', function() { 
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications')); 
    })->name('notifications.index');

    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');
    Route::get('/notifications/{id}/click', function($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead(); // Đánh dấu đã đọc
        
        // Chuyển hướng đến URL đích lưu trong thông báo (nếu không có thì về trang chủ)
        return redirect($notification->data['url'] ?? '/');
    })->name('notifications.click');
    // Xóa một thông báo (bằng Ajax)
    Route::delete('/notifications/{id}', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->delete();
        return response()->json(['success' => true]);
    })->name('notifications.destroy');

    // 4. Giỏ hàng & Đơn hàng
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    });

    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
        Route::post('/{id}/ship', [OrderController::class, 'shipOrder'])->name('ship');
        Route::post('/{id}/confirm', [OrderController::class, 'confirmReceived'])->name('confirm');
    });

    // 5. Quản lý Ví (Ví người dùng)
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
    });

    // 6. Hệ thống Chat
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/tin-nhan', [ChatController::class, 'index'])->name('index');
        Route::post('/start/{product}', [ChatController::class, 'startConversation'])->name('start');
        Route::get('/{conversationId}/messages', [ChatController::class, 'fetchMessages'])->name('messages');
        Route::post('/{conversationId}/messages', [ChatController::class, 'sendMessage'])->name('send');
        Route::delete('/{id}', [ChatController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
    });
});

/*
|--------------------------------------------------------------------------
| KHU VỰC QUẢN TRỊ VIÊN (ADMIN ROUTES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/store', [AdminUserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/destroy/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // Quản lý Danh mục
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // Quản lý Sản phẩm
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminProductController::class, 'show'])->name('show');
        Route::patch('/{id}/approve', [AdminProductController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [AdminProductController::class, 'reject'])->name('reject');
        Route::patch('/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{id}/push', [AdminProductController::class, 'pushTin'])->name('push');
        Route::delete('/{id}/destroy', [AdminProductController::class, 'destroy'])->name('destroy');
    });

    // Quản lý Ví & Giao dịch
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [AdminWalletController::class, 'index'])->name('index');
        Route::patch('/{id}/approve', [AdminWalletController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [AdminWalletController::class, 'reject'])->name('reject');
    });
});

require __DIR__ . '/auth.php';