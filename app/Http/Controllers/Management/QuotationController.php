<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use App\Models\Quotation;
use App\Services\QuotationCalculator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class QuotationController extends Controller
{
    public function __construct(private QuotationCalculator $calculator) {}

    public function index(Request $request): View
    {
        $query = Quotation::with(['eventHall.franchise'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('quote_number', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_email', 'like', '%'.$request->search.'%');
            });
        }
        if ($request->filled('franchise_id')) {
            $query->where('franchise_id', $request->franchise_id);
        }

        $quotations = $query->paginate(20)->withQueryString();

        return view('management.quotations.index', compact('quotations'));
    }

    public function show(Quotation $quotation): View
    {
        $quotation->load(['eventHall.franchise', 'items', 'booking', 'createdBy']);
        $quotation->markAsViewed();

        return view('management.quotations.show', compact('quotation'));
    }

    public function edit(Quotation $quotation): View
    {
        $quotation->load(['eventHall.franchise', 'items']);

        return view('management.quotations.edit', compact('quotation'));
    }

    public function update(Request $request, Quotation $quotation): RedirectResponse
    {
        $validated = $request->validate([
            'discount_amount'  => ['nullable', 'numeric', 'min:0'],
            'discount_reason'  => ['nullable', 'string', 'max:500'],
            'valid_until'      => ['nullable', 'date'],
            'terms_conditions' => ['nullable', 'string'],
            'internal_notes'   => ['nullable', 'string'],
            'items'            => ['nullable', 'array'],
            'items.*.id'       => ['required', 'exists:quotation_items,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:1'],
            'items.*.unit_price'=> ['required', 'numeric', 'min:0'],
        ]);

        // Process item updates
        if (!empty($validated['items'])) {
            $newSubtotal = 0;
            $newAddonsTotal = 0;

            foreach ($validated['items'] as $itemData) {
                $item = $quotation->items()->find($itemData['id']);
                if ($item) {
                    $itemTotal = round((float)$itemData['quantity'] * (float)$itemData['unit_price'], 2);
                    $item->update([
                        'quantity'   => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'total'      => $itemTotal,
                    ]);

                    if ($item->item_type === 'hall') {
                        $newSubtotal += $itemTotal;
                    } else {
                        $newAddonsTotal += $itemTotal;
                    }
                }
            }

            // Update quotation base totals
            $quotation->update([
                'subtotal'     => $newSubtotal,
                'addons_total' => $newAddonsTotal,
            ]);
        }

        $oldDiscount = $quotation->discount_amount;
        $quotation->update([
            'discount_amount'  => $validated['discount_amount'] ?? $quotation->discount_amount,
            'discount_reason'  => $validated['discount_reason'] ?? $quotation->discount_reason,
            'valid_until'      => $validated['valid_until'] ?? $quotation->valid_until,
            'terms_conditions' => $validated['terms_conditions'] ?? $quotation->terms_conditions,
            'internal_notes'   => $validated['internal_notes'] ?? $quotation->internal_notes,
            'updated_by'       => auth()->id(),
        ]);

        // Recalculate totals
        $discounted = max(0, ($quotation->subtotal + $quotation->addons_total) - (float) $quotation->discount_amount);
        $taxAmount  = round($discounted * ($quotation->tax_rate / 100), 2);
        $scAmount   = round($discounted * ($quotation->service_charge_rate / 100), 2);
        $total      = $discounted + $taxAmount + $scAmount;
        $deposit    = round($total * 0.3, 2);

        $quotation->update([
            'tax_amount'             => $taxAmount,
            'service_charge_amount'  => $scAmount,
            'total_amount'           => $total,
            'deposit_required'       => $deposit,
            'balance_due'            => max(0, $total - $deposit),
        ]);

        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => 'price_override',
            'description' => "Discount changed from {$oldDiscount} to " . ($validated['discount_amount'] ?? $oldDiscount) . " on quote {$quotation->quote_number}",
        ]);
        return redirect()->route('management.quotations.show', $quotation)
            ->with('success', 'Quotation updated.');
    }

    public function send(Quotation $quotation): RedirectResponse
    {
        $quotation->update(['status' => 'sent', 'sent_at' => now()]);

        // TODO: dispatch mail notification
        // Mail::to($quotation->guest_email)->send(new QuotationMail($quotation));

        return back()->with('success', 'Quotation marked as sent.');
    }

    public function accept(Quotation $quotation): RedirectResponse
    {
        $quotation->update(['status' => 'accepted', 'accepted_at' => now()]);

        return back()->with('success', 'Quotation accepted.');
    }

    public function reject(Request $request, Quotation $quotation): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['nullable', 'string', 'max:1000']]);

        $quotation->update([
            'status'           => 'rejected',
            'rejected_at'      => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Quotation rejected.');
    }

    public function convert(Quotation $quotation): RedirectResponse
    {
        if (! $quotation->canBeConverted()) {
            return back()->withErrors(['error' => 'Quotation cannot be converted.']);
        }

        $booking = $this->calculator->convertToBooking($quotation);

        return redirect()->route('management.bookings.show', $booking)
            ->with('success', 'Quotation converted to booking #'.$booking->booking_number);
    }

    public function pdf(Quotation $quotation): Response
    {
        $quotation->load(['eventHall.franchise', 'items']);

        $pdf = Pdf::loadView('management.quotations.pdf', compact('quotation'));

        return $pdf->download("quote-{$quotation->quote_number}.pdf");
    }

    public function expire(Quotation $quotation): RedirectResponse
    {
        $quotation->update(['status' => 'expired', 'valid_until' => now()->subDay()]);

        return back()->with('success', 'Quotation expired.');
    }
}
