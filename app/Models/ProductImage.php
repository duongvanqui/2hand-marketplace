<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    // Tên bảng trong CSDL (nếu bạn đặt tên bảng là product_images)
    protected $table = 'product_images';

    // CẤP QUYỀN: Thêm dòng này để cho phép lưu dữ liệu đường dẫn ảnh vào CSDL
    protected $fillable = [
        'product_id', 
        'image_path', 
        'is_main'
    ];

    /**
     * Mối quan hệ ngược lại với Product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}