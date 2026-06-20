<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProductLockedNotification extends Notification
{
    use Queueable;

    protected $product;
    protected $reason;

    public function __construct(Product $product, $reason)
    {
        $this->product = $product;
        $this->reason = $reason;
    }

    public function via($notifiable)
    {
        return ['database']; // Chỉ lưu vào Database để hiện ở quả chuông thông báo
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'title' => 'Sản phẩm của bạn đã bị khóa',
            'message' => 'Sản phẩm "' . $this->product->title . '" đã bị quản trị viên khóa do vi phạm: ' . $this->reason . '. Vui lòng liên hệ Admin để biết thêm chi tiết.',
            'icon' => 'fa-solid fa-lock',
            'color' => 'text-red-500',
            'url' => '#' // Có thể trỏ về trang hỗ trợ hoặc giữ nguyên
        ];
    }
}