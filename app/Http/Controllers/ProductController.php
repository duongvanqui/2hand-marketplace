<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * 1. Hiển thị danh sách sản phẩm ngoài màn hình chính (Index)
     */
    public function index(Request $request)
    {
        // Khởi tạo query Builder lấy các sản phẩm đã được duyệt
        $query = Product::where('status', 'approved')->with(['images', 'category']);

        // [1] Lọc theo Từ khóa Tìm kiếm
       if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function($q) use ($searchTerm) {
                // 1. Tìm trong tên sản phẩm
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  // 2. Hoặc tìm trong mô tả
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  // 3. Hoặc tìm xem tên Danh mục có chứa từ khóa này không (Cực kỳ quan trọng)
                  ->orWhereHas('category', function($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // [2] Lọc theo Danh mục (Đã fix lỗi Cha - Con)
        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            
            // Tìm chính ID đó VÀ tất cả ID của các danh mục con thuộc về nó
            $categoryIds = Category::where('id', $categoryId)
                                   ->orWhere('parent_id', $categoryId)
                                   ->pluck('id');
            
            // Lọc sản phẩm nằm trong mảng ID vừa tìm được
            $query->whereIn('category_id', $categoryIds);
        }

        // Sắp xếp ưu tiên các tin đang được "Đẩy lên" (pushed_until còn hạn), sau đó mới tới ngày đăng mới nhất
        $products = $query->orderByRaw('pushed_until > NOW() DESC')
                          ->latest()
                          ->paginate(12);

        // Lấy danh mục gốc để hiển thị ra các ô "Khám phá danh mục" trên trang chủ
        $rootCategories = Category::whereNull('parent_id')->get();

        return view('products.index', compact('products', 'rootCategories'));
    }

    /**
     * 2. Hiển thị trang biểu mẫu đăng tin bán đồ cũ (Create)
     */
    public function create()
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * 3. Xử lý lưu thông tin khi người dùng nhấn nút "ĐĂNG TIN NGAY" (Store)
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:150',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'condition_pct' => 'required|integer|between:1,100',
            'description' => 'required',
            'specifications' => 'nullable|string',
            'location' => 'required',
            'images' => 'required|array|min:1|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048', 
        ]);

        $product = Product::create([
            'user_id' => auth()->id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'specifications' => $request->specifications,
            'original_price' => $request->price,
            'price' => $request->price,
            'condition_pct' => $request->condition_pct,
            'location' => $request->location,
            'status' => 'pending', 
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_main' => ($index === 0) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Đăng tin thành công! Vui lòng chờ hệ thống phê duyệt.');
    }

    /**
     * 4. Xem chi tiết một sản phẩm đồ cũ (Show)
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)->with(['images', 'category', 'user'])->firstOrFail();
        $product->increment('view_count'); 

        return view('products.show', compact('product'));
    }

    /**
     * 5. Hiển thị trang Dashboard cá nhân kèm thống kê
     */
    public function dashboard()
    {
        $user = Auth::user();

        $products = Product::where('user_id', $user->id)
            ->with('category')
            ->latest()
            ->paginate(5);

        $totalApproved = Product::where('user_id', $user->id)->where('status', 'approved')->count();
        $totalViews = Product::where('user_id', $user->id)->sum('view_count');
        $totalRejected = Product::where('user_id', $user->id)->where('status', 'rejected')->count();

        return view('dashboard', compact('products', 'totalApproved', 'totalViews', 'totalRejected'));
    }
}