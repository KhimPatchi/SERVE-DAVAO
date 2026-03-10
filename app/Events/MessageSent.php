<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];

        // Broadcast to each participant's private user channel for global notifications
        // Check if relation is loaded to avoid N+1 if possible, or just access it
        foreach ($this->message->conversation->participants->unique('id') as $participant) {
            // For system messages (user_id is null), notify ALL participants
            if ($this->message->user_id === null || (int)$participant->id !== (int)$this->message->user_id) {
                $channels[] = new PrivateChannel('App.Models.User.' . $participant->id);
            }
        }

        return $channels;
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $sender = $this->message->sender;

        return [
            'id' => $this->message->id,
            'conversation_id' => $this->message->conversation_id,
            'message' => $this->message->message,
            'is_system_message' => $this->message->is_system_message,
            'user_id' => $this->message->user_id,
            'sender' => $sender ? [
                'id' => $sender->id,
                'name' => $sender->name,
                'avatar' => $sender->avatar ?? $sender->google_avatar,
                'avatar_url' => $sender->avatar_url,
            ] : null,
            'attachment_url' => $this->message->attachment_url,
            'attachment_type' => $this->message->attachment_type,
            'created_at' => $this->message->created_at->toISOString(),
            'formatted_time' => $this->message->formatted_time,
        ];
    }
}
