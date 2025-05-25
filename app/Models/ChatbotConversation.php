<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotConversation extends Model
{
    use HasFactory;

    protected $table = 'chatbot_conversations';

    protected $fillable = [
        'user_id',
        'session_id',
        'messages'
    ];

    protected $casts = [
        'messages' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }
}