<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $bookings = Booking::with('facility')
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load('facility', 'user', 'promotion');

        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,checked_in,completed,cancelled'],
            'payment_status' => ['required', 'in:unpaid,paid,refunded'],
        ]);

        $booking->update($validated);

        ActivityLog::record('booking.updated', $booking, "Updated booking {$booking->booking_number} to status={$validated['status']}, payment={$validated['payment_status']}");

        return back()->with('success', 'Booking updated.');
    }
}
