<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

#[Fillable([
    'quote_number', 'customer_token', 'user_id', 'event_hall_id', 'franchise_id',
    'guest_name', 'guest_email', 'guest_phone', 'event_type', 'guests',
    'event_date', 'start_time', 'end_time', 'duration_hours', 'requirements',
    'customer_notes',
    'subtotal', 'addons_total', 'discount_amount', 'discount_reason',
    'tax_rate', 'tax_amount', 'service_charge_rate', 'service_charge_amount',
    'total_amount', 'deposit_required', 'balance_due',
    'status', 'valid_until', 'sent_at', 'viewed_at', 'accepted_at', 'rejected_at',
    'converted_at', 'customer_submitted_at', 'rejection_reason', 'terms_conditions', 'internal_notes',
    'created_by', 'updated_by',
])]
class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'event_date'             => 'date',
            'valid_until'            => 'date',
            'sent_at'                => 'datetime',
            'viewed_at'              => 'datetime',
            'accepted_at'            => 'datetime',
            'rejected_at'            => 'datetime',
            'converted_at'           => 'datetime',
            'customer_submitted_at'  => 'datetime',
            'subtotal'               => 'decimal:2',
            'addons_total'           => 'decimal:2',
            'discount_amount'        => 'decimal:2',
            'tax_rate'               => 'decimal:2',
            'tax_amount'             => 'decimal:2',
            'service_charge_rate'    => 'decimal:2',
            'service_charge_amount'  => 'decimal:2',
            'total_amount'           => 'decimal:2',
            'deposit_required'       => 'decimal:2',
            'balance_due'            => 'decimal:2',
            'duration_hours'         => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Quotation $quotation) {
            if (empty($quotation->quote_number)) {
                $quotation->quote_number = 'QT-'.strtoupper(Str::random(8));
            }
            if (empty($quotation->customer_token)) {
                $quotation->customer_token = Str::uuid()->toString();
            }
        });
    }

    public function generateCustomerToken(): string
    {
        $token = Str::uuid()->toString();
        $this->update(['customer_token' => $token]);
        return $token;
    }

    public function getRouteKeyName(): string
    {
        return 'quote_number';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventHall()
    {
        return $this->belongsTo(EventHall::class);
    }

    public function franchise()
    {
        return $this->belongsTo(Franchise::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class)->orderBy('sort_order');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isExpired(): bool
    {
        return $this->valid_until && $this->valid_until->isPast()
            && ! in_array($this->status, ['accepted', 'converted', 'cancelled']);
    }

    public function canBeConverted(): bool
    {
        return $this->status === 'accepted' && ! $this->isExpired();
    }

    public function markAsViewed(): void
    {
        if (! $this->viewed_at) {
            $this->update(['viewed_at' => now(), 'status' => 'viewed']);
        }
    }
}
