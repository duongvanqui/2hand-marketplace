<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SystemNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Xác định kênh gửi thông báo.
     * Mặc định là 'database', có thể thêm 'mail' hoặc 'broadcast' từ Controller.
     */
    public function via($notifiable)
    {
        // Lấy danh sách kênh từ mảng data truyền vào, nếu không có thì mặc định dùng database
        return $this->data['channels'] ?? ['database'];
    }

    /**
     * KÊNH 1: LƯU VÀO DATABASE (Bảng notifications)
     */
    public function toArray($notifiable)
    {
        return [
            'type'    => $this->data['type'] ?? 'info',       // success, warning, danger, info
            'icon'    => $this->data['icon'] ?? 'fa-bell',    // fa-check, fa-money-bill, ...
            'title'   => $this->data['title'] ?? 'Thông báo hệ thống',
            'message' => $this->data['message'] ?? '',
            'url'     => $this->data['url'] ?? '#',
        ];
    }

    /**
     * KÊNH 2: GỬI REAL-TIME (Cập nhật giao diện không cần F5)
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'type'    => $this->data['type'] ?? 'info',
            'icon'    => $this->data['icon'] ?? 'fa-bell',
            'title'   => $this->data['title'] ?? 'Thông báo mới',
            'message' => $this->data['message'] ?? '',
            'url'     => $this->data['url'] ?? '#',
            'created_at' => now()->toDateTimeString(),
        ]);
    }

    /**
     * KÊNH 3: GỬI EMAIL (Cho các thông báo liên quan đến tiền bạc/bảo mật)
     */
    public function toMail($notifiable)
    {
        // Loại bỏ các thẻ HTML (như <span>, <strong>) để nội dung email hiển thị sạch sẽ
        $cleanMessage = strip_tags($this->data['message']);

        return (new MailMessage)
                    ->subject('🔔 ' . ($this->data['title'] ?? 'Thông báo từ 2HAND MARKET'))
                    ->greeting('Xin chào, ' . ($notifiable->name ?? 'bạn') . '!')
                    ->line($cleanMessage)
                    ->action('Xem chi tiết', url($this->data['url'] ?? '/'))
                    ->line('Cảm ơn bạn đã tin tưởng và sử dụng dịch vụ của 2HAND MARKET!');
    }
}