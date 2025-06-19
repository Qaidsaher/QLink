<?php

namespace App\Http\Controllers\Api;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MessageController extends ApiController
{
    // public function __construct()
    // {
    //     $this->middleware('auth:sanctum');
    // }

    /**
     * Get a list of conversations (latest message with each user).
     */
    public function getConversations(Request $request)
    {
        $userId = Auth::id();

        // Subquery to get the latest message_id for each conversation pair
        $latestMessageIds = DB::table('messages as m1')
            ->select(DB::raw('MAX(m1.id) as last_message_id'))
            ->where('m1.sender_id', $userId)
            ->orWhere('m1.receiver_id', $userId)
            ->groupBy(DB::raw('CASE WHEN m1.sender_id = '.$userId.' THEN m1.receiver_id ELSE m1.sender_id END'));


        $conversations = Message::whereIn('id', $latestMessageIds)
            ->with(['sender', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Transform to show the "other user" in conversation
        $conversations->getCollection()->transform(function ($message) use ($userId) {
            $message->other_user = $message->sender_id == $userId ? $message->receiver : $message->sender;
            $message->unread_count = Message::where('sender_id', $message->other_user->id)
                                            ->where('receiver_id', $userId)
                                            ->where('is_read', false)
                                            ->count();
            unset($message->sender); // Clean up if you only want other_user
            unset($message->receiver);
            return $message;
        });


        return $this->sendResponse($conversations, 'Conversations retrieved successfully.');
    }


    /**
     * Get messages between the authenticated user and another user.
     */
    public function getMessagesWithUser(Request $request, User $user)
    {
        $authUserId = Auth::id();
        $otherUserId = $user->id;

        if ($authUserId == $otherUserId) {
            return $this->sendError('Cannot fetch messages with yourself.', [], 400);
        }

        // Mark messages from $otherUserId to $authUserId as read
        Message::where('sender_id', $otherUserId)
               ->where('receiver_id', $authUserId)
               ->where('is_read', false)
               ->update(['is_read' => true]);

        $messages = Message::where(function ($query) use ($authUserId, $otherUserId) {
            $query->where('sender_id', $authUserId)->where('receiver_id', $otherUserId);
        })->orWhere(function ($query) use ($authUserId, $otherUserId) {
            $query->where('sender_id', $otherUserId)->where('receiver_id', $authUserId);
        })->with(['sender', 'receiver'])
          ->orderBy('created_at', 'asc') // Typically chat messages are asc
          ->paginate(20); // Or use cursorPaginate for infinite scrolling

        return $this->sendResponse($messages, 'Messages retrieved successfully.');
    }

    /**
     * Send a message to another user.
     */
    public function sendMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors()->toArray());
        }

        $senderId = Auth::id();
        $receiverId = $request->receiver_id;

        if ($senderId == $receiverId) {
            return $this->sendError('You cannot send a message to yourself.', [], 400);
        }

        // Check if receiver exists (already handled by 'exists:users,id' but good for clarity)
        $receiver = User::find($receiverId);
        if (!$receiver) {
            return $this->sendNotFound('Receiver not found.');
        }

        $message = Message::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $request->message,
            'is_read' => false,
        ]);
        $message->load(['sender', 'receiver']);

        // Here you might want to dispatch an event for real-time notification (e.g., using Laravel Echo)
        // event(new NewMessageSent($message));

        return $this->sendResponse($message, 'Message sent successfully.');
    }

    /**
     * Mark a specific message as read.
     * This might be more granular than needed if getMessagesWithUser marks all as read.
     */
    public function markAsRead(Request $request, Message $message)
    {
        // Ensure the authenticated user is the receiver of the message
        if ($message->receiver_id !== Auth::id()) {
            return $this->sendForbidden('You are not authorized to mark this message as read.');
        }

        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }
        $message->load(['sender', 'receiver']);
        return $this->sendResponse($message, 'Message marked as read.');
    }

    /**
     * Delete a message (for the authenticated user only - "delete for me" functionality).
     * True deletion or soft deletion depends on your requirements.
     * For "delete for everyone", you'd need more complex logic.
     */
    public function destroy(Request $request, Message $message)
    {
        // Allow deletion if the user is the sender or receiver.
        // This example implements a "delete for me" kind of functionality.
        // True deletion of the record:
        if ($message->sender_id === Auth::id() || $message->receiver_id === Auth::id()) {
            // For "delete for me", you might add columns like `deleted_by_sender` and `deleted_by_receiver`
            // and only truly delete if both have marked it.
            // For simplicity, this example just deletes it.
            $message->delete();
            return $this->sendResponse([], 'Message deleted successfully.');
        }

        return $this->sendForbidden('You are not authorized to delete this message.');
    }
}
