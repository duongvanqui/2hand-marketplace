<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('buyer_id')->constrained('users')->onDelete('cascade'); // Người mua
        $table->foreignId('seller_id')->constrained('users')->onDelete('cascade'); // Người bán
        $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Món đồ cũ
        
        $table->decimal('total_amount', 12, 2);   // Giá gốc sản phẩm người mua trả
        $table->decimal('fee_amount', 12, 2);     // Phí sàn giữ lại (3%)
        $table->decimal('seller_amount', 12, 2);  // Số tiền thực chuyển cho người bán (97%)
        
        $table->string('payment_method'); // 'banking' (Chuyển khoản), 'wallet' (Ví điện tử)
        
        // CÁC TRẠNG THÁI ĐƠN HÀNG TRỌNG YẾU:
        // 'pending_payment': Chờ người mua nạp/chuyển tiền cho Web
        // 'paid_escrow': Đã nạp tiền vào Web (Web đang giữ tiền - Chờ người bán giao hàng)
        // 'shipped': Người bán xác nhận đã gửi hàng (Đang vận chuyển)
        // 'completed': Người mua bấm Đã nhận hàng (Web trừ 3% phí và cộng tiền cho người bán)
        // 'cancelled': Đơn hàng bị hủy (Hủy kèo, Web hoàn tiền lại cho người mua)
        $table->string('status')->default('pending_payment');
        
        $table->string('shipping_address');
        $table->string('phone_number');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
