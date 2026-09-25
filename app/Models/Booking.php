<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'booking_number', 'user_id', 'facility_id', 'event_hall_id', 'quotation_id', 'package_id', 'promotion_id',
    'booking_type', 'guest_name', 'guest_email', 'guest_phone',
    'check_in', 'check_out', 'nights', 'guests',
    'start_time', 'end_time', 'duration_hours', 'event_type',
    'subtotal', 'addons_total', 'discount_amount', 'total_price',
    'deposit_amount', 'deposit_paid', 'special_requests', 'internal_notes',
    'status', 'payment_status',
])]
class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'check_in'        => 'date',
            'check_out'       => 'date',
            'subtotal'        => 'decimal:2',
            'addons_total'    => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'total_price'     => 'decimal:2',
            'deposit_amount'  => 'decimal:2',
            'deposit_paid'    => 'decimal:2',
            'duration_hours'  => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if (empty($booking->booking_number)) {
                $booking->booking_number = 'SHR-'.strtoupper(Str::random(8));
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'booking_number';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function addons()
    {
        return $this->belongsToMany(Addon::class)
            ->withPivot(['quantity', 'price_at_booking'])
            ->withTimestamps();
    }

    public function eventHall()
    {
        return $this->belongsTo(EventHall::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function eventSchedules()
    {
        return $this->hasMany(EventSchedule::class)->orderBy('scheduled_date')->orderBy('start_time');
    }

    public function staffAssignments()
    {
        return $this->hasMany(StaffAssignment::class);
    }

    public function isEventHallBooking(): bool
    {
        return $this->booking_type === 'event_hall';
    }

    public function overlapsWithExistingBooking(): bool
    {
        // Accommodation date-based overlap
        if ($this->booking_type !== 'event_hall') {
            $newCheckOut = $this->check_out ?? $this->check_in->copy()->addDay();

            return static::where('facility_id', $this->facility_id)
                ->where('id', '!=', $this->id ?? 0)
                ->whereNotIn('status', ['cancelled'])
                ->where('check_in', '<', $newCheckOut)
                ->whereRaw('COALESCE(check_out, DATE_ADD(check_in, INTERVAL 1 DAY)) > ?', [$this->check_in])
                ->exists();
        }

        // Event hall time-based overlap (with buffer)
        return static::where('event_hall_id', $this->event_hall_id)
            ->where('id', '!=', $this->id ?? 0)
            ->whereNotIn('status', ['cancelled'])
            ->where('check_in', $this->check_in)
            ->where('start_time', '<', $this->end_time)
            ->where('end_time', '>', $this->start_time)
            ->exists();
    }
}
