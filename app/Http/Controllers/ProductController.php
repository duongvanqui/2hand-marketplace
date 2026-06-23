<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhere('description', 'LIKE', '%' . $searchTerm . '%')
                  ->orWhereHas('category', function($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'LIKE', '%' . $searchTerm . '%');
                  });
            });
        }

        // [2] Lọc theo Danh mục
        if ($request->filled('category_id')) {
            $categoryId = $request->input('category_id');
            
            $categoryIds = Category::where('id', $categoryId)
                                   ->orWhere('parent_id', $categoryId)
                                   ->pluck('id');
            
            $query->whereIn('category_id', $categoryIds);
        }

        // Sắp xếp ưu tiên các tin đang được "Đẩy lên"
        $products = $query->orderByRaw('pushed_until > NOW() DESC')
                          ->latest()
                          ->paginate(15);

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
     * 3. Xử lý lưu thông tin khi đăng tin mới (Store)
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
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120', // Nâng lên 5MB cho thoải mái
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

        // Cập nhật is_main dựa theo lựa chọn cover_image_index từ form
        $coverIndex = $request->input('cover_image_index', 0);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'image_path' => $path,
                    'is_main' => ($index == $coverIndex) ? 1 : 0,
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

    /**
     * 6. Quản lý Sản phẩm của tôi
     */
    public function myProducts(\Illuminate\Http\Request $request)
    {
        $userId = \Illuminate\Support\Facades\Auth::id();

        // 1. TÁCH RIÊNG TỪNG TRẠNG THÁI ĐỂ ĐẾM CHÍNH XÁC
        $totalProducts = \App\Models\Product::where('user_id', $userId)->count();
        $activeProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'approved')->count();
        $pendingProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'pending')->count();
        $soldProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'sold')->count();
        $rejectedProducts = \App\Models\Product::where('user_id', $userId)->where('status', 'rejected')->count();

        // 2. Xử lý bộ lọc và tìm kiếm
        $query = \App\Models\Product::where('user_id', $userId)->with('category');

        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Phân trang kết quả
        $products = $query->latest()->paginate(10);

        // Đừng quên truyền biến $soldProducts và $rejectedProducts xuống View nhé
        return view('products.my_products', compact(
            'products', 'totalProducts', 'activeProducts', 'pendingProducts', 'soldProducts', 'rejectedProducts'
        ));
    }

    /**
     * 7. Hiển thị form sửa tin (Edit)
     */
    public function edit($id)
    {
        $product = Product::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        // BẢO MẬT: Chặn không cho sửa nếu sản phẩm đã bán
        if ($product->status === 'sold') {
            return redirect()->route('my.products')->with('error', 'Sản phẩm đã bán không thể chỉnh sửa.');
        }

        // Lấy danh mục Cha kèm Con để đổ vào Select Box
        $categories = Category::whereNull('parent_id')->with('children')->get(); 

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * 8. Xử lý lưu dữ liệu khi nhấn Cập nhật (Update)
     */
    public function update(Request $request, $id)
    {
        $product = Product::where('id', $id)
                          ->where('user_id', Auth::id())
                          ->firstOrFail();

        // BẢO MẬT: Chặn nếu dùng postman cố tình đẩy request khi sp đã bán
        if ($product->status === 'sold') {
            return redirect()->route('my.products')->with('error', 'Sản phẩm đã bán không thể chỉnh sửa.');
        }

        $request->validate([
            'title' => 'required|max:150',
            'category_id' => 'required',
            'price' => 'required|numeric',
            'condition_pct' => 'required|integer|between:1,100',
            'description' => 'required',
            'specifications' => 'nullable|string',
            'location' => 'required',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Cập nhật thông tin text
        $product->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'condition_pct' => $request->condition_pct,
            'description' => $request->description,
            'specifications' => $request->specifications,
            'location' => $request->location,
            'status' => 'pending', // Đưa về trạng thái chờ duyệt
        ]);

        // XỬ LÝ ẢNH
        
        // 1. Xóa ảnh cũ nếu người dùng bấm nút xóa
        if ($request->has('deleted_images')) {
            $deletedIds = $request->input('deleted_images');
            $imagesToDelete = $product->images()->whereIn('id', $deletedIds)->get();
            
            foreach ($imagesToDelete as $img) {
                // Xóa file vật lý trong storage
                if (Storage::disk('public')->exists($img->image_path)) {
                    Storage::disk('public')->delete($img->image_path);
                }
                // Xóa record trong DB
                $img->delete();
            }
        }

        // 2. Thêm ảnh mới (Nếu có)
        $newUploadedImageIds = [];
        if ($request->hasFile('new_images')) {
            foreach ($request->file('new_images') as $file) {
                $path = $file->store('products', 'public');
                $newImg = $product->images()->create([
                    'image_path' => $path,
                    'is_main' => 0 // Tạm gán bằng 0
                ]);
                $newUploadedImageIds[] = $newImg->id; // Lưu lại ID để tí nữa set ảnh bìa
            }
        }

        // 3. Xử lý cài đặt Ảnh bìa (is_main)
        // Reset tất cả ảnh về 0
        $product->images()->update(['is_main' => 0]);

        if ($request->filled('cover_image_id')) {
            // Trường hợp 1: Người dùng chọn 1 trong các ảnh cũ làm ảnh bìa
            $product->images()->where('id', $request->input('cover_image_id'))->update(['is_main' => 1]);
            
        } elseif ($request->filled('cover_new_index')) {
            // Trường hợp 2: Người dùng chọn 1 ảnh MỚI TẢI LÊN làm ảnh bìa
            $coverIndex = $request->input('cover_new_index');
            if (isset($newUploadedImageIds[$coverIndex])) {
                $product->images()->where('id', $newUploadedImageIds[$coverIndex])->update(['is_main' => 1]);
            }
        } else {
            // Trường hợp 3 (Dự phòng): Không chọn gì, hoặc xóa lỡ tay -> Lấy cái ảnh đầu tiên còn sót lại làm bìa
            $firstImg = $product->images()->first();
            if ($firstImg) {
                $firstImg->update(['is_main' => 1]);
            }
        }

        return redirect()->route('my.products')->with('success', 'Đã cập nhật tin đăng thành công. Vui lòng chờ Quản trị viên duyệt lại!');
    }
}