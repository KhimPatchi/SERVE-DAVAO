<?php

namespace App\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired after a volunteer is marked "attended" when an organizer ends an event.
 * Broadcasts a real-time prompt for the volunteer to submit feedback & suggestions.
 */
class VolunteerCompletedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public readonly Event $event,
        public readonly User  $volunteer,
    ) {}

    /**
     * Broadcast on the volunteer's private channel so only they receive it.
     */
    public function broadcastOn(): Channel
    {
        return new PrivateChannel("user.{$this->volunteer->id}");
    }

    /**
     * The event name broadcast to Laravel Echo.
     */
    public function broadcastAs(): string
    {
        return 'VolunteerCompletedEvent';
    }

    /**
     * Data sent to the frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'event_id'        => $this->event->id,
            'event_title'     => $this->event->title,
            'event_date'      => $this->event->date->format('M d, Y'),
            'feedback_url'    => route('feedback.create', $this->event),
            'suggestions_url' => route('suggestions.create'),
            'message'         => "Thank you for volunteering at \"{$this->event->title}\"! Please take a moment to share your experience.",
        ];
    }
}
