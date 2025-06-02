<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id', 'content'
    ];

      protected $with = ['user', 'attachments', 'comments', 'likes']; // Eager load common relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'desc');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Accessor to get like count
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Accessor to check if authenticated user liked the post
    public function getIsLikedAttribute()
    {
        if (auth()->check()) {
            return $this->likes()->where('user_id', auth()->id())->exists();
        }
        return false;
    }

    protected $appends = ['likes_count', 'is_liked'];
}
