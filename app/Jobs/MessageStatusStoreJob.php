<?php

namespace App\Jobs;

use App\Events\MessageStatusStoreEvent;
use App\Models\Message;
use App\Models\MessageStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MessageStatusStoreJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly array $data,
        private readonly Message $message,
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->data['user_ids'] as $user_id) {

            MessageStatus::query()->create([
                'chat_id' => $this->data['chat_id'],
                'message_id' => $this->message->id,
                'user_id' => $user_id,
            ]);

            $count = MessageStatus::query()
                ->where('chat_id', $this->data['chat_id'])
                ->where('user_id', $user_id)
                ->where('is_read', false)
                ->count();

            broadcast(
                new MessageStatusStoreEvent(
                    $count,
                    (int)$this->data['chat_id'],
                    (int)$user_id,
                    $this->message
                )
            );

        }
    }
}
