<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Lý do từ chối
            $table->text('rejection_reason')->nullable()->after('status');

            // Admin đã duyệt/từ chối
            $table->foreignId('reviewed_by')->nullable()
                  ->after('rejection_reason')
                  ->constrained('users')
                  ->nullOnDelete();

            // Thời điểm duyệt/từ chối
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');

            // Số lần gửi lại
            $table->tinyInteger('resubmit_count')->unsigned()->default(0)->after('reviewed_at');

            // Tin hết hạn
            $table->timestamp('expired_at')->nullable()->after('resubmit_count');

            // Đẩy tin lên đầu
            $table->timestamp('pushed_at')->nullable()->after('expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn([
                'rejection_reason',
                'reviewed_by',
                'reviewed_at',
                'resubmit_count',
                'expired_at',
                'pushed_at',
            ]);
        });
    }
};