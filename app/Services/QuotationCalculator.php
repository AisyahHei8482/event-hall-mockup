<?php

namespace App\Services;

use App\Models\Addon;
use App\Models\EventHall;
use App\Models\HallPricingRule;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class QuotationCalculator
{
    private float $taxRate;
    private float $serviceChargeRate;
    private float $defaultDepositPercent;

    public function __construct()
    {
        $this->taxRate             = (float) Setting::get('tax_rate', 0);
        $this->serviceChargeRate   = (float) Setting::get('service_charge_rate', 0);
        $this->defaultDepositPercent = (float) Setting::get('deposit_percent', 30);
    }

    /**
     * Calculate a quote breakdown for given parameters.
     * Returns array usable for live preview (no DB writes).
     */
    public function calculate(array $params): array
    {
        $hall      = EventHall::with('pricingRules')->findOrFail($params['event_hall_id']);
        $date      = Carbon::parse($params['event_date']);
        $startTime = $params['start_time'];
        $endTime   = $params['end_time'];
        $guests    = (int) ($params['guests'] ?? 1);

        // Duration
        $start         = Carbon::parse($date->format('Y-m-d').' '.$startTime);
        $end           = Carbon::parse($date->format('Y-m-d').' '.$endTime);
        $durationHours = round($start->floatDiffInHours($end), 2);

        // Resolve applicable rate type
        $rateType   = $this->resolveRateType($durationHours, $hall);
        $pricingRule = $hall->priceForSlot($date, $rateType);

        $hallRental = $pricingRule ? (float) $pricingRule->price : 0.0;

        // If hourly, multiply by duration
        if ($rateType === 'hourly') {
            $hallRental = round($hallRental * $durationHours, 2);
        }

        $items = [];
        $items[] = [
            'item_type'       => 'hall_rental',
            'description'     => $hall->name.' — '.ucfirst($rateType).' rental ('.$durationHours.' hrs)',
            'quantity'        => $rateType === 'hourly' ? $durationHours : 1,
            'unit'            => $rateType === 'hourly' ? 'hours' : 'session',
            'unit_price'      => $pricingRule ? (float) $pricingRule->price : 0,
            'discount_amount' => 0,
            'total'           => $hallRental,
        ];

        // Add-ons
        $addonsTotal = 0.0;
        $selectedAddons = ! empty($params['addons']) ? Addon::whereIn('id', $params['addons'])->where('is_active', true)->get() : collect();

        foreach ($selectedAddons as $addon) {
            $qty        = (int) ($params['addon_quantities'][$addon->id] ?? 1);
            $addonTotal = round((float) $addon->price * $qty, 2);
            $addonsTotal += $addonTotal;

            $items[] = [
                'item_type'       => 'addon',
                'description'     => $addon->name,
                'quantity'        => $qty,
                'unit'            => $addon->unit ?? 'unit',
                'unit_price'      => (float) $addon->price,
                'discount_amount' => 0,
                'total'           => $addonTotal,
                'addon_id'        => $addon->id,
            ];
        }

        $subtotal          = $hallRental;
        $discountAmount    = (float) ($params['discount_amount'] ?? 0);
        $discountedSubtotal = max(0, $subtotal + $addonsTotal - $discountAmount);
        $taxAmount          = round($discountedSubtotal * ($this->taxRate / 100), 2);
        $serviceCharge      = round($discountedSubtotal * ($this->serviceChargeRate / 100), 2);
        $totalAmount        = round($discountedSubtotal + $taxAmount + $serviceCharge, 2);
        $depositRequired    = round($totalAmount * ($this->defaultDepositPercent / 100), 2);
        $balanceDue         = max(0, $totalAmount - $depositRequired);

        return [
            'hall'                   => $hall,
            'date'                   => $date->format('Y-m-d'),
            'start_time'             => $startTime,
            'end_time'               => $endTime,
            'duration_hours'         => $durationHours,
            'rate_type'              => $rateType,
            'pricing_rule'           => $pricingRule,
            'items'                  => $items,
            'subtotal'               => $subtotal,
            'addons_total'           => $addonsTotal,
            'discount_amount'        => $discountAmount,
            'tax_rate'               => $this->taxRate,
            'tax_amount'             => $taxAmount,
            'service_charge_rate'    => $this->serviceChargeRate,
            'service_charge_amount'  => $serviceCharge,
            'total_amount'           => $totalAmount,
            'deposit_required'       => $depositRequired,
            'balance_due'            => $balanceDue,
            'deposit_percent'        => $this->defaultDepositPercent,
        ];
    }

    /**
     * Persist a quotation from calculated params.
     */
    public function createQuotation(array $params, array $calculated): Quotation
    {
        return DB::transaction(function () use ($params, $calculated) {
            $hall = $calculated['hall'];

            $quotation = Quotation::create([
                'user_id'                => auth()->id(),
                'event_hall_id'          => $hall->id,
                'franchise_id'           => $hall->franchise_id,
                'guest_name'             => $params['guest_name'],
                'guest_email'            => $params['guest_email'],
                'guest_phone'            => $params['guest_phone'] ?? null,
                'event_type'             => $params['event_type'] ?? null,
                'guests'                 => $params['guests'] ?? 1,
                'event_date'             => $calculated['date'],
                'start_time'             => $calculated['start_time'],
                'end_time'               => $calculated['end_time'],
                'duration_hours'         => $calculated['duration_hours'],
                'requirements'           => $params['requirements'] ?? null,
                'subtotal'               => $calculated['subtotal'],
                'addons_total'           => $calculated['addons_total'],
                'discount_amount'        => $calculated['discount_amount'],
                'discount_reason'        => $params['discount_reason'] ?? null,
                'tax_rate'               => $calculated['tax_rate'],
                'tax_amount'             => $calculated['tax_amount'],
                'service_charge_rate'    => $calculated['service_charge_rate'],
                'service_charge_amount'  => $calculated['service_charge_amount'],
                'total_amount'           => $calculated['total_amount'],
                'deposit_required'       => $calculated['deposit_required'],
                'balance_due'            => $calculated['balance_due'],
                'status'                 => 'generated',
                'valid_until'            => now()->addDays((int) Setting::get('quote_validity_days', 7)),
                'terms_conditions'       => Setting::get('quote_terms', ''),
                'created_by'             => auth()->id(),
            ]);

            foreach ($calculated['items'] as $i => $item) {
                QuotationItem::create(array_merge($item, [
                    'quotation_id' => $quotation->id,
                    'sort_order'   => $i,
                ]));
            }

            return $quotation;
        });
    }

    /**
     * Update an existing quotation from calculated params.
     */
    public function updateQuotation(Quotation $quotation, array $params, array $calculated): Quotation
    {
        return DB::transaction(function () use ($quotation, $params, $calculated) {
            $hall = $calculated['hall'];

            $quotation->update([
                'event_hall_id'          => $hall->id,
                'franchise_id'           => $hall->franchise_id,
                'guest_name'             => $params['guest_name'],
                'guest_email'            => $params['guest_email'],
                'guest_phone'            => $params['guest_phone'] ?? null,
                'event_type'             => $params['event_type'] ?? null,
                'guests'                 => $params['guests'] ?? 1,
                'event_date'             => $calculated['date'],
                'start_time'             => $calculated['start_time'],
                'end_time'               => $calculated['end_time'],
                'duration_hours'         => $calculated['duration_hours'],
                'requirements'           => $params['requirements'] ?? null,
                'subtotal'               => $calculated['subtotal'],
                'addons_total'           => $calculated['addons_total'],
                'discount_amount'        => $calculated['discount_amount'],
                'discount_reason'        => $params['discount_reason'] ?? null,
                'tax_rate'               => $calculated['tax_rate'],
                'tax_amount'             => $calculated['tax_amount'],
                'service_charge_rate'    => $calculated['service_charge_rate'],
                'service_charge_amount'  => $calculated['service_charge_amount'],
                'total_amount'           => $calculated['total_amount'],
                'deposit_required'       => $calculated['deposit_required'],
                'balance_due'            => $calculated['balance_due'],
                'status'                 => 'generated',
            ]);

            $quotation->items()->delete();
            foreach ($calculated['items'] as $i => $item) {
                QuotationItem::create(array_merge($item, [
                    'quotation_id' => $quotation->id,
                    'sort_order'   => $i,
                ]));
            }

            return $quotation;
        });
    }

    /**
     * Convert an accepted quotation into a booking.
     */
    public function convertToBooking(Quotation $quotation): \App\Models\Booking
    {
        if (! $quotation->canBeConverted()) {
            throw new \RuntimeException('Quotation cannot be converted: status is '.$quotation->status);
        }

        return DB::transaction(function () use ($quotation) {
            $booking = \App\Models\Booking::create([
                'event_hall_id'  => $quotation->event_hall_id,
                'quotation_id'   => $quotation->id,
                'booking_type'   => 'event_hall',
                'guest_name'     => $quotation->guest_name,
                'guest_email'    => $quotation->guest_email,
                'guest_phone'    => $quotation->guest_phone,
                'user_id'        => $quotation->user_id,
                'check_in'       => $quotation->event_date,
                'start_time'     => $quotation->start_time,
                'end_time'       => $quotation->end_time,
                'duration_hours' => $quotation->duration_hours,
                'guests'         => $quotation->guests,
                'event_type'     => $quotation->event_type,
                'subtotal'       => $quotation->subtotal,
                'addons_total'   => $quotation->addons_total,
                'discount_amount'=> $quotation->discount_amount,
                'total_price'    => $quotation->total_amount,
                'deposit_amount' => $quotation->deposit_required,
                'special_requests'=> $quotation->requirements,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            // Transfer addon items from quotation to booking
            foreach ($quotation->items->where('item_type', 'addon') as $item) {
                if ($item->addon_id) {
                    $booking->addons()->attach($item->addon_id, [
                        'quantity'         => $item->quantity,
                        'price_at_booking' => $item->unit_price,
                    ]);
                }
            }

            $quotation->update([
                'status'       => 'converted',
                'converted_at' => now(),
            ]);

            return $booking;
        });
    }

    private function resolveRateType(float $hours, EventHall $hall): string
    {
        // Check if half-day or full-day rule exists and duration qualifies
        $fullDayRule = $hall->pricingRules()
            ->where('rate_type', 'full_day')
            ->where('is_active', true)
            ->first();

        if ($fullDayRule && $fullDayRule->full_day_hours && $hours >= $fullDayRule->full_day_hours) {
            return 'full_day';
        }

        $halfDayRule = $hall->pricingRules()
            ->where('rate_type', 'half_day')
            ->where('is_active', true)
            ->first();

        if ($halfDayRule && $halfDayRule->half_day_hours && $hours >= $halfDayRule->half_day_hours) {
            return 'half_day';
        }

        return 'hourly';
    }
}
