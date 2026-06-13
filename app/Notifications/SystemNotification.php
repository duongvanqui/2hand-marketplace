<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'type'    => $this->data['type'] ?? 'info',       // success, warning, danger, info
            'icon'    => $this->data['icon'] ?? 'fa-bell',    // fa-check, fa-bag-shopping, fa-xmark, ...
            'title'   => $this->data['title'] ?? 'Thông báo',
            'message' => $this->data['message'] ?? '',
            'url'     => $this->data['url'] ?? '#',
        ];
    }
}