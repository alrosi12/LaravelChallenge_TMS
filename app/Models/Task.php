<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'title',
        'description',
        'status',
        'due_data',
        'user_id',
        'assignee_id'
    ];
    protected $casts = [
        'due_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePending($quere)
    {
        return $quere->where('status', 'pending');
    }
    public function scopeOverdue($quere)
    {
        return $quere->where('due_date', '<', now());
    }

    public function scopeAssignedTo($quere, $userId)
    {
        return $quere->where('assignee_id', $userId);
    }
}
