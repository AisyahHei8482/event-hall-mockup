<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function index(): View
    {
        $bookings = auth()->user()->bookings()
            ->with('facility', 'package')
            ->latest()
            ->paginate(10);

        return view('guest.bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking): RedirectResponse
    {
        abort_unless($booking->user_id === auth()->id(), 403);
        abort_if(in_array($booking->status, ['completed', 'cancelled']), 422);

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking cancelled.');
    }
}
