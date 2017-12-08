<?php

namespace StemateF\LaravelConversation;

use StemateF\LaravelConversation\models\Conversation;
use StemateF\LaravelConversation\models\Message;

trait Messageable
{
    public function messages()
    {
        return $this->hasMany('StemateF\LaravelConversation\models\Message', 'sender_id');
    }

    public function conversations()
    {
        return $this->belongsToMany('StemateF\LaravelConversation\models\Conversation', 'conversation_user', 'user_id', 'conversation_id')->withTimestamps()->withPivot('seen_at');
    }
    public function conversation($conversation)
    {
        return $this->conversations()->where('conversation_id', $conversation)->first();
    }
    public function createConversation($participants)
    {

        $participants[] = $this->id;

        $conversation = Conversation::create();
        $conversation->users()->sync($participants);
        return $conversation;

    }

    public function findOrCreateConversation($participants)
    {

        //TODO
        //====
        //FIND A BETER IMPLEMENTATION
        $conversations       = $this->conversations()->byParticipantsCount(count($participants) + 1)->get();
        $finalResult         = collect();
        $participantsClone   = $participants;
        $participantsClone[] = $this->id;
        foreach ($conversations as $conversation) {
            $conversationMembers = $conversation->users->pluck('id')->toArray();
            $result              = array_diff($participantsClone, $conversationMembers);

            if (empty($result)) {
                $finalResult->push($conversation);
            }
        }

        if ($finalResult->isNotEmpty()) {
            return $conversation;
        } else {
            return $this->createConversation($participants);
        }
    }
    public function markSeen($conversation)
    {
        $this->conversations()->updateExistingPivot($conversation, ['is_seen' => 1, 'seen_at' => now()]);
    }

    public function send(Conversation $conversation, $message)
    {
        $newMessage                  = new Message;
        $newMessage->body            = $message;
        $newMessage->conversation_id = $conversation->id;
        $this->messages()->save($newMessage);
        $conversation->markUnseen($this);
    }
    public function inbox()
    {
        return $this->conversations()->with('participants', 'lastMessage')->latest()->paginate(config('conversation.max_conversation_number'));
    }
}
