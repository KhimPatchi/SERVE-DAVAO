<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\EventVolunteer;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Scans for past events and auto-marks remaining 'registered' volunteers as 'no-show'.
 * Scheduled to run hourly via app/Console/Kernel.php.
 *
 * Usage: php artisan events:close-attendance
 */
class CloseAttendance extends Command
{
    protected $signature   = 'events:close-attendance';
    protected $description = 'Mark no-show volunteers and complete past events.';

    public function handle(): int
    {
        $this->info('Scanning for events to close...');

        // Find active events whose end time (start + 8h default) has passed
        $pastEvents = Event::where('status', 'active')
            ->where('date', '<=', Carbon::now()->subHours(8))
            ->get();

        if ($pastEvents->isEmpty()) {
            $this->info('No events to close.');
            return self::SUCCESS;
        }

        $totalNoShows = 0;
        $totalClosed  = 0;

        foreach ($pastEvents as $event) {
            // Mark still-registered volunteers as no-show
            $noShowCount = EventVolunteer::where('event_id', $event->id)
                ->registered()
                ->update(['status' => 'no-show']);

            // Mark event as completed
            $event->update(['status' => 'completed']);

            $totalNoShows += $noShowCount;
            $totalClosed++;

            Log::info("[CloseAttendance] Event {$event->id} «{$event->title}» closed. No-shows: {$noShowCount}");
            $this->line("  ✓ Event #{$event->id} «{$event->title}» → completed  ({$noShowCount} no-shows)");
        }

        $this->info("Done. {$totalClosed} event(s) closed, {$totalNoShows} no-show(s) recorded.");

        return self::SUCCESS;
    }
}
