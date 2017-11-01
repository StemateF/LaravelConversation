<?php

namespace StemateF\LaravelConversation\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use StemateF\LaravelConversation\models\Message;

class Conversation extends Model
{
    public function messages()
    {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public function users()
    {
        return $this->belongsToMany('App\User', 'conversation_user', 'conversation_id', 'user_id')->withTimestamps();
    }

    public function scopeByParticipantsCount($query, $participants)
    {
        $query->has('users', '=', $participants);
        return $query;
    }

    public function markUnseen($sender)
    {
        //find beter implementation ???
        DB::table('conversation_user')
            ->where('conversation_id', $this->id)
            ->where('user_id', '!=', $sender->id)
            ->update(['is_seen' => 0]);
    }
}
