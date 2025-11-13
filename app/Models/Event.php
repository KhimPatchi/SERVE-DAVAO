<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'date', 'location',
        'required_volunteers', 'current_volunteers',
        'organizer_id', 'organizer_name', 'status', 'skills_required'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'pending',
    ];

    // RELATIONSHIPS
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function volunteers()
    {
        return $this->hasMany(EventVolunteer::class);
    }

    public function audits()
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    // REGISTRATION METHODS
    public function isRegistered($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }
        
        if (!$userId) {
            return false;
        }

        return $this->volunteers()
                    ->where('volunteer_id', $userId)
                    ->where('status', 'registered')
                    ->exists();
    }

    public function isOrganizer($userId = null)
    {
        if (!$userId && auth()->check()) {
            $userId = auth()->id();
        }
        
        if (!$userId) {
            return false;
        }

        return $this->organizer_id == $userId;
    }

    // =========================================================================
    // FIXED TIME VALIDATION METHODS
    // =========================================================================

    /**
     * Check if event has started (timezone-aware)
     */
    public function hasStarted()
    {
        $now = Carbon::now(config('app.timezone'));
        $eventTime = $this->date->copy()->setTimezone(config('app.timezone'));
        return $now->gte($eventTime);
    }

    /**
     * Check if event has ended (timezone-aware)
     * Assuming 8-hour event duration by default
     */
    public function hasEnded($eventDurationHours = 8)
    {
        $now = Carbon::now(config('app.timezone'));
        $eventTime = $this->date->copy()->setTimezone(config('app.timezone'));
        $eventEndTime = $eventTime->copy()->addHours($eventDurationHours);
        return $now->gte($eventEndTime);
    }

    /**
     * Check if event is currently ongoing
     */
    public function isOngoing($eventDurationHours = 8)
    {
        return $this->hasStarted() && !$this->hasEnded($eventDurationHours);
    }

    /**
     * Check if event can be joined (comprehensive check)
     */
    public function canBeJoined($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return false;
        }
        
        // DEBUG: Log the validation steps
        \Log::info("canBeJoined Check for Event {$this->id}:", [
            'isActive' => $this->isActive(),
            'hasStarted' => $this->hasStarted(),
            'hasEnded' => $this->hasEnded(),
            'isFull' => $this->isFull(),
            'isRegistered' => $this->isRegistered($userId),
            'isOrganizer' => $this->isOrganizer($userId),
            'current_time' => now()->toDateTimeString(),
            'event_time' => $this->date->toDateTimeString(),
        ]);
        
        return $this->isActive() 
            && !$this->hasStarted()  // ← THIS IS THE KEY FIX
            && !$this->hasEnded()
            && !$this->isFull()
            && !$this->isRegistered($userId)
            && !$this->isOrganizer($userId);
    }

    /**
     * Check if user can leave the event
     */
    public function canBeLeft($userId = null)
    {
        $userId = $userId ?? auth()->id();
        
        if (!$userId) {
            return false;
        }
        
        return $this->isRegistered($userId)
            && !$this->hasStarted()
            && !$this->hasEnded();
    }

    // =========================================================================
    // FIXED STATUS METHODS
    // =========================================================================

    /**
     * FIXED: Timezone-aware status calculation
     */
    public function getCurrentStatusAttribute()
    {
        $now = Carbon::now(config('app.timezone'));
        $eventTime = $this->date->copy()->setTimezone(config('app.timezone'));
        
        // If manually set to completed, cancelled, or rejected, respect that
        if (in_array($this->status, ['completed', 'cancelled', 'rejected'])) {
            return $this->status;
        }
        
        // If event has ended, mark as completed
        if ($this->hasEnded()) {
            return 'completed';
        }
        
        // If event has started but not ended, it's ongoing
        if ($this->hasStarted()) {
            return 'active'; // Keep as active but should show as ongoing in UI
        }
        
        // Otherwise return the original status
        return $this->status;
    }

    /**
     * FIXED: Direct timezone-aware active check
     */
    public function isActive(): bool 
    { 
        $now = Carbon::now(config('app.timezone'));
        $eventTime = $this->date->copy()->setTimezone(config('app.timezone'));
        
        return $this->status === 'active' && $now->lt($eventTime);
    }

    public function shouldBeCompleted()
    {
        return $this->hasEnded();
    }

    public function updateStatusBasedOnDates()
    {
        if ($this->shouldBeCompleted() && $this->status !== 'completed') {
            $this->update(['status' => 'completed']);
            return true;
        }
        
        return false;
    }

    public function isFull(): bool { 
        return $this->current_volunteers >= $this->required_volunteers; 
    }
    
    public function isPending(): bool { 
        return $this->status === 'pending'; 
    }
    
    public function isCompleted(): bool {
        return $this->current_status === 'completed';
    }

    // =========================================================================
    // SCOPES
    // =========================================================================

    public function scopeActive($query) { 
        return $query->where('status', 'active')
                    ->where('date', '>', now());
    }
    
    public function scopePending($query) { 
        return $query->where('status', 'pending'); 
    }
    
    public function scopeCompleted($query) {
        return $query->where(function($q) {
            $q->where('status', 'completed')
              ->orWhere('date', '<', now()->subHours(8)); // Events that ended 8+ hours ago
        });
    }
    
    public function scopeUpcoming($query) {
        return $query->where('status', 'active')
                    ->where('date', '>', now());
    }

    public function scopeAllActive($query) {
        return $query->where('status', 'active');
    }

    public function scopeJoinable($query, $userId = null)
    {
        $userId = $userId ?? auth()->id();
        $now = Carbon::now(config('app.timezone'));
        
        return $query->where('status', 'active')
                    ->where('date', '>', $now) // Only future events
                    ->where('current_volunteers', '<', 'required_volunteers')
                    ->whereDoesntHave('volunteers', function($q) use ($userId) {
                        $q->where('volunteer_id', $userId)
                          ->where('status', 'registered');
                    })
                    ->where('organizer_id', '!=', $userId);
    }
}