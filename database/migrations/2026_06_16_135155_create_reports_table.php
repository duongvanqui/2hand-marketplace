<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('reports', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Người báo cáo
        $table->foreignId('product_id')->constrained()->onDelete('cascade'); // Sản phẩm bị báo cáo
        $table->string('reason'); // Lý do báo cáo (Lừa đảo, Hàng giả...)
        $table->text('details')->nullable(); // Chi tiết cụ thể
        $table->string('status')->default('pending'); // pending (chờ xử lý), resolved (đã giải quyết)
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
