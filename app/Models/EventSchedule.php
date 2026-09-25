<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'booking_id', 'activity_type', 'title', 'description',
    'scheduled_date', 'start_time', 'end_time', 'status', 'notes', 'sort_order',
])]
class EventSchedule extends Model
{
    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
        ];
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tasks()
    {
        return $this->hasMany(EventTask::class);
    }
}
