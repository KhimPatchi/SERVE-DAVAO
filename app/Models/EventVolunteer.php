<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventVolunteer extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'volunteer_id',
        'status',
        'hours_volunteered'
    ];

    protected $attributes = [
        'status' => 'registered',
        'hours_volunteered' => 0,
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function volunteer()
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }
}