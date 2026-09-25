<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt {{ $booking->booking_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1f2d24; font-size: 13px; }
        h1 { color: #2f5233; font-size: 20px; margin-bottom: 0; }
        .muted { color: #6b7d70; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        td, th { padding: 8px 0; text-align: left; }
        .totals td { border-top: 1px solid #d8e3da; }
        .totals .grand { font-weight: bold; font-size: 15px; }
        .header { border-bottom: 2px solid #2f5233; padding-bottom: 12px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Savanna Hill Resort</h1>
        <p class="muted">Sungai Tiram, Ulu Tiram, Johor &middot; Booking Receipt</p>
    </div>

    <table>
        <tr><td class="muted">Booking Reference</td><td>{{ $booking->booking_number }}</td></tr>
        <tr><td class="muted">Guest Name</td><td>{{ $booking->guest_name }}</td></tr>
        <tr><td class="muted">Email</td><td>{{ $booking->guest_email }}</td></tr>
        <tr><td class="muted">Facility</td><td>{{ $booking->facility->name ?? '-' }}</td></tr>
        <tr><td class="muted">Check-in</td><td>{{ $booking->check_in->format('d M Y') }}</td></tr>
        @if ($booking->check_out)
            <tr><td class="muted">Check-out</td><td>{{ $booking->check_out->format('d M Y') }}</td></tr>
        @endif
        <tr><td class="muted">Guests</td><td>{{ $booking->guests }}</td></tr>
        <tr><td class="muted">Status</td><td style="text-transform: capitalize;">{{ $booking->status }}</td></tr>
        <tr><td class="muted">Payment Status</td><td style="text-transform: capitalize;">{{ $booking->payment_status }}</td></tr>
    </table>

    @if ($booking->addons->isNotEmpty())
        <table>
            <tr><th class="muted">Add-on</th><th class="muted" style="text-align:right;">Price</th></tr>
            @foreach ($booking->addons as $addon)
                <tr><td>{{ $addon->name }}</td><td style="text-align:right;">RM {{ number_format($addon->pivot->price_at_booking, 2) }}</td></tr>
            @endforeach
        </table>
    @endif

    <table class="totals">
        <tr><td>Subtotal</td><td style="text-align:right;">RM {{ number_format($booking->subtotal, 2) }}</td></tr>
        @if ($booking->addons_total > 0)
            <tr><td>Add-ons Total</td><td style="text-align:right;">RM {{ number_format($booking->addons_total, 2) }}</td></tr>
        @endif
        @if ($booking->discount_amount > 0)
            <tr><td>Discount{{ $booking->promotion ? ' ('.$booking->promotion->code.')' : '' }}</td><td style="text-align:right;">- RM {{ number_format($booking->discount_amount, 2) }}</td></tr>
        @endif
        <tr class="grand"><td>Total</td><td style="text-align:right;">RM {{ number_format($booking->total_price, 2) }}</td></tr>
    </table>

    <p class="muted" style="margin-top: 30px;">Thank you for booking with Savanna Hill Resort. This receipt was generated on {{ now()->format('d M Y, h:i A') }}.</p>
</body>
</html>
