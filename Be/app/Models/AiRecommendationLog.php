<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiRecommendationLog extends Model
{
    protected $table = 'ai_recommendation_logs';

    protected $fillable = [
        'session_id',
        'product_id',
        'is_purchased',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_purchased' => 'boolean',
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(SanPham::class, 'product_id', 'id');
    }
}
