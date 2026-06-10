<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Thêm cột 'balance' (số dư) vào bảng users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 12, 2)->default(0)->after('password');
        });

        // 2. Tạo bảng 'withdrawals' (Yêu cầu rút tiền)
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->text('bank_info'); // Lưu thông tin Tên Ngân hàng, STK, Tên chủ thẻ
            $table->string('status')->default('pending'); // pending (chờ duyệt), approved (đã chuyển), rejected (từ chối)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
        });
    }
};