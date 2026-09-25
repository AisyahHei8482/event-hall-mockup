<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'event_hall_id', 'reason', 'blockout_type', 'date_from', 'date_to',
    'time_from', 'time_to', 'is_active',
])]
class HallBlockout extends Model
{
    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to'   => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function hall()
    {
        return $this->belongsTo(EventHall::class, 'event_hall_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
