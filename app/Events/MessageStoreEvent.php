<?php

namespace App\Events;

use App\Http\Resources\Message\MessageBroadcastResource;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStoreEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        private readonly Message $message
    )
    {}

    public function broadcastOn(): array
    {
        return [
            new Channel('store-message.' . $this->message->chat_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'store-message';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => MessageBroadcastResource::make(
                $this->message
            )
                ->resolve()
        ];
    }
}
