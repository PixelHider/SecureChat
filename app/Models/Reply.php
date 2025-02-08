<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $table = 'replies';

    protected $fillable = ['message_id', 'sender_id', 'receiver_id', 'replyMessage'];

    protected $casts = [
        'replyMessage' => 'encrypted',
    ];

    public function message()
    {
        return $this->belongsTo(Message::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
