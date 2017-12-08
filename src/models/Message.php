<?php

namespace StemateF\LaravelConversation\models;

use Illuminate\Database\Eloquent\Model;
use StemateF\LaravelConversation\models\Conversation;

class Message extends Model
{
    public function conversation()
    {
        return $this->belongsTo(Conversation::class, 'conversation_id');
    }
    public function sender()
    {
        return $this->belongsTo('App\User', 'sender_id');
    }
    public function scopeUnseen($query, $seenDate)
    {
        return $query->whereDate('created_at', $seenDate);
    }
}
