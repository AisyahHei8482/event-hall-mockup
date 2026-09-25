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
    .terms { background: #f8fafc; border-radius: 6px; padding: 15px; font-size: 10px; color: #475569; margin-bottom: 20px; }
    .terms h4 { font-size: 10px; font-weight: 700; color: #0f172a; margin-bottom: 6px; text-transform: uppercase; }
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
                    <div class="brand-title">{{ $quotation->eventHall->franchise->name ?? 'Savanna Hill Event Halls' }}</div>
                    <div class="brand-subtitle">{{ $quotation->eventHall->franchise->address ?? 'Johor Bahru, Malaysia' }}</div>
                    <div class="brand-subtitle">{{ $quotation->eventHall->franchise->phone ?? '+60 12 345 6789' }} · {{ $quotation->eventHall->franchise->email ?? 'events@savannahill.local' }}</div>
                </td>
                <td style="width: 50%; text-align: right;">
                    <div class="doc-title">Quotation</div>
                    <div class="doc-meta">#{{ $quotation->quote_number }}</div>
                    <div class="doc-meta" style="margin-top: 4px; color: #94a3b8;">Issued: {{ $quotation->created_at->format('d M Y') }}</div>
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
                        <div class="info-row"><span class="info-label">Name</span><span class="info-value">{{ $quotation->guest_name }}</span></div>
                        <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $quotation->guest_email }}</span></div>
                        @if($quotation->guest_phone)<div class="info-row"><span class="info-label">Phone</span><span class="info-value">{{ $quotation->guest_phone }}</span></div>@endif
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="info-card last">
                        <div class="info-title">Event Summary</div>
                        <div class="info-row"><span class="info-label">Venue</span><span class="info-value">{{ $quotation->eventHall->name ?? '—' }}</span></div>
                        <div class="info-row"><span class="info-label">Date</span><span class="info-value">{{ $quotation->event_date?->format('l, d M Y') }}</span></div>
                        <div class="info-row"><span class="info-label">Time</span><span class="info-value">{{ $quotation->start_time }} – {{ $quotation->end_time }}</span></div>
                        <div class="info-row"><span class="info-label">Guests</span><span class="info-value">{{ $quotation->guests }} pax</span></div>
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
            @foreach($quotation->items as $item)
            <tr>
                <td>
                    <div class="item-name">{{ $item->description }}</div>
                    @if($item->unit)<div class="item-unit">per {{ $item->unit }}</div>@endif
                </td>
                <td class="right" style="color: #64748b;">{{ $item->quantity }}</td>
                <td class="right">RM {{ number_format($item->unit_price, 2) }}</td>
                <td class="right" style="font-weight: 700; color: #0f172a;">RM {{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-container">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal</td>
                <td class="value">RM {{ number_format($quotation->subtotal + $quotation->addons_total, 2) }}</td>
            </tr>
            @if($quotation->discount_amount > 0)
            <tr class="discount">
                <td class="label">Discount {{ $quotation->discount_reason ? '('.$quotation->discount_reason.')' : '' }}</td>
                <td class="value">−RM {{ number_format($quotation->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($quotation->tax_amount > 0)
            <tr>
                <td class="label">Tax ({{ $quotation->tax_rate }}%)</td>
                <td class="value">RM {{ number_format($quotation->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($quotation->service_charge_amount > 0)
            <tr>
                <td class="label">Service Charge ({{ $quotation->service_charge_rate }}%)</td>
                <td class="value">RM {{ number_format($quotation->service_charge_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <td>Total Amount</td>
                <td style="text-align: right;">RM {{ number_format($quotation->total_amount, 2) }}</td>
            </tr>
            <tr class="deposit">
                <td class="label">Required Deposit</td>
                <td class="value">RM {{ number_format($quotation->deposit_required, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Terms -->
    @if($quotation->terms_conditions || $quotation->valid_until)
    <div class="terms">
        @if($quotation->valid_until)
        <div style="margin-bottom: 10px; font-weight: 700; color: #ea580c;">
            This quotation is valid until {{ $quotation->valid_until->format('l, d M Y') }}.
        </div>
        @endif
        
        @if($quotation->terms_conditions)
        <h4>Terms & Conditions</h4>
        <div>{!! nl2br(e($quotation->terms_conditions)) !!}</div>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        Generated by Savanna Hill Event Management System · {{ now()->format('Y') }}
    </div>
</div>
</body>
</html>
