<?php

use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('retrieves top 5 ongoing events for the chatbot', function () {
    // Set time
    $now = Carbon::parse('2026-06-18 12:00:00');
    Carbon::setTestNow($now);

    // Create a user for organizer_id
    $organizer = User::factory()->create();

    // Helper to create event
    $createEvent = function ($title, $status, $date, $endTime, $volunteers) use ($organizer) {
        return Event::create([
            'title' => $title,
            'description' => 'Test event description',
            'location' => 'Test Location',
            'required_volunteers' => 100,
            'current_volunteers' => $volunteers,
            'organizer_id' => $organizer->id,
            'organizer_name' => $organizer->name,
            'status' => $status,
            'date' => $date,
            'end_time' => $endTime,
        ]);
    };

    // Create ongoing events with different participant counts
    $createEvent('Event 1', 'active', $now->copy()->subHours(1), $now->copy()->addHours(2), 10);
    $createEvent('Event 2', 'active', $now->copy()->subHours(2), $now->copy()->addHours(1), 30);
    $createEvent('Event 3', 'active', $now->copy()->subHours(3), $now->copy()->addHours(2), 5);
    $createEvent('Event 4', 'active', $now->copy()->subHours(1), $now->copy()->addHours(3), 45);
    $createEvent('Event 5', 'active', $now->copy()->subHours(1), $now->copy()->addHours(2), 15);
    $createEvent('Event 6 (should not be in top 5)', 'active', $now->copy()->subHours(1), $now->copy()->addHours(2), 2);

    // Non-ongoing event (ended)
    $createEvent('Ended Event', 'active', $now->copy()->subHours(10), $now->copy()->subHours(2), 100);

    // Non-ongoing event (future)
    $createEvent('Future Event', 'active', $now->copy()->addHours(2), $now->copy()->addHours(5), 100);

    // Inactive ongoing event
    $createEvent('Inactive Event', 'pending', $now->copy()->subHours(1), $now->copy()->addHours(2), 100);

    $response = $this->get('/api/chatbot/current-events');

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
    ]);

    $data = $response->json('events');
    expect($data)->toHaveCount(5);

    // Verify ordering by current_volunteers descending
    expect($data[0]['title'])->toBe('Event 4'); // 45
    expect($data[1]['title'])->toBe('Event 2'); // 30
    expect($data[2]['title'])->toBe('Event 5'); // 15
    expect($data[3]['title'])->toBe('Event 1'); // 10
    expect($data[4]['title'])->toBe('Event 3'); // 5

    Carbon::setTestNow(); // Reset time
});
