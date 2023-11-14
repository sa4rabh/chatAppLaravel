<?php

namespace App\Http\Requests\MessageStatus;

use Illuminate\Foundation\Http\FormRequest;

class MessageStatusUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message_id' => [
                'required',
                'integer',
                'exists:messages,id',
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }
}
