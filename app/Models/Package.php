<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title', 'slug', 'short_description', 'description', 'cover_image',
    'price', 'original_price', 'badge', 'is_active',
])]
class Package extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'original_price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'package_facility');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
