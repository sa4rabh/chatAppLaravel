<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_id',
        'user_id',
        'body'
    ];

    public function getTimeAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getIsOwnerAttribute(): bool
    {
        return (int) $this->user_id === (int) auth()->id();
     }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
