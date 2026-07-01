<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Club;
use App\Models\PostMedia;
use App\Notifications\ClubNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PostComment;
use App\Events\CommentPosted;
use App\Models\Event;

class PostController extends Controller
{
    public function index(Club $clubs)
    {
        $user = Auth::user();
        $clubIds = $user ? $user->followed_clubs ?? [] : [];

        $followedPosts = Post::with(['club', 'media', 'comments.user'])
            ->whereIn('club_id', $clubIds)
            ->latest()
            ->get();

        $followedClubs = Club::whereIn('id', $clubIds)
            ->with(['posts', 'events'])
            ->get();
    
        $otherPosts = Post::with(['club', 'media', 'comments.user'])
            ->whereNotIn('club_id', $clubIds)
            ->latest()
            ->get();

        $posts = Post::with(['club', 'media', 'comments.user'])
            ->latest()
            ->get();

        $events = $followedClubs->pluck('events')->flatten();
         
         $otherEvents = Event::all()
         ->whereNotIn('club_id', $clubIds);

        $allEvents = Event::all();

        return view('welcome', compact('clubIds', 'followedPosts', 'otherPosts', 'posts', 'events','otherEvents', 'allEvents'));
    }

    public function create(Club $club)
    {
        return view('posts.create', compact('club'));
    }

    
    public function store(Request $request, Club $club)
    {
        
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            //  Only allow images
            'media.*' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:51200',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['club_id'] = $club->id;

        // Create the post
        $post = Post::create($validated);

        // Handle multiple image uploads
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $post->media()->create([
                    'type' => 'image', // always image now
                    'path' => $path,
                ]);
            }
        }

        // Notify members
        foreach ($club->users as $member) {
            $member->notify(new ClubNotification(
                $club,
                "New Post in {$club->name}: {$post->title}",
                'post'
            ));
        }

        return redirect()->route('clubs.show', $club)
                         ->with('success', 'Post created with images!');
    }

    public function show(Post $post)
    {
        $post->load(['club', 'media', 'comments.user']); 
        return view('posts.show', compact('post'));
    }

    public function edit(Club $club, Post $post)
    {
        return view('posts.edit', compact('club', 'post'));
    }

    public function update(Request $request, Club $club, Post $post)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'media.*' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
        ]);

        $post->update($validated);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $post->media()->create([
                    'type' => 'image', 
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('clubs.show', $club->id)
                         ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $club = $post->club;
        $post->delete();

        return redirect()->route('clubs.show', $club)
                         ->with('success', 'Post deleted successfully!');
    }

    // =========================================================================
    // Public/Interactive Interactions
    // =========================================================================
    public function like(Post $post)
    {
        $userId = auth()->id();
        
        // 1. Get the current array of user IDs (ensures it fallback to an empty array if null)
        $likedUsers = is_string($post->liked_users) 
            ? json_decode($post->liked_users, true) 
            : ($post->liked_users ?? []);

        // 2. Check if the authenticated user has already liked this post
        if (in_array($userId, $likedUsers)) {
            // Unlike: Remove the current user ID from the array
            $likedUsers = array_values(array_diff($likedUsers, [$userId]));
            $liked = false;
        } else {
            // Like: Add the current user ID to the array
            $likedUsers[] = $userId;
            $liked = true;
        }

        // 3. Keep your counter variable in sync with the array length
        $likesCount = count($likedUsers);

        // 4. Persist the updated data directly back to the post attributes
        $post->update([
            'likes_count' => $likesCount,
            'liked_users' => json_encode($likedUsers), // Encodes cleanly back to database string format
        ]);

        return response()->json([
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate(['body' => 'required|string']);

        $comment = $post->comments()->create([
            'user_id' => auth()->id(),
            'body' => $request->body,
        ]);

        broadcast(new CommentPosted($comment))->toOthers();

        return response()->json($comment->load('user'));
    }

    public function getComments(Post $post)
    {
        return response()->json(
            $post->comments()->with('user')->latest()->get()
        );
    }
}
