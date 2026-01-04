<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets; 
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;  
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;

    /**
     * Create a new event instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * The channel the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        // 👇 The channel name — must match your JS Pusher subscription
        return new Channel('my-channel');
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
         return 'my-event';
    }

    /**
     * The data that should be broadcast.
     */
    public function broadcastWith(): array
    {
        // 👇 It’s best to wrap inside a key (like "data") to avoid null values
        return [
            'author' => $this->data['author'] ?? '',
            'title' => $this->data['title'] ?? '',
        ];
    }
}
