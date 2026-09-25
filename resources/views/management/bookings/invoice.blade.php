<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
    body { color: #334155; line-height: 1.4; font-size: 11px; background: #fff; }
    .page { padding: 40px; margin: 0 auto; max-width: 800px; }
    
    /* Header Section */
    .header { border-bottom: 2px solid #ea580c; padding-bottom: 20px; margin-bottom: 20px; }
    .header table { width: 100%; border: none; margin: 0; }
    .header td { border: none; padding: 0; vertical-align: top; }
    .brand-title { font-size: 24px; font-weight: 800; color: #0f172a; margin-bottom: 4px; text-transform: uppercase; letter-spacing: -0.5px; }
    .brand-subtitle { font-size: 10px; color: #64748b; margin-bottom: 2px; }
    .doc-title { font-size: 28px; font-weight: 900; color: #ea580c; text-align: right; text-transform: uppercase; letter-spacing: -1px; margin-bottom: 4px; }
    .doc-meta { text-align: right; font-size: 11px; font-weight: 600; color: #475569; }
    
    /* Info Cards */
    .info-section { margin-bottom: 20px; }
    .info-section table { width: 100%; border: none; margin: 0; table-layout: fixed; }
    .info-section td { border: none; padding: 0; vertical-align: top; }
    .info-card { background: #f8fafc; border-radius: 6px; padding: 12px; margin-right: 12px; border-left: 3px solid #ea580c; }
    .info-card.last { margin-right: 0; }
    .info-title { font-size: 9px; font-weight: 700; text-transform: uppercase; color: #94a3b8; letter-spacing: 1px; margin-bottom: 8px; }
    .info-row { margin-bottom: 3px; }
    .info-label { display: inline-block; width: 55px; color: #64748b; font-size: 10px; }
    .info-value { font-weight: 600; color: #0f172a; font-size: 10px; }

    /* Main Table */
    .items-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
    .items-table th { background: #0f172a; color: #fff; text-align: left; padding: 8px 10px; font-size: 9px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; border: none; }
    .items-table th.right { text-align: right; }
    .items-table td { padding: 8px 10px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    .items-table td.right { text-align: right; }
    .item-name { font-weight: 700; color: #0f172a; }
    .item-unit { font-size: 9px; color: #94a3b8; text-transform: uppercase; margin-top: 2px; }

    /* Totals Section */
    .totals-container { width: 50%; margin-left: auto; margin-bottom: 30px; }
    .totals-table { width: 100%; border-collapse: collapse; }
    .totals-table td { padding: 6px 10px; border: none; font-size: 11px; }
    .totals-table td.label { color: #64748b; }
    .totals-table td.value { font-weight: 700; text-align: right; color: #0f172a; }
    .totals-table tr.discount td { color: #16a34a; }
    .totals-table tr.grand-total { background: #ea580c; }
    .totals-table tr.grand-total td { color: #fff; font-size: 14px; font-weight: 800; padding: 12px 10px; }
    .totals-table tr.deposit { background: #f8fafc; border-top: 2px solid #fff; }
    .totals-table tr.deposit td { color: #0f172a; font-size: 12px; font-weight: 800; padding: 10px; }

    /* Footer / Terms */
    .footer { text-align: center; border-top: 1px solid #e2e8f0; padding-top: 15px; font-size: 9px; color: #94a3b8; }
</style>
</head>
<body>
<div class="page">
    <!-- Header -->
    <div class="header">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="brand-title">{{ $booking->eventHall->franchise->name ?? 'Savanna Hill Event Halls' }}</div>
                    <div class="brand-subtitle">{{ $booking->eventHall->franchise->address ?? 'Johor Bahru, Malaysia' }}</div>
                    <div class="brand-subtitle">{{ $booking->eventHall->franchise->phone ?? '+60 12 345 6789' }} · {{ $booking->eventHall->franchise->email ?? 'events@savannahill.local' }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="doc-title">Invoice</div>
                    <div class="doc-meta">#{{ $booking->booking_number }}</div>
                    <div class="doc-meta" style="margin-top: 4px; color: #94a3b8;">Generated: {{ now()->format('d M Y') }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Info Cards -->
    <div class="info-section">
        <table>
            <tr>
                <td style="width: 50%;">
                    <div class="info-card">
                        <div class="info-title">Bill To</div>
                        <div class="info-row"><span class="info-label">Name</span><span class="info-value">{{ $booking->guest_name }}</span></div>
                        <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $booking->guest_email }}</span></div>
                        @if($booking->guest_phone)<div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $booking->guest_phone }}</span></div>@endif
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="info-card last">
                        <div class="info-title">Event Summary</div>
                        <div class="info-row"><span class="info-label">Venue</span><span class="info-value">{{ $booking->eventHall->name ?? '—' }}</span></div>
                        <div class="info-row"><span class="info-label">Date</span><span class="info-value">{{ $booking->check_in->format('l, d M Y') }}</span></div>
                        <div class="info-row"><span class="info-label">Time</span><span class="info-value">{{ $booking->start_time }} – {{ $booking->end_time }}</span></div>
                        <div class="info-row"><span class="info-label">Guests</span><span class="info-value">{{ $booking->guests }} pax</span></div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th>Description</th>
                <th class="right">Qty</th>
                <th class="right">Unit Price</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="item-name">Hall Rental — {{ $booking->eventHall->name ?? '' }}</div>
                    <div class="item-unit">per hours ({{ $booking->duration_hours }} hrs)</div>
                </td>
                <td class="right" style="color: #64748b;">1</td>
                <td class="right">RM {{ number_format($booking->subtotal, 2) }}</td>
                <td class="right" style="font-weight: 700; color: #0f172a;">RM {{ number_format($booking->subtotal, 2) }}</td>
            </tr>
            @foreach($booking->addons as $addon)
            <tr>
                <td>
                    <div class="item-name">{{ $addon->name }}</div>
                </td>
                <td class="right" style="color: #64748b;">{{ $addon->pivot->quantity }}</td>
                <td class="right">RM {{ number_format($addon->pivot->price_at_booking, 2) }}</td>
                <td class="right" style="font-weight: 700; color: #0f172a;">RM {{ number_format($addon->pivot->price_at_booking * $addon->pivot->quantity, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-container">
        <table class="totals-table">
            @if($booking->discount_amount > 0)
            <tr class="discount">
                <td class="label">Discount</td>
                <td class="value">−RM {{ number_format($booking->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>Total Amount</td>
                <td style="text-align: right;">RM {{ number_format($booking->total_price, 2) }}</td>
            </tr>
            <tr class="deposit">
                <td class="label">Deposit Required</td>
                <td class="value">RM {{ number_format($booking->deposit_amount, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Deposit Paid</td>
                <td class="value">RM {{ number_format($booking->deposit_paid ?? 0, 2) }}</td>
            </tr>
            <tr style="border-top: 1px solid #e2e8f0;">
                <td class="label" style="padding-top: 10px; font-weight: 700; color: #0f172a;">Balance Due</td>
                <td class="value" style="padding-top: 10px; font-weight: 700; color: #ea580c; font-size: 13px;">RM {{ number_format(max(0, $booking->total_price - ($booking->deposit_paid ?? 0)), 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        Generated by Savanna Hill Event Management System · {{ now()->format('Y') }}<br>
        Booking status: {{ ucfirst($booking->status) }}
    </div>
</div>
</body>
</html>
