<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class ReceiptController extends Controller
{
    public function show(Booking $booking): Response
    {
        abort_unless($booking->user_id === auth()->id(), 403);

        $pdf = Pdf::loadView('guest.bookings.receipt', compact('booking'));

        return $pdf->download("receipt-{$booking->booking_number}.pdf");
    }
}
