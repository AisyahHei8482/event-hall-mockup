<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'event_hall_id', 'label', 'rate_type', 'day_type', 'time_type',
    'time_from', 'time_to', 'price', 'half_day_hours', 'full_day_hours',
    'season_start', 'season_end', 'is_active', 'priority',
])]
class HallPricingRule extends Model
{
    protected function casts(): array
    {
        return [
            'price'          => 'decimal:2',
            'half_day_hours' => 'decimal:2',
            'full_day_hours' => 'decimal:2',
            'season_start'   => 'date',
            'season_end'     => 'date',
            'is_active'      => 'boolean',
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
