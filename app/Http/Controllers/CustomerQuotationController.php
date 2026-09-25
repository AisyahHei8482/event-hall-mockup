<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Customer-facing quotation review page.
 * Accessed via a unique token URL — no login required.
 */
class CustomerQuotationController extends Controller
{
    public function show(string $token): View
    {
        $quotation = Quotation::where('customer_token', $token)
            ->with(['eventHall', 'items'])
            ->firstOrFail();

        // Mark as viewed if not yet
        $quotation->markAsViewed();

        return view('customer.quotation', compact('quotation'));
    }

    public function submit(Request $request, string $token): RedirectResponse
    {
        $quotation = Quotation::where('customer_token', $token)->firstOrFail();

        // Only allow submission if in sendable states
        if (!in_array($quotation->status, ['sent', 'viewed', 'draft', 'generated'])) {
            return back()->with('error', 'This quotation can no longer be modified.');
        }

        $validated = $request->validate([
            'guest_name'     => ['required', 'string', 'max:255'],
            'guest_phone'    => ['nullable', 'string', 'max:30'],
            'event_date'     => ['required', 'date', 'after_or_equal:today'],
            'start_time'     => ['required', 'date_format:H:i'],
            'end_time'       => ['required', 'date_format:H:i', 'after:start_time'],
            'guests'         => ['required', 'integer', 'min:1'],
            'event_type'     => ['nullable', 'string', 'max:100'],
            'requirements'   => ['nullable', 'string'],
            'customer_notes' => ['nullable', 'string', 'max:2000'],
            'action'         => ['required', 'in:save,accept,reject'],
        ]);

        $action = $validated['action'];
        unset($validated['action']);

        $validated['customer_submitted_at'] = now();

        if ($action === 'accept') {
            $validated['accepted_at'] = now();
            $validated['status']      = 'accepted';
        } elseif ($action === 'reject') {
            $validated['rejected_at']      = now();
            $validated['rejection_reason'] = $request->input('rejection_reason');
            $validated['status']           = 'rejected';
        } else {
            // Just save changes, keep as viewed
            $validated['status'] = 'viewed';
        }

        $quotation->update($validated);

        $message = match($action) {
            'accept' => 'Thank you! Your quotation has been accepted. We will contact you shortly.',
            'reject' => 'Your response has been recorded. Feel free to contact us to discuss further.',
            default  => 'Your changes have been saved successfully.',
        };

        return redirect()->route('customer.quotation.show', $token)
            ->with('success', $message);
    }
}
