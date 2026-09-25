<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'event_schedule_id', 'title', 'description', 'assigned_to',
    'vendor', 'due_time', 'status', 'notes',
])]
class EventTask extends Model
{
    public function schedule()
    {
        return $this->belongsTo(EventSchedule::class, 'event_schedule_id');
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
