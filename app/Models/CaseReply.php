<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseReply extends Model
{
    protected $table = 'case_replies';

    protected $fillable = [
        'case_id',
        'sender_id',
        'receiver_id',
        'replyMessage',
    ];

    protected $casts = [
        'replyMessage' => 'encrypted',
    ];

    public function case()
    {
        return $this->belongsTo(Cases::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    
}
