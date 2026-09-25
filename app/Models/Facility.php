<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name', 'slug', 'type', 'accommodation_type', 'experience_type', 'short_description', 'description', 'cover_image', 'virtual_tour_url',
    'capacity', 'price', 'price_unit', 'amenities', 'meta',
    'is_featured', 'is_active', 'sort_order',
])]
class Facility extends Model
{
    /** @use HasFactory<\Database\Factories\FacilityFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'amenities' => 'array',
            'meta' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function images()
    {
        return $this->hasMany(FacilityImage::class)->orderBy('sort_order');
    }

    public function addons()
    {
        return $this->hasMany(Addon::class);
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    public function seasonalRates()
    {
        return $this->hasMany(SeasonalRate::class);
    }

    public function priceForDate(\DateTimeInterface $date): float
    {
        $rate = $this->seasonalRates()
            ->active()
            ->where('starts_on', '<=', $date)
            ->where('ends_on', '>=', $date)
            ->first();

        return $rate ? $rate->priceFor((float) $this->price) : (float) $this->price;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function packages()
    {
        return $this->belongsToMany(Package::class, 'package_facility');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->reviews()->where('is_approved', true);
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }
}
