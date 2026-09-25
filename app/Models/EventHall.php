<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'franchise_id', 'name', 'code', 'description', 'capacity', 'floor_area', 'hall_type',
    'facilities', 'amenities', 'cover_image', 'operating_hours',
    'min_booking_hours', 'max_booking_hours', 'buffer_before', 'buffer_after',
    'is_active', 'sort_order',
])]
class EventHall extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'facilities'      => 'array',
            'amenities'       => 'array',
            'operating_hours' => 'array',
            'is_active'       => 'boolean',
            'floor_area'      => 'decimal:2',
        ];
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function images()
    {
        return $this->hasMany(HallImage::class)->orderBy('sort_order');
    }

    public function pricingRules()
    {
        return $this->hasMany(HallPricingRule::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(HallTimeSlot::class)->where('is_active', true);
    }

    public function blockouts()
    {
        return $this->hasMany(HallBlockout::class)->where('is_active', true);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class)->where('is_active', true);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get the applicable pricing rule for a given date, duration type, and time.
     */
    public function priceForSlot(\DateTimeInterface $date, string $rateType = 'hourly'): ?HallPricingRule
    {
        $dayOfWeek = (int) $date->format('N'); // 1=Mon, 7=Sun
        $isWeekend = in_array($dayOfWeek, [6, 7]);

        return $this->pricingRules()
            ->where('rate_type', $rateType)
            ->where('is_active', true)
            ->where(function ($q) use ($isWeekend) {
                $q->where('day_type', 'all')
                    ->orWhere('day_type', $isWeekend ? 'weekend' : 'weekday');
            })
            ->where(function ($q) use ($date) {
                $q->whereNull('season_start')
                    ->orWhere(function ($sq) use ($date) {
                        $sq->where('season_start', '<=', $date->format('Y-m-d'))
                            ->where('season_end', '>=', $date->format('Y-m-d'));
                    });
            })
            ->orderByDesc('priority')
            ->first();
    }

    /**
     * Check if this hall is blocked out on a given date/time range.
     */
    public function isBlockedOut(\DateTimeInterface $date, ?string $startTime = null, ?string $endTime = null): bool
    {
        $dateStr = $date->format('Y-m-d');

        return $this->blockouts()
            ->where('date_from', '<=', $dateStr)
            ->where('date_to', '>=', $dateStr)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where('blockout_type', 'full_day')
                    ->orWhere('blockout_type', 'maintenance')
                    ->when($startTime && $endTime, function ($sq) use ($startTime, $endTime) {
                        $sq->orWhere(function($inner) use ($startTime, $endTime) {
                            $inner->where('blockout_type', 'time_range')
                                  ->where('time_from', '<', $endTime)
                                  ->where('time_to', '>', $startTime);
                        });
                    });
            })
            ->exists();
    }

    /**
     * Get available time slots for a specific date.
     */
    public function availableSlotsForDate(\Carbon\Carbon $date): \Illuminate\Support\Collection
    {
        $dayOfWeek = (int) $date->format('N');

        $slots = HallTimeSlot::where('event_hall_id', $this->id)
            ->where('is_active', true)
            ->where(function ($q) use ($dayOfWeek, $date) {
                $q->whereNull('days_of_week')
                    ->orWhereJsonContains('days_of_week', $dayOfWeek);
            })
            ->where(function ($q) use ($date) {
                $q->whereNull('specific_date')
                    ->orWhere('specific_date', $date->format('Y-m-d'));
            })
            ->get();

        // Filter out booked/blocked slots
        return $slots->filter(function ($slot) use ($date) {
            return ! $this->isSlotBooked($date, $slot->start_time, $slot->end_time)
                && ! $this->isBlockedOut($date, $slot->start_time, $slot->end_time);
        });
    }

    /**
     * Check if a specific time slot is already booked.
     */
    public function isSlotBooked(\Carbon\Carbon $date, string $startTime, string $endTime): bool
    {
        $bufferBefore = $this->buffer_before; // minutes
        $bufferAfter  = $this->buffer_after;

        $effectiveStart = \Carbon\Carbon::parse($date->format('Y-m-d').' '.$startTime)
            ->subMinutes($bufferBefore)->format('H:i:s');
        $effectiveEnd = \Carbon\Carbon::parse($date->format('Y-m-d').' '.$endTime)
            ->addMinutes($bufferAfter)->format('H:i:s');

        return Booking::where('event_hall_id', $this->id)
            ->where('check_in', $date->format('Y-m-d'))
            ->whereNotIn('status', ['cancelled'])
            ->where('start_time', '<', $effectiveEnd)
            ->where('end_time', '>', $effectiveStart)
            ->exists();
    }
}
