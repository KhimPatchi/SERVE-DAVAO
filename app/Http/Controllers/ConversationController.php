<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\User;
use App\Models\Event;
use App\Services\ConversationService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ConversationController extends Controller
{
    use AuthorizesRequests;

    protected $conversationService;

    public function __construct(ConversationService $conversationService)
    {
        $this->middleware('auth');
        $this->conversationService = $conversationService;
    }

    /**
     * Display list of user's conversations
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $conversationsData = $this->conversationService->getUserConversationsWithStats($user);
        $unreadCount = $this->conversationService->getTotalUnreadCount($user);

        return view('messages.index', [
            'conversationsData' => $conversationsData,
            'unreadCount' => $unreadCount,
            'activeConversation' => null,
            'messages' => collect([]),
        ]);
    }

    /**
     * Display single conversation with messages
     */
    public function show(Conversation $conversation)
    {
        $user = auth()->user();
        
        \Log::info('DEBUG: Entering show method', [
            'user_id' => $user->id,
            'passed_convo_id' => $conversation->id,
            'is_participant' => $conversation->isParticipant($user)
        ]);

        // Authorization check
        if (!$conversation->isParticipant($user)) {
            \Log::error('DEBUG: Authorization failure for user ' . $user->id . ' on convo ' . $conversation->id);
            abort(403, 'You are not authorized to view this conversation.');
        }

        $conversationsData = $this->conversationService->getUserConversationsWithStats($user);
        $messages = $this->conversationService->getConversationMessages($conversation);
        $unreadCount = $this->conversationService->getTotalUnreadCount($user);

        // Mark as read
        $this->conversationService->markConversationAsRead($conversation, $user);

        \Log::info('DEBUG: Rendering messages.index view', [
            'active_id' => $conversation->id,
            'convo_data_count' => count($conversationsData),
            'messages_count' => count($messages)
        ]);

        return view('messages.index', [
            'conversationsData' => $conversationsData,
            'activeConversation' => $conversation,
            'messages' => $messages,
            'unreadCount' => $unreadCount,
            'debug_info' => 'Rendering convo ' . $conversation->id . ' for user ' . $user->id
        ]);
    }

    public function startDirect(User $user)
    {
        $recipient = $user;
        $currentUser = auth()->user();

        \Log::info('Starting direct conversation', [
            'current_user_id' => $currentUser->id,
            'recipient_id' => $recipient->id
        ]);

        // Don't allow messaging yourself
        if ($currentUser->id === $recipient->id) {
            \Log::warning('User tried to message themselves', ['user_id' => $currentUser->id]);
            return redirect()->route('messages.index')
                ->with('error', 'You cannot message yourself.');
        }

        $conversation = $this->conversationService->findOrCreateDirectConversation($currentUser, $recipient);
        
        \Log::info('Conversation found or created', ['conversation_id' => $conversation->id]);

        // Ensure both are participants
        $conversation->participants()->syncWithoutDetaching([
            $currentUser->id => ['last_read_at' => now()],
            $recipient->id => ['last_read_at' => now()],
        ]);

        \Log::info('Redirecting to conversation show', ['conversation_id' => $conversation->id]);
        return redirect()->route('messages.show', ['conversation' => $conversation->id]);
    }

    /**
     * Start or get event group conversation
     */
    public function startEventGroup(Event $event)
    {
        $user = auth()->user();

        // Check if user is organizer or registered volunteer
        if (!$event->isOrganizer($user->id) && !$event->isRegistered($user->id)) {
            abort(403, 'You must be a participant of this event to access the group chat.');
        }

        // Use the createEventConversation method from the Conversation model
        $conversation = Conversation::createEventConversation($event);

        // Ensure user is added to conversation
        if (!$conversation->isParticipant($user)) {
            $conversation->addParticipant($user);
            
            // Send system message when joining for the first time
            $this->conversationService->createSystemMessage(
                $conversation, 
                "{$user->name} has joined the event group chat"
            );
        }

        return redirect()->route('messages.show', ['conversation' => $conversation->id]);
    }

    /**
     * Mark conversation as read (AJAX endpoint)
     */
    public function markAsRead(Conversation $conversation)
    {
        $user = auth()->user();

        if (!$conversation->isParticipant($user)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $this->conversationService->markConversationAsRead($conversation, $user);

        return response()->json(['success' => true]);
    }

    /**
     * Search messages (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100',
        ]);

        $user = auth()->user();
        $results = $this->conversationService->searchMessages($user, $request->input('q'));

        return response()->json($results);
    }
}
