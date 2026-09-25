<?php

namespace App\Http\Controllers;

use App\Models\Addon;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BookingController extends Controller
{
    public function create(Facility $facility): View
    {
        $addons = Addon::where('is_active', true)
            ->where(fn ($q) => $q->where('facility_id', $facility->id)->orWhereNull('facility_id'))
            ->get();

        return view('bookings.create', compact('facility', 'addons'));
    }

    public function store(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:30'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['nullable', 'date', 'after_or_equal:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:'.($facility->capacity ?? 999)],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'promo_code' => ['nullable', 'string', 'max:50'],
            'addons' => ['nullable', 'array'],
            'addons.*' => ['integer', 'exists:addons,id'],
        ]);

        $checkIn = \Carbon\Carbon::parse($validated['check_in']);
        $checkOut = $validated['check_out'] ?? null ? \Carbon\Carbon::parse($validated['check_out']) : null;
        $nights = $checkOut ? max(1, $checkIn->diffInDays($checkOut)) : 1;

        $tentative = new Booking([
            'facility_id' => $facility->id,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
        ]);

        if ($tentative->overlapsWithExistingBooking()) {
            return back()->withInput()->withErrors([
                'check_in' => 'This facility is already booked for the selected dates. Please choose different dates.',
            ]);
        }

        $ratePerUnit = $facility->priceForDate($checkIn);
        $subtotal = $ratePerUnit * $nights * ($facility->price_unit === 'per person' ? $validated['guests'] : 1);

        $selectedAddons = Addon::whereIn('id', $validated['addons'] ?? [])
            ->where('is_active', true)
            ->get();
        $addonsTotal = $selectedAddons->sum('price');

        $promotion = null;
        $discountAmount = 0;

        if (! empty($validated['promo_code'])) {
            $promotion = Promotion::where('code', $validated['promo_code'])
                ->where('is_active', true)
                ->where(fn ($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
                ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
                ->first();

            if (! $promotion) {
                return back()->withInput()->withErrors(['promo_code' => 'This promo code is invalid or has expired.']);
            }

            $discountAmount = $promotion->discount_type === 'percentage'
                ? round(($subtotal + $addonsTotal) * ((float) $promotion->discount_value / 100), 2)
                : min((float) $promotion->discount_value, $subtotal + $addonsTotal);
        }

        $total = max(0, $subtotal + $addonsTotal - $discountAmount);

        $booking = Booking::create([
            'guest_name' => $validated['guest_name'],
            'guest_email' => $validated['guest_email'],
            'guest_phone' => $validated['guest_phone'] ?? null,
            'check_in' => $checkIn,
            'check_out' => $checkOut,
            'guests' => $validated['guests'],
            'special_requests' => $validated['special_requests'] ?? null,
            'user_id' => auth()->id(),
            'facility_id' => $facility->id,
            'promotion_id' => $promotion?->id,
            'subtotal' => $subtotal,
            'addons_total' => $addonsTotal,
            'discount_amount' => $discountAmount,
            'total_price' => $total,
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        foreach ($selectedAddons as $addon) {
            $booking->addons()->attach($addon->id, [
                'quantity' => 1,
                'price_at_booking' => $addon->price,
            ]);
        }

        return redirect()->route('bookings.confirmation', $booking)
            ->with('success', 'Your booking request has been received. We will confirm shortly.');
    }

    public function confirmation(Booking $booking): View
    {
        $qrCode = QrCode::size(200)->format('svg')->generate($booking->booking_number);

        return view('bookings.confirmation', compact('booking', 'qrCode'));
    }
}
