<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use HasFactory;
    
    protected $table = 'feedback';
    
    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'rating',
        'category',
        'department',
        'doctor_name',
        'status', // 'pending', 'approved', 'rejected'
        'is_anonymous',
        'is_highlighted',
        'admin_notes',
        'admin_reviewed_at',
    ];
    
    protected $casts = [
        'admin_reviewed_at' => 'datetime',
        'is_highlighted' => 'boolean',
        'is_anonymous' => 'boolean',
    ];
    
    /**
     * Get the user that owns the feedback.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'UserID');
    }
    
    /**
     * Scope to get approved feedback.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }
    
    /**
     * Scope to get pending feedback.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
    
    /**
     * Scope to get highlighted feedback.
     */
    public function scopeHighlighted($query)
    {
        return $query->where('is_highlighted', true);
    }
    
    /**
     * Scope to get rejected feedback.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
    
    /**
     * Check if feedback is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    /**
     * Check if feedback is approved.
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    /**
     * Check if feedback is rejected.
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
    
    /**
     * Get rating as stars.
     */
    public function getRatingStarsAttribute()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            $stars .= ($i <= $this->rating) ? '★' : '☆';
        }
        return $stars;
    }
}
