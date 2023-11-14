<?php

namespace App\Http\Resources\Chat;

use App\Http\Resources\Message\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? $this->chatWith->name,
            'users' => $this->users,
            'last_message' => $this->lastMessage ? MessageResource::make($this->lastMessage)
                ->resolve() : null,
            'unreadable_count' => $this->unreadable_message_status_count
        ];
    }
}
