<?php

namespace App\Events;

use App\Models\PostComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentPosted implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $comment;

    public function __construct(PostComment $comment)
    {
        // Load user relationship so we can broadcast username
        $this->comment = $comment->load('user');
    }

    public function broadcastOn()
    {
        // Each post gets its own channel
        return new Channel('post.' . $this->comment->post_id);
    }
}
