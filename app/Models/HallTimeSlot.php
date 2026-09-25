<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'event_hall_id', 'start_time', 'end_time', 'label', 'days_of_week', 'specific_date', 'is_active',
])]
class HallTimeSlot extends Model
{
    protected function casts(): array
    {
        return [
            'days_of_week'  => 'array',
            'specific_date' => 'date',
            'is_active'     => 'boolean',
        ];
    }

    public function hall()
    {
        return $this->belongsTo(EventHall::class, 'event_hall_id');
    }

    public function getDurationHoursAttribute(): float
    {
        $start = \Carbon\Carbon::parse($this->start_time);
        $end   = \Carbon\Carbon::parse($this->end_time);

        return round($start->floatDiffInHours($end), 2);
    }
}
