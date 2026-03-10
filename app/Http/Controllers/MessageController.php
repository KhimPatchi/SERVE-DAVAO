<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Send a new message
     */
    public function store(Request $request, Conversation $conversation)
    {
        $user = auth()->user();
        
        \Log::info('DEBUG: Message submission started', [
            'user_id' => $user->id,
            'convo_id' => $conversation->id,
            'message_length' => strlen($request->input('message', ''))
        ]);

        // Authorization check
        if (!$conversation->isParticipant($user)) {
            \Log::error('DEBUG: Message submission UNAUTHORIZED', [
                'user_id' => $user->id,
                'convo_id' => $conversation->id
            ]);
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Validation
        try {
            $request->validate([
                'message' => 'nullable|string|max:2000',
                'attachment' => 'nullable|file|max:10240', // Max 10MB
            ]);
            
            if (!$request->input('message') && !$request->hasFile('attachment')) {
                return response()->json(['error' => 'Message or attachment is required'], 422);
            }
        } catch (\Illuminate\Validation\ValidationException $ve) {
            \Log::error('DEBUG: Message validation FAILED', ['errors' => $ve->errors()]);
            return response()->json(['error' => 'Validation failed', 'details' => $ve->errors()], 422);
        }

        // Handle Attachment
        $attachmentPath = null;
        $attachmentType = null;

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $path = $file->store('attachments', 'public');
            $attachmentPath = $path;
            $attachmentType = $file->getMimeType();
        }

        // Create message
        try {
            $message = $conversation->messages()->create([
                'user_id' => $user->id,
                'message' => $request->input('message'),
                'attachment' => $attachmentPath,
                'attachment_type' => $attachmentType,
            ]);
            
            \Log::info('DEBUG: Message created successfully', ['message_id' => $message->id]);

            // Update conversation timestamp
            $conversation->touch();

            // Broadcast message to other participants
            try {
                broadcast(new MessageSent($message))->toOthers();
            } catch (\Exception $be) {
                \Log::warning('DEBUG: Broadcasting failed: ' . $be->getMessage());
            }
        } catch (\Exception $e) {
            \Log::error('DEBUG: Message creation FAILED', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save message'], 500);
        }

        // Return message data
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => [
                    'id' => $message->id,
                    'message' => $message->message,
                    'sender' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'avatar' => $user->avatar ?? $user->google_avatar,
                        'avatar_url' => $user->avatar_url, // Use accessor
                    ],
                    'attachment_url' => $message->attachment_url,
                    'attachment_type' => $message->attachment_type,
                    'created_at' => $message->created_at->toISOString(),
                    'formatted_time' => $message->formatted_time,
                ],
            ]);
        }

        return redirect()->route('messages.show', $conversation->id);
    }

    /**
     * Delete a message (soft delete)
     */
    public function destroy(Message $message)
    {
        $user = auth()->user();

        // Only message sender can delete
        if (!$message->belongsToUser($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message->delete();

        return response()->json(['success' => true]);
    }
}
