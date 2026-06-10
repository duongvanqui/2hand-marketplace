<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách danh mục (Tích hợp Tìm kiếm & Phân trang)
     */
    public function index(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ request (Thanh input name="search")
       $search = $request->input('search');

        // Lấy danh mục cha (parent_id là null) để hiển thị trong form chọn
        $parentCategories = Category::whereNull('parent_id')->get();

        // Lấy tất cả danh mục, kèm theo danh mục cha của nó (with('parent'))
        $categories = Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->with('parent')->latest()->paginate(10);

        return view('admin.categories', compact('categories', 'parentCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Thêm danh mục thành công!');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Tránh việc chọn chính nó làm cha
        if ($request->parent_id == $id) {
            return redirect()->back()->with('error', 'Danh mục không thể tự làm cha của chính mình.');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Cập nhật thành công!');
    }

    /**
     * Xóa danh mục sản phẩm
     */
    public function destroy($id)
{
    // Tìm danh mục, nếu không thấy trả về lỗi 404
    $category = Category::findOrFail($id);
    
    // Đếm số lượng sản phẩm thuộc danh mục này
    $productCount = $category->products()->count();

    // Nếu số lượng lớn hơn 0, chặn không cho xóa và trả về thông báo lỗi
    if ($productCount > 0) {
        return redirect()->back()->with('error', "Không thể xóa! Danh mục \"{$category->name}\" hiện đang có {$productCount} sản phẩm.");
    }
    
    // Nếu không có sản phẩm nào mới thực hiện xóa
    $category->delete();
    return redirect()->back()->with('success', 'Xóa danh mục sản phẩm thành công!');
}
}