<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Kiểm tra xem người dùng đã đăng nhập chưa và có phải là admin không
        if (Auth::check() && Auth::user()->role === 'admin') {
            
            // 2. Nếu tài khoản của Admin này bị khóa (status == 0), đăng xuất và trả về trang login
            if (Auth::user()->status == 0) {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị ban quản trị khóa!');
            }
            
            return $next($request);
        }

        // Nếu không phải admin, đá về trang chủ
        return redirect('/')->with('error', 'Bạn không có quyền truy cập vào khu vực Quản trị viên!');
    }
}