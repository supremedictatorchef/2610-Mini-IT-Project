<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;
use App\Models\PostComment;
use App\Models\PostMedia;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'club_id',
        'user_id',
        'title',
        'content',
        'image',        
        'likes_count',
        'comments_count',
        'liked_users',
        'comments',         
    ];

    protected $casts = [
        'liked_users' => 'array',
        'comments'    => 'array',
    ];

    protected $appends = ['likedByUser'];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLikedByUserAttribute()
    {
        return auth()->check()
            ? $this->likes()->where('user_id', auth()->id())->exists()
            : false;
    }

    public function likes()
    {
        return $this->hasMany(PostLike::class);
    }

    public function comments()
    {
        return $this->hasMany(PostComment::class);
    }

    public function media()
    {
        return $this->hasMany(PostMedia::class);
    }
}
