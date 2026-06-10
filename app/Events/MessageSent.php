<?php

namespace App\Events;

use App\Models\Message; // Gọi Model Message vào đây
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// CHÚ Ý: Phải có "implements ShouldBroadcast" thì Laravel mới biết đem nó lên Pusher
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message; // Biến này sẽ chứa nội dung tin nhắn mang đi phát sóng

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        // Khi Sự kiện được gọi, nó sẽ nhận dữ liệu tin nhắn từ Controller
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Phát sóng vào một kênh Riêng tư (Private) gắn với ID của phòng chat
        // Ví dụ phòng chat số 5 -> Kênh tên là "chat.5"
        return [
            new PrivateChannel('chat.' . $this->message->conversation_id),
        ];
    }

    /**
     * Tên của sự kiện khi phát xuống màn hình Javascript
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}