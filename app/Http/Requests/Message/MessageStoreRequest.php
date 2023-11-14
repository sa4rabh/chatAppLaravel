<?php

namespace App\Http\Requests\Message;

use Illuminate\Foundation\Http\FormRequest;

class MessageStoreRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'user_id' => $this->user()->id
        ]);
    }
    public function rules(): array
    {
        return [
            'chat_id' => [
                'required',
                'integer',
                'exists:chats,id'
            ],
            'body' => [
                'required',
                'string'
            ],
            'user_ids' => [
                'required',
                'array'
            ],
            'user_ids.*' => [
                'required',
                'integer',
                'exists:users,id'
            ],
            'user_id' => [
                'exists:users,id'
            ],
        ];
    }
}
