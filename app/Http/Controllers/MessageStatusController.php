<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStatus\MessageStatusUpdateRequest;
use App\Models\MessageStatus;
use Illuminate\Http\Request;

class MessageStatusController extends Controller
{
    public function update(MessageStatusUpdateRequest $request): void
    {
        $data = $request->validated();

        $messageStatus = MessageStatus::query()
            ->where('user_id', $data['user_id'])
            ->where('message_id', $data['message_id'])
            ->update(['is_read' => true]);
    }
}
