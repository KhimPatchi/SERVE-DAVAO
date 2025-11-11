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
    'organizer_id', 'organizer_name', 'status', 'skills_required' // ← ADD organizer_name HERE
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

    // ADD THIS METHOD TO FIX THE ERROR
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

    // NEW: Automatic status calculation based on date
    public function getCurrentStatusAttribute()
    {
        $now = Carbon::now();
        
        // If manually set to completed, cancelled, or rejected, respect that
        if (in_array($this->status, ['completed', 'cancelled', 'rejected'])) {
            return $this->status;
        }
        
        // If event date is in the past, mark as completed
        if ($now->gt($this->date)) {
            return 'completed';
        }
        
        return $this->status;
    }

    // NEW: Check if event should be automatically completed
    public function shouldBeCompleted()
    {
        return Carbon::now()->gt($this->date);
    }

    // NEW: Update status based on dates
    public function updateStatusBasedOnDates()
    {
        if ($this->shouldBeCompleted() && $this->status !== 'completed') {
            $this->update(['status' => 'completed']);
            return true;
        }
        
        return false;
    }

    // CLEAN STATUS METHODS (Updated)
    public function isFull(): bool { 
        return $this->current_volunteers >= $this->required_volunteers; 
    }
    
    public function isActive(): bool { 
        return $this->current_status === 'active'; 
    }
    
    public function isPending(): bool { 
        return $this->current_status === 'pending'; 
    }
    
    public function isCompleted(): bool {
        return $this->current_status === 'completed';
    }

    // CLEAN SCOPES (Updated)
    public function scopeActive($query) { 
        return $query->where('status', 'active')
                    ->where('date', '>', now());
    }
    
    public function scopePending($query) { 
        return $query->where('status', 'pending'); 
    }
    
    // NEW: Scope for completed events
    public function scopeCompleted($query) {
        return $query->where(function($q) {
            $q->where('status', 'completed')
              ->orWhere('date', '<', now());
        });
    }
    
    // NEW: Scope for upcoming events
    public function scopeUpcoming($query) {
        return $query->where('status', 'active')
                    ->where('date', '>', now());
    }

    // NEW: Scope for all active events (including those that should be completed)
    public function scopeAllActive($query) {
        return $query->where('status', 'active');
    }
}