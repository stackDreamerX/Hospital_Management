<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotFAQ extends Model
{
    use HasFactory;

    protected $table = 'chatbot_faqs';

    protected $fillable = [
        'question',
        'answer',
        'category',
        'keywords'
    ];
}