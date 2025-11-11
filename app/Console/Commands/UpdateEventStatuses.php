<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventStatuses extends Command
{
    protected $signature = 'events:update-status';
    protected $description = 'Automatically update event statuses based on dates';

    public function handle()
    {
        $now = Carbon::now();
        $updatedCount = 0;

        // Update events that should be marked as completed (date is in past)
        $eventsToComplete = Event::where('date', '<', $now)
            ->where('status', '!=', 'completed')
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->get();

        foreach ($eventsToComplete as $event) {
            $event->update(['status' => 'completed']);
            $updatedCount++;
            
            $this->info("Marked event '{$event->title}' as completed (event date: {$event->date})");
        }

        $this->info("Successfully updated {$updatedCount} event(s) to completed status.");
        
        return Command::SUCCESS;
    }
}