<?php

namespace App\Http\Controllers;

use App\Models\EventHall;
use App\Models\Franchise;
use App\Models\Quotation;
use App\Services\QuotationCalculator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(private QuotationCalculator $calculator) {}

    /**
     * Show the quote builder / instant calculator.
     */
    public function create(Request $request): View
    {
        $franchises = Franchise::where('status', 'active')->with('activeHalls.addons')->get();

        $selectedHall = null;
        $existingQuotation = null;

        if ($request->filled('quotation_id')) {
            $existingQuotation = Quotation::with('items')->where('quote_number', $request->quotation_id)->orWhere('id', $request->quotation_id)->first();
            if ($existingQuotation) {
                $selectedHall = EventHall::with(['franchise', 'pricingRules', 'addons'])->find($existingQuotation->event_hall_id);
            }
        } elseif ($request->filled('hall_id')) {
            $selectedHall = EventHall::with(['franchise', 'pricingRules', 'addons'])
                ->find($request->hall_id);
        }

        return view('quotations.create', compact('franchises', 'selectedHall', 'existingQuotation'));
    }

    /**
     * Live calculate — returns JSON for AJAX preview.
     */
    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'event_hall_id' => ['required', 'exists:event_halls,id'],
            'event_date'    => ['required', 'date', 'after_or_equal:today'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'guests'        => ['required', 'integer', 'min:1'],
            'addons'        => ['nullable', 'array'],
            'addons.*'      => ['integer', 'exists:addons,id'],
            'addon_quantities' => ['nullable', 'array'],
        ]);

        try {
            $result = $this->calculator->calculate($request->all());

            // Format for JSON
            return response()->json([
                'success'        => true,
                'subtotal'       => number_format($result['subtotal'], 2),
                'addons_total'   => number_format($result['addons_total'], 2),
                'discount_amount'=> number_format($result['discount_amount'], 2),
                'tax_amount'     => number_format($result['tax_amount'], 2),
                'service_charge' => number_format($result['service_charge_amount'], 2),
                'total_amount'   => number_format($result['total_amount'], 2),
                'deposit_required'=> number_format($result['deposit_required'], 2),
                'balance_due'    => number_format($result['balance_due'], 2),
                'tax_rate'       => $result['tax_rate'],
                'service_charge_rate' => $result['service_charge_rate'],
                'deposit_percent'=> $result['deposit_percent'],
                'duration_hours' => $result['duration_hours'],
                'rate_type'      => $result['rate_type'],
                'items'          => $result['items'],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Store a generated quotation.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'event_hall_id' => ['required', 'exists:event_halls,id'],
            'event_date'    => ['required', 'date', 'after_or_equal:today'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'guests'        => ['required', 'integer', 'min:1'],
            'guest_name'    => ['required', 'string', 'max:255'],
            'guest_email'   => ['required', 'email', 'max:255'],
            'guest_phone'   => ['nullable', 'string', 'max:30'],
            'event_type'    => ['nullable', 'string', 'max:100'],
            'requirements'  => ['nullable', 'string', 'max:2000'],
            'addons'        => ['nullable', 'array'],
            'addons.*'      => ['integer', 'exists:addons,id'],
            'addon_quantities' => ['nullable', 'array'],
        ]);

        // Check availability
        $hall = EventHall::findOrFail($request->event_hall_id);
        if ($hall->isSlotBooked(
            \Carbon\Carbon::parse($request->event_date),
            $request->start_time.':00',
            $request->end_time.':00'
        )) {
            return back()->withInput()->withErrors([
                'start_time' => 'This time slot is already booked. Please choose a different time.',
            ]);
        }

        $calculated = $this->calculator->calculate($request->all());
        
        if ($request->filled('quotation_id')) {
            $existingQuotation = Quotation::where('quote_number', $request->quotation_id)->orWhere('id', $request->quotation_id)->firstOrFail();
            $quotation = $this->calculator->updateQuotation($existingQuotation, $request->all(), $calculated);
            \App\Models\ActivityLog::record('updated', $quotation, 'Customer updated the quotation requirements via public form.');
            $msg = 'Your quotation has been updated!';
        } else {
            $quotation  = $this->calculator->createQuotation($request->all(), $calculated);
            $msg = 'Your quotation has been generated!';
        }

        return redirect()->route('quotations.show', $quotation)
            ->with('success', $msg);
    }

    /**
     * Customer view of their quotation.
     */
    public function show(Quotation $quotation): View
    {
        $quotation->load(['eventHall.franchise', 'items']);
        $quotation->markAsViewed();

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Accept a quotation (customer action).
     */
    public function accept(Request $request, Quotation $quotation): RedirectResponse
    {
        if ($quotation->isExpired()) {
            return back()->withErrors(['error' => 'This quotation has expired.']);
        }
        if (! in_array($quotation->status, ['generated', 'sent', 'viewed'])) {
            return back()->withErrors(['error' => 'This quotation cannot be accepted.']);
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($quotation) {
            $quotation->update([
                'status' => 'accepted', 
                'accepted_at' => now()
            ]);

            // Convert to booking
            $booking = \App\Models\Booking::create([
                'booking_number'  => 'EVH-' . strtoupper(uniqid()),
                'user_id'         => $quotation->user_id,
                'event_hall_id'   => $quotation->event_hall_id,
                'quotation_id'    => $quotation->id,
                'booking_type'    => 'event_hall',
                'guest_name'      => $quotation->guest_name,
                'guest_email'     => $quotation->guest_email,
                'guest_phone'     => $quotation->guest_phone,
                'check_in'        => clone $quotation->event_date,
                'start_time'      => $quotation->start_time,
                'end_time'        => $quotation->end_time,
                'duration_hours'  => $quotation->duration_hours,
                'guests'          => $quotation->guests,
                'event_type'      => $quotation->event_type,
                'subtotal'        => $quotation->subtotal,
                'addons_total'    => $quotation->addons_total,
                'discount_amount' => $quotation->discount_amount,
                'total_price'     => $quotation->total_amount,
                'deposit_amount'  => $quotation->deposit_required,
                'deposit_paid'    => false,
                'status'          => 'pending',
                'payment_status'  => 'pending',
                'special_requests'=> $quotation->requirements,
            ]);

            // Copy items to booking addons
            foreach ($quotation->items as $item) {
                if ($item->item_type === 'addon' && $item->addon_id) {
                    \Illuminate\Support\Facades\DB::table('booking_addons')->insert([
                        'booking_id' => $booking->id,
                        'addon_id'   => $item->addon_id,
                        'quantity'   => $item->quantity,
                        'unit_price' => $item->unit_price,
                        'total_price'=> $item->total,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        });

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation accepted! Your booking is now pending deposit payment.');
    }

    /**
     * Reject a quotation (customer action).
     */
    public function reject(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['nullable', 'string', 'max:500']]);

        $quotation->update([
            'status'           => 'rejected',
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('quotations.show', $quotation)
            ->with('success', 'Quotation declined.');
    }

    /**
     * Download PDF.
     */
    public function pdf(Quotation $quotation): \Illuminate\Http\Response
    {
        $quotation->load(['eventHall.franchise', 'items']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('quotations.pdf', compact('quotation'));

        return $pdf->download("quote-{$quotation->quote_number}.pdf");
    }
}
