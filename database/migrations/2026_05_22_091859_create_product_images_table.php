<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            // Khóa ngoại liên kết trực tiếp tới ID của bảng products
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            // Lưu trữ đường dẫn file ảnh trong thư mục storage
            $table->string('image_path');
            // Đánh dấu ảnh đại diện hiển thị ngoài trang chủ (1: chính, 0: phụ)
            $table->tinyInteger('is_main')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};