<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatSession extends Model
{
    protected $table = 'ai_chat_sessions';

    protected $fillable = [
        'table_id',
        'start_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
    ];

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'session_id', 'id')->orderBy('timestamp', 'asc');
    }

    public function recommendationLogs()
    {
        return $this->hasMany(AiRecommendationLog::class, 'session_id', 'id');
    }

    public function ban()
    {
        return $this->belongsTo(Ban::class, 'table_id', 'id');
    }
}
