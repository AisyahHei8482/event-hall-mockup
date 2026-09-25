<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Response;

class CalendarController extends Controller
{
    public function show(Facility $facility): Response
    {
        $bookings = $facility->bookings()
            ->whereIn('status', ['confirmed', 'completed'])
            ->whereNotNull('check_in')
            ->get();

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Savanna Hill Resort//Booking Calendar//EN',
            'CALSCALE:GREGORIAN',
            'X-WR-CALNAME:'.$facility->name.' Availability',
        ];

        foreach ($bookings as $booking) {
            $checkIn = $booking->check_in->format('Ymd');
            $checkOut = ($booking->check_out ?? $booking->check_in->addDay())->format('Ymd');

            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:'.$booking->booking_number.'@savannahill.com.my';
            $lines[] = 'DTSTAMP:'.now()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART;VALUE=DATE:'.$checkIn;
            $lines[] = 'DTEND;VALUE=DATE:'.$checkOut;
            $lines[] = 'SUMMARY:Booked - '.$facility->name;
            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines))
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="'.$facility->slug.'.ics"');
    }
}
