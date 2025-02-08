<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'subject',
        'message',
    ];

    protected $casts = [
        'subject' => 'encrypted',
        'message' => 'encrypted',
    ];

    // Relationship to sender
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Relationship to receiver
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Check if the message is read
    public function isRead()
    {
        return $this->is_read;
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
}
