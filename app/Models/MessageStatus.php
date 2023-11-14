<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageStatus extends Model
{
    use HasFactory;

    protected $table = 'message_status';
    protected $fillable = [
        'chat_id',
        'message_id',
        'user_id',
        'is_read',
    ];
}
