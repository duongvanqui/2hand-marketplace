<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Các trường dữ liệu được phép tương tác hàng loạt
    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'specifications',
        'original_price',
        'price',
        'condition_pct',
        'location',
        'status',
        'pushed_until',
        'view_count'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    /**
     * Mối quan hệ: Một sản phẩm có nhiều hình ảnh đi kèm
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    /**
     * Mối quan hệ: Một sản phẩm phải thuộc về một Danh mục (Category)
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    /**
     * Mối quan hệ BỔ SUNG: Một sản phẩm phải do một Người dùng (User) đăng lên
     * Giúp Admin hiển thị tên người đăng tin ngoài giao diện quản lý sản phẩm
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Một sản phẩm có thể được yêu thích bởi nhiều user
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
}
