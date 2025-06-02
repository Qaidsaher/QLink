<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role', 'bio', 'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     // User can have many posts.
     public function posts()
     {
         return $this->hasMany(Post::class);
     }
     public function comments()
     {
         return $this->hasMany(Comment::class);
     }
     public function sentMessages()
     {
         return $this->hasMany(Message::class, 'sender_id');
     }

     public function receivedMessages()
     {
         return $this->hasMany(Message::class, 'receiver_id');
     }

     public function followers()
     {
         return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
     }

     public function following()
     {
         return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
     }

     public function likes()
    {
        return $this->hasMany(Like::class);
    }

    //    public function following()
    // {
    //     return $this->belongsToMany(User::class, 'follower_user', 'follower_id', 'user_id')
    //                 ->withTimestamps();
    // }

    // // Users that are following this user
    // public function followers()
    // {
    //     return $this->belongsToMany(User::class, 'follower_user', 'user_id', 'follower_id')
    //                 ->withTimestamps();
    // }

    // Accessor for followers count (add to $appends if always needed)
    public function getFollowersCountAttribute()
    {
        return $this->followers()->count();
    }

    // Accessor for following count (add to $appends if always needed)
    public function getFollowingCountAttribute()
    {
        return $this->following()->count();
    }

    // Accessor to check if the authenticated user is following this user
    // (add to $appends if always needed when fetching a user profile)
    public function getIsFollowedByAuthUserAttribute()
    {
        if (!Auth::check()) {
            return false;
        }
        // Check if the authenticated user's ID exists in this user's followers list
        return $this->followers()->where('follower_id', Auth::id())->exists();
    }
    protected $appends = ['followers_count', 'following_count', 'is_followed_by_auth_user', /* existing appends like avatar_url */];

}
