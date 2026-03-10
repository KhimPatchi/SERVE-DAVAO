<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventFeedback extends Model
{
    use HasFactory;

    protected $table = 'event_feedback';

    protected $fillable = [
        'event_id',
        'volunteer_id',
        'overall_rating',
        'organization_rating',
        'impact_rating',
        'comment',
        'would_recommend'
    ];

    protected $casts = [
        'would_recommend' => 'boolean',
        'overall_rating' => 'integer',
        'organization_rating' => 'integer',
        'impact_rating' => 'integer',
    ];

    // RELATIONSHIPS
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    // SCOPES
    public function scopeForEvent($query, $eventId)
    {
        return $query->where('event_id', $eventId);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('overall_rating', '>=', $minRating);
    }

    // HELPER METHODS
    public static function getAverageRatings($eventId)
    {
        return self::where('event_id', $eventId)
            ->selectRaw('
                AVG(overall_rating) as avg_overall,
                AVG(organization_rating) as avg_organization,
                AVG(impact_rating) as avg_impact,
                COUNT(*) as total_feedback,
                SUM(CASE WHEN would_recommend = 1 THEN 1 ELSE 0 END) as recommend_count
            ')
            ->first();
    }
}
