<?php

namespace App\Services;

use App\Models\Conversation;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ConversationService
{
    /**
     * Find or create direct conversation between two users
     */
    public function findOrCreateDirectConversation(User $user1, User $user2): Conversation
    {
        return Conversation::createDirectConversation($user1, $user2);
    }

    /**
     * Create event group conversation with all volunteers
     */
    public function createEventGroupConversation(Event $event): Conversation
    {
        return Conversation::createEventConversation($event);
    }

    /**
     * Add a new volunteer to existing event chat
     */
    public function addNewVolunteerToEventChat(Event $event, User $volunteer): void
    {
        $conversation = $event->conversation;

        if ($conversation) {
            $conversation->addParticipant($volunteer);
        } else {
            // Create conversation if it doesn't exist
            $this->createEventGroupConversation($event);
        }
    }

    /**
     * Get user's conversations with stats (optimized query)
     */
    public function getUserConversationsWithStats(User $user): \Illuminate\Support\Collection
    {
        return $user->conversations()
            ->with([
                'latestMessage.sender',
                'participants' => function ($query) use ($user) {
                    // Only load other participants for direct conversations
                    $query->where('user_id', '!=', $user->id);
                },
                'event' // For event group conversations
            ])
            ->get()
            ->map(function ($conversation) use ($user) {
                return [
                    'conversation' => $conversation,
                    'display_name' => $conversation->getDisplayName($user),
                    'avatar_url' => $conversation->getAvatarUrl($user),
                    'last_message' => $conversation->latestMessage,
                    'unread_count' => $conversation->getUnreadCount($user),
                    'updated_at' => $conversation->updated_at,
                ];
            })
            ->sortByDesc('updated_at')
            ->values();
    }

    /**
     * Search messages across all user's conversations
     */
    public function searchMessages(User $user, string $query, int $limit = 50): array
    {
        $conversationIds = $user->conversations->pluck('id');

        $messages = \App\Models\Message::whereIn('conversation_id', $conversationIds)
            ->where('message', 'LIKE', "%{$query}%")
            ->with(['conversation', 'sender'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();

        return $messages->map(function ($message) use ($user) {
            return [
                'message' => $message,
                'conversation' => $message->conversation,
                'display_name' => $message->conversation->getDisplayName($user),
                'excerpt' => $this->getMessageExcerpt($message->message, $query),
            ];
        })->toArray();
    }

    /**
     * Get message excerpt with highlighted search term
     */
    private function getMessageExcerpt(string $message, string $searchTerm, int $contextLength = 50): string
    {
        $position = stripos($message, $searchTerm);

        if ($position === false) {
            return substr($message, 0, $contextLength * 2);
        }

        $start = max(0, $position - $contextLength);
        $excerpt = substr($message, $start, $contextLength * 2);

        // Add ellipsis if truncated
        if ($start > 0) {
            $excerpt = '...' . $excerpt;
        }
        if (strlen($message) > $start + $contextLength * 2) {
            $excerpt .= '...';
        }

        return $excerpt;
    }

    /**
     * Mark conversation as read for user
     */
    public function markConversationAsRead(Conversation $conversation, User $user): void
    {
        $conversation->markAsReadBy($user);
    }

    /**
     * Get total unread messages count for user
     */
    public function getTotalUnreadCount(User $user): int
    {
        return $user->getTotalUnreadMessagesCount();
    }

    /**
     * Create a system message (e.g., "User joined the group")
     */
    public function createSystemMessage(Conversation $conversation, string $message): void
    {
        $msg = $conversation->messages()->create([
            'user_id'           => null, // null = system message, no sender
            'message'           => $message,
            'is_system_message' => true,
        ]);

        // Load relationship so the broadcast can access participants
        $msg->load('conversation.participants');

        // Broadcast to all participants so they see the system message in real-time
        broadcast(new \App\Events\MessageSent($msg));
    }

    /**
     * Get paginated messages for a conversation
     */
    public function getConversationMessages(Conversation $conversation, int $perPage = 50): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $conversation->messages()
            ->with('sender')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
