<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Bộ lọc phân quyền & trạng thái hoạt động nếu có
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        if ($request->has('status') && $request->status !== null && $request->status != '') {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role'     => 'required|string|in:user,admin',
            'status'   => 'required|in:0,1',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'status'   => $request->status,
            'address'  => $request->address,
        ]);

        return redirect()->back()->with('success', 'Thêm tài khoản mới thành công!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'role'     => 'required|string|in:user,admin',
            'status'   => 'required|in:0,1',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:255',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;
        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Xử lý đồng bộ trạng thái sản phẩm theo trạng thái User
        if ($user->status == 0) {
            $user->products()->update(['status' => 0]);
            return redirect()->back()->with('success', 'Cập nhật và khóa toàn bộ sản phẩm của tài khoản thành công!');
        } else {
            // Nếu update thành Hoạt động (1), khôi phục luôn sản phẩm
            $user->products()->update(['status' => 1]);
            return redirect()->back()->with('success', 'Cập nhật thông tin tài khoản thành công!');
        }
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Đảo ngược trạng thái hiện tại (0 thành 1, 1 thành 0)
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        if ($user->status == 0) {
            // Khi bị khóa -> Ẩn toàn bộ sản phẩm (đưa về 0)
            $user->products()->update(['status' => 0]); 
            $message = 'Đã khóa tài khoản và ẨN toàn bộ sản phẩm của người dùng này!';
        } else {
            // THIẾU ĐOẠN NÀY: Khi mở khóa -> Khôi phục toàn bộ sản phẩm (đưa về 1)
            $user->products()->update(['status' => 1]); 
            $message = 'Đã mở khóa tài khoản và KHÔI PHỤC sản phẩm thành công!';
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (auth()->id() == $user->id) {
            return redirect()->back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình!');
        }

        $user->delete();
        return redirect()->back()->with('success', 'Xóa tài khoản thành công!');
    }
}