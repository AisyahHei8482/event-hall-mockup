<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['facility_id', 'label', 'starts_on', 'ends_on', 'price_override', 'price_multiplier', 'is_active'])]
class SeasonalRate extends Model
{
    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
            'price_override' => 'decimal:2',
            'price_multiplier' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function coversDate(\DateTimeInterface $date): bool
    {
        return $date >= $this->starts_on && $date <= $this->ends_on;
    }

    public function priceFor(float $basePrice): float
    {
        if ($this->price_override !== null) {
            return (float) $this->price_override;
        }

        if ($this->price_multiplier !== null) {
            return round($basePrice * (float) $this->price_multiplier, 2);
        }

        return $basePrice;
    }
}
