<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;  
use Illuminate\Support\Facades\Auth;

/**
 * NotificationController — Thông báo trong web cho người dùng
 */
class NotificationController extends Controller
{
    // Danh sách thông báo
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        // Đếm chưa đọc (dùng cho badge)
        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    // Đánh dấu đã đọc 1 thông báo
    public function markAsRead($id)
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        return back();
    }

    // Đánh dấu đọc tất cả
    public function markAllRead()
    {
        Auth::user()->unreadNotifications()->update(['read_at' => now()]);
        return back()->with('success', 'Đã đánh dấu đọc tất cả thông báo.');
    }

    // API: lấy số thông báo chưa đọc (dùng cho badge realtime)
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unreadNotifications()->count()
        ]);
    }
}