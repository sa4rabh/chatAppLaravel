<?php

namespace App\Http\Requests\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ChatStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string'
            ],
            'users' => [
                'required',
                'array'
            ],
            'users.*' => [
                'required',
                'integer',
                'exists:users,id'
            ],
        ];
    }
}
