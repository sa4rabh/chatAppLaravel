<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'users'
    ];

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(
            related: User::class,
            table: 'chat_user',
            foreignPivotKey: 'chat_id',
            relatedPivotKey: 'user_id'
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(
            related: Message::class,
            foreignKey: 'chat_id',
            localKey: 'id'
        );
    }

    public function unreadableMessageStatus(): HasMany
    {
        return $this->hasMany(
            related: MessageStatus::class,
            foreignKey: 'chat_id',
            localKey: 'id'
        )
            ->where('user_id', auth()->id())
            ->where('is_read', false);
    }

    public function lastMessage(): HasOne
    {
        return $this->hasOne(
            related: Message::class,
            foreignKey: 'chat_id',
            localKey: 'id'
        )->latestOfMany()
            ->with('user');
    }

    public function chatWith(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            ChatUser::class,
            'chat_id',
            'id',
            'id',
            'user_id',
        )->where('user_id', '!=', auth()->id());
    }

}
