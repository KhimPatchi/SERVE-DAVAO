<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollOption extends Model
{
    use HasFactory;

    protected $fillable = ['poll_id', 'label', 'votes'];

    protected $casts = ['votes' => 'integer'];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function pollVotes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function percentage(int $total): float
    {
        if ($total === 0) return 0;
        return round(($this->votes / $total) * 100, 1);
    }
}
