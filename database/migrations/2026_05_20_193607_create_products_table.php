<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // Khóa ngoại liên kết chuẩn Laravel
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            
            // Định nghĩa giá tiền (Hỗ trợ định dạng tiền tệ lớn)
            $table->decimal('original_price', 15, 2)->nullable(); // Giá mua gốc
            $table->decimal('price', 15, 2);                      // Giá bán lại
            
            $table->integer('condition_pct'); // Độ mới % (Ví dụ: 95)
            $table->string('location', 255);  // Khu vực bán
            
            // Trạng thái tin đăng sử dụng enum như file cũ của bạn
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold', 'hidden'])->default('pending');
            
            $table->integer('view_count')->default(0); // Lượt xem tin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};