<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// IMPORT CÁC CONTROLLER NGƯỜI DÙNG (USER)
// ==========================================
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ReportController;

// ==========================================
// IMPORT CÁC CONTROLLER QUẢN TRỊ (ADMIN)
// ==========================================
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminWalletController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\ReportManageController;
use App\Http\Controllers\Admin\ExportController;

/*
|--------------------------------------------------------------------------
| 1. KHU VỰC CÔNG KHAI (PUBLIC ROUTES)
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index'])->name('products.index');
Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/shop/{id}', [ShopController::class, 'show'])->name('shop.show');


/*
|--------------------------------------------------------------------------
| 2. KHU VỰC NGƯỜI DÙNG (YÊU CẦU ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // --- Dashboard & Profile ---
    Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- Quản lý & Đăng tin Sản phẩm ---
    Route::get('/san-pham-cua-toi', [ProductController::class, 'myProducts'])->name('my.products');
    Route::get('/dang-tin', [ProductController::class, 'create'])->name('products.create');
    Route::post('/dang-tin', [ProductController::class, 'store'])->name('products.store');
    Route::get('/san-pham/{id}/sua', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/san-pham/{id}/sua', [ProductController::class, 'update'])->name('products.update');

    // --- Hệ thống Thông báo ---
    Route::get('/notifications', function () {
        $notifications = auth()->user()->notifications()->paginate(15);
        return view('notifications.index', compact('notifications'));
    })->name('notifications.index');

    Route::post('/notifications/mark-as-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read');

    Route::get('/notifications/{id}/click', function ($id) {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead(); 
        return redirect($notification->data['url'] ?? '/');
    })->name('notifications.click');

    Route::delete('/notifications/{id}', function ($id) {
        auth()->user()->notifications()->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    })->name('notifications.destroy');

    // --- Giỏ hàng ---
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add/{id}', [CartController::class, 'add'])->name('add');
        Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    });

    // --- Đơn hàng & Đánh giá ---
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout.store');
        Route::post('/{id}/ship', [OrderController::class, 'shipOrder'])->name('ship');
        Route::post('/{id}/confirm', [OrderController::class, 'confirmReceived'])->name('confirm');
        Route::post('/{id}/reject', [OrderController::class, 'rejectOrder'])->name('reject');
        Route::post('/{id}/review', [ReviewController::class, 'store'])->name('review');
    });

    // --- Quản lý Ví (Wallet) ---
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::post('/withdraw', [WalletController::class, 'withdraw'])->name('withdraw');
    });

    // --- Hệ thống Chat ---
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/tin-nhan', [ChatController::class, 'index'])->name('index');
        Route::post('/start/{product}', [ChatController::class, 'startConversation'])->name('start');
        Route::post('/user/{user}', [ChatController::class, 'startUserChat'])->name('startUser');
        Route::get('/{conversationId}/messages', [ChatController::class, 'fetchMessages'])->name('messages');
        Route::post('/{conversationId}/messages', [ChatController::class, 'sendMessage'])->name('send');
        Route::delete('/{id}', [ChatController::class, 'destroy'])->name('destroy');
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount'])->name('unread-count');
    });

    // --- Tương tác (Yêu thích, Theo dõi, Báo cáo) ---
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/follow/toggle', [FollowController::class, 'toggle'])->name('follow.toggle');
    Route::post('/products/{product}/report', [ReportController::class, 'store'])->name('reports.store');
});


/*
|--------------------------------------------------------------------------
| 3. KHU VỰC QUẢN TRỊ VIÊN (ADMIN ROUTES)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // --- Dashboard ---
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // BỔ SUNG ROUTE XUẤT BÁO CÁO TỔNG QUAN PDF
    Route::get('/dashboard/export', [AdminDashboardController::class, 'exportPDF'])->name('dashboard.export');

    // --- Quản lý Tài khoản (Users) ---
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::post('/store', [AdminUserController::class, 'store'])->name('store');
        Route::put('/update/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::patch('/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggle-status');
        Route::delete('/destroy/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
    });

    // --- Quản lý Danh mục (Categories) ---
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::post('/store', [CategoryController::class, 'store'])->name('store');
        Route::put('/update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/destroy/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    });

    // --- Quản lý Sản phẩm (Products) ---
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminProductController::class, 'show'])->name('show');
        Route::patch('/{id}/approve', [AdminProductController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [AdminProductController::class, 'reject'])->name('reject');
        Route::patch('/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('toggle-status');
        Route::patch('/{id}/push', [AdminProductController::class, 'pushTin'])->name('push');
        Route::delete('/{id}/destroy', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/bulk', [AdminProductController::class, 'bulkAction'])->name('bulk');
    });

    // --- Quản lý Báo cáo vi phạm (Reports) ---
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [ReportManageController::class, 'index'])->name('index');
        Route::patch('/{report}/handle', [ReportManageController::class, 'handle'])->name('handle');
    });

    // --- Quản lý Ví (Wallets) ---
    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [AdminWalletController::class, 'index'])->name('index');
        Route::patch('/{id}/approve', [AdminWalletController::class, 'approve'])->name('approve');
        Route::patch('/{id}/reject', [AdminWalletController::class, 'reject'])->name('reject');
    });

    // --- Quản lý Giao dịch / Đơn hàng (Orders) ---
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
    });

    // --- Hệ thống Xuất báo cáo (Export Excel/PDF) ---
    // Đã fix lỗi URL parameter để phù hợp với ?type=...
    Route::get('/export/{type}', [ExportController::class, 'export'])->name('export');
});

require __DIR__ . '/auth.php';