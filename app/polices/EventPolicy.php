<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine if the user can manage the event.
     */
    public function manageEvent(User $user, Event $event): bool
    {
        // Only the event organizer can manage their event
        return $user->id === $event->organizer_id;
    }

    /**
     * Determine if the user can access organizer features.
     */
    public function organizerAccess(User $user): bool
    {
        // Only verified organizers have organizer access
        return $user->isVerifiedOrganizer();
    }
}