<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    protected $table = 'ai_chat_messages';

    protected $fillable = [
        'session_id',
        'sender',
        'content',
        'metadata',
        'timestamp',
    ];

    protected $casts = [
        'metadata'  => 'array',
        'timestamp' => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id', 'id');
    }
}
