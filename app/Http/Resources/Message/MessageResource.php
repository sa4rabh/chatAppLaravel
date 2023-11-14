<?php

namespace App\Http\Resources\Message;

use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'chat_id' => $this->chat_id,
            'user' => UserResource::make($this->user)->resolve(),
            'body' => $this->body,
            'time' => $this->time,
            'is_owner' => $this->isOwner,
        ];
    }
}
