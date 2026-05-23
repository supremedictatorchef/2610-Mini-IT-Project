<?php

namespace App\Http\Controllers;

use App\Models\Club;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request, Club $club)
    {
        // Save the message in DB
        $message = $club->messages()->create([
            'user_id' => auth()->id(),
            'body'    => $request->body,
        ]);

        // Broadcast the event to others
        broadcast(new MessageSent($message->load('user')))->toOthers();

        // Return JSON response
        return response()->json($message);
    }

    public function destroy(Message $message)
{
    if ($message->user_id == auth()->id()) {
        $message->delete();
        return response()->noContent();
    }
    return response()->json(['error' => 'Unauthorized'], 403);
}


}
