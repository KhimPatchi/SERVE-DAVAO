<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'date', 'location', 
        'required_volunteers', 'current_volunteers', 
        'organizer_id', 'status', 'skills_required'
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
    // CLEAN STATUS METHODS
    public function isFull(): bool { return $this->current_volunteers >= $this->required_volunteers; }
    public function isActive(): bool { return $this->status === 'active'; }
    public function isPending(): bool { return $this->status === 'pending'; }

    // CLEAN SCOPES
    public function scopeActive($query) { 
        return $query->where('status', 'active')->where('date', '>=', now()); 
    }
    public function scopePending($query) { return $query->where('status', 'pending'); }
}