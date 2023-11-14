<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageWasCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct()
    {
        //
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('test-ws'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'test-message';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => 'Test ws message'
        ];
    }
}
