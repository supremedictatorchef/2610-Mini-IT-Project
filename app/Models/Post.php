<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PostLike;
use App\Models\PostComment;



class Post extends Model
{
    use HasFactory;

   protected $fillable = [
    'club_id', 'user_id', 'title', 'content', 'image',
     'comments_count'
];

    public function club()
    {
        return $this->belongsTo(Club::class);
    }

    public function user()
    {
    
    return $this->belongsTo(User::class);
    }

    protected $appends = ['likedByUser'];

public function getLikedByUserAttribute()
{
    return $this->likes()->where('user_id', auth()->id())->exists();
}

    public function likes()
{
    return $this->hasMany(PostLike::class);
}

public function comments()
{
    return $this->hasMany(PostComment::class);
}

}