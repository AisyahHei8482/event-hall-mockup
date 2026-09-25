<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['facility_id', 'event_hall_id', 'name', 'category', 'description', 'price', 'unit', 'is_quantifiable', 'max_quantity', 'is_active'])]
class Addon extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class)
            ->withPivot(['quantity', 'price_at_booking'])
            ->withTimestamps();
    }
}
