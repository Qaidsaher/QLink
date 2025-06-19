<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content'
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
        if (\Illuminate\Support\Facades\Auth::check()) {
            return $this->likes()->where('user_id', \Illuminate\Support\Facades\Auth::id())->exists();
        }
        return false;
    }

    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->count();
    }

    protected $appends = ['likes_count', 'is_liked'];
    public function getHashtags(): array
    {
        if (empty($this->content)) {
            return [];
        }

        // Regex to find hashtags: # followed by word characters (alphanumeric + underscore)
        preg_match_all('/#([\p{L}\p{N}_]+)/u', $this->content, $matches);

        // $matches[1] will contain the hashtag text without the #
        // Convert to lowercase for consistent counting
        return array_map('strtolower', $matches[1] ?? []);
    }
}
