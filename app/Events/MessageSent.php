<?php

namespace App\Events;

use App\Models\Chat;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $chat;
    public $sender_image;

    public function __construct(Chat $chat, $sender_image)
    {
        $this->chat = $chat;
        $this->sender_image = $sender_image;
    }

    public function broadcastOn(): array
    {
        // Simple public channel for demonstration, but for chat we usually use private.
        // For now, let's use the one mentioned in the user's snippet to match their example.
        return [
            new Channel('my-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'my-event';
    }

    public function broadcastWith(): array
    {
        return [
            'chat' => $this->chat,
            'sender_image' => $this->sender_image,
            'time' => date('h:i A', strtotime($this->chat->created_at))
        ];
    }
}
