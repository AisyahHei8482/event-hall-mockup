<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Review your event quotation from Savanna Hill Event Hall">
    <title>Quotation {{ $quotation->quote_number }} — Savanna Hill Event Hall</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #fdf4e7 0%, #fff7ed 50%, #fef9f0 100%);
            min-height: 100vh;
            color: #1e293b;
        }
        .container { max-width: 760px; margin: 0 auto; padding: 2rem 1.25rem 4rem; }
        .badge {
            display: inline-flex; align-items: center; gap: .375rem;
            padding: .3rem .75rem; border-radius: 999px; font-size: .7rem;
            font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
        }
        .badge-sent     { background: #dbeafe; color: #1d4ed8; }
        .badge-viewed   { background: #f0fdf4; color: #15803d; }
        .badge-accepted { background: #dcfce7; color: #16a34a; }
        .badge-rejected { background: #fee2e2; color: #dc2626; }
        .badge-expired  { background: #f1f5f9; color: #64748b; }
        .badge-draft    { background: #fef9c3; color: #854d0e; }
        .card {
            background: #fff; border-radius: 1.25rem;
            border: 1px solid #f1f5f9; box-shadow: 0 2px 16px rgba(0,0,0,.06);
            padding: 2rem; margin-bottom: 1.5rem;
        }
        .section-label {
            font-size: .65rem; font-weight: 800; letter-spacing: .12em;
            text-transform: uppercase; color: #94a3b8; margin-bottom: .75rem;
        }
        .detail-row { display: flex; justify-content: space-between; align-items: center; padding: .6rem 0; border-bottom: 1px solid #f8fafc; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: .8rem; font-weight: 600; color: #64748b; }
        .detail-value { font-size: .9rem; font-weight: 700; color: #0f172a; }
        .field-group { margin-bottom: 1.25rem; }
        .field-label { display: block; font-size: .7rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; color: #64748b; margin-bottom: .5rem; }
        .field-input {
            width: 100%; padding: .75rem 1rem; border: 1.5px solid #e2e8f0;
            border-radius: .75rem; font-size: .875rem; font-family: 'Inter', sans-serif;
            font-weight: 500; color: #0f172a; background: #f8fafc;
            transition: border-color .2s, box-shadow .2s; outline: none;
        }
        .field-input:focus { border-color: #f97316; box-shadow: 0 0 0 3px rgba(249,115,22,.12); background: #fff; }
        textarea.field-input { resize: vertical; min-height: 80px; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        @media (max-width: 520px) { .grid-2 { grid-template-columns: 1fr; } }
        .price-row { display: flex; justify-content: space-between; padding: .5rem 0; font-size: .875rem; }
        .price-row.total { font-weight: 800; font-size: 1.1rem; color: #f97316; border-top: 2px solid #f97316; margin-top: .5rem; padding-top: 1rem; }
        .item-row { display: flex; justify-content: space-between; align-items: center; padding: .625rem 0; border-bottom: 1px solid #f1f5f9; font-size: .85rem; }
        .item-row:last-child { border-bottom: none; }
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
            padding: .75rem 1.75rem; border-radius: .875rem; font-size: .8rem;
            font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
            border: none; cursor: pointer; transition: all .2s;
        }
        .btn-primary { background: #f97316; color: #fff; box-shadow: 0 4px 14px rgba(249,115,22,.3); }
        .btn-primary:hover { background: #ea6c0b; transform: translateY(-1px); }
        .btn-success { background: #22c55e; color: #fff; box-shadow: 0 4px 14px rgba(34,197,94,.25); }
        .btn-success:hover { background: #16a34a; transform: translateY(-1px); }
        .btn-danger  { background: #fff; color: #ef4444; border: 1.5px solid #fca5a5; }
        .btn-danger:hover  { background: #fef2f2; }
        .btn-ghost   { background: #f1f5f9; color: #475569; }
        .btn-ghost:hover   { background: #e2e8f0; }
        .alert { padding: 1rem 1.25rem; border-radius: .875rem; margin-bottom: 1.5rem; font-size: .875rem; font-weight: 600; display: flex; align-items: center; gap: .75rem; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert-error   { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-info    { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
        .status-banner { background: linear-gradient(135deg, #f97316, #fb923c); color: #fff; padding: 1.25rem 2rem; border-radius: 1rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 1rem; }
        .logo-area { text-align: center; padding: 2rem 0 1.5rem; }
        .logo-icon { width: 60px; height: 60px; background: linear-gradient(135deg, #f97316, #fb923c); border-radius: 1rem; display: inline-flex; align-items: center; justify-content: center; margin-bottom: .75rem; box-shadow: 0 6px 20px rgba(249,115,22,.35); }
        .expired-banner { background: #fef2f2; border: 1.5px solid #fecaca; border-radius: 1rem; padding: 1.5rem; text-align: center; color: #dc2626; }
        .accepted-banner { background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 1rem; padding: 1.5rem; text-align: center; color: #15803d; }
    </style>
</head>
<body x-data="{ view: 'review', rejectReason: '' }">
<div class="container">

    {{-- Logo / Header --}}
    <div class="logo-area">
        <div class="logo-icon">
            <i class="fa-solid fa-building" style="color:#fff;font-size:1.5rem;"></i>
        </div>
        <h1 style="font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:.25rem;">Savanna Hill Event Hall</h1>
        <p style="font-size:.875rem;color:#64748b;font-weight:500;">Your Quotation for Review</p>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="alert alert-error"><i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif

    {{-- Quote Header Card --}}
    <div class="card" style="background: linear-gradient(135deg, #fff7ed, #fff); border-color: #fed7aa;">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem;">
            <div>
                <div class="section-label">Quotation Reference</div>
                <div style="font-size:1.5rem;font-weight:900;color:#f97316;font-family:monospace;letter-spacing:.05em;">{{ $quotation->quote_number }}</div>
                <div style="font-size:.8rem;color:#64748b;margin-top:.25rem;font-weight:500;">Prepared for: <strong style="color:#0f172a;">{{ $quotation->guest_name }}</strong></div>
            </div>
            <div style="text-align:right;">
                @php
                    $statusClass = match($quotation->status) {
                        'accepted'  => 'badge-accepted',
                        'rejected'  => 'badge-rejected',
                        'viewed','sent' => 'badge-viewed',
                        'expired'   => 'badge-expired',
                        default     => 'badge-draft',
                    };
                @endphp
                <span class="badge {{ $statusClass }}">
                    <i class="fa-solid fa-circle" style="font-size:.45rem;"></i> {{ ucfirst($quotation->status) }}
                </span>
                @if($quotation->valid_until)
                <div style="font-size:.75rem;color:#94a3b8;margin-top:.5rem;font-weight:500;">Valid until <strong>{{ $quotation->valid_until->format('d M Y') }}</strong></div>
                @endif
            </div>
        </div>
    </div>

    {{-- Accepted State --}}
    @if($quotation->status === 'accepted')
    <div class="accepted-banner">
        <i class="fa-solid fa-circle-check" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
        <div style="font-size:1rem;font-weight:700;">Quotation Accepted!</div>
        <div style="font-size:.875rem;margin-top:.25rem;color:#166534;">Thank you! Our team will contact you within 24 hours to confirm the booking details.</div>
    </div>
    @elseif($quotation->status === 'rejected')
    <div class="expired-banner">
        <i class="fa-solid fa-circle-xmark" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
        <div style="font-size:1rem;font-weight:700;">Quotation Rejected</div>
        <div style="font-size:.875rem;margin-top:.25rem;">Your response has been recorded. Please contact us to discuss your requirements further.</div>
    </div>
    @elseif($quotation->isExpired())
    <div class="expired-banner">
        <i class="fa-solid fa-clock" style="font-size:2rem;margin-bottom:.5rem;display:block;"></i>
        <div style="font-size:1rem;font-weight:700;">Quotation Expired</div>
        <div style="font-size:.875rem;margin-top:.25rem;">This quotation has expired. Please contact us to request a new quotation.</div>
    </div>
    @endif

    {{-- Hall Details --}}
    @if($quotation->eventHall)
    <div class="card">
        <div class="section-label">Event Hall</div>
        @if($quotation->eventHall->cover_image)
        <img src="{{ asset('storage/'.$quotation->eventHall->cover_image) }}" alt="{{ $quotation->eventHall->name }}"
             style="width:100%;height:180px;object-fit:cover;border-radius:.75rem;margin-bottom:1rem;">
        @endif
        <div style="font-size:1.1rem;font-weight:800;color:#0f172a;">{{ $quotation->eventHall->name }}</div>
        @if($quotation->eventHall->description)
        <p style="font-size:.85rem;color:#64748b;margin-top:.375rem;line-height:1.6;">{{ $quotation->eventHall->description }}</p>
        @endif
        <div style="display:flex;gap:1.5rem;margin-top:.875rem;flex-wrap:wrap;">
            @if($quotation->eventHall->capacity)
            <div style="font-size:.8rem;font-weight:600;color:#475569;"><i class="fa-solid fa-users" style="color:#f97316;margin-right:.3rem;"></i> Up to {{ $quotation->eventHall->capacity }} pax</div>
            @endif
            @if($quotation->eventHall->floor_area)
            <div style="font-size:.8rem;font-weight:600;color:#475569;"><i class="fa-solid fa-vector-square" style="color:#f97316;margin-right:.3rem;"></i> {{ $quotation->eventHall->floor_area }} m²</div>
            @endif
        </div>
    </div>
    @endif

    {{-- Edit form (only if quotation can be modified) --}}
    @php $canEdit = in_array($quotation->status, ['sent', 'viewed', 'draft', 'generated']) && !$quotation->isExpired(); @endphp

    @if($canEdit)
    <div class="card" style="border-color: #fed7aa;">
        <div class="section-label" style="color:#f97316;">Your Event Details</div>
        <div class="alert alert-info" style="margin-bottom:1.5rem;">
            <i class="fa-solid fa-circle-info"></i>
            Please review and update your event details below. You can save changes, accept, or reject this quotation.
        </div>

        <form method="POST" action="{{ route('customer.quotation.submit', $quotation->customer_token) }}" x-ref="mainForm">
            @csrf
            <input type="hidden" name="action" x-bind:value="view === 'reject' ? 'reject' : (view === 'accept' ? 'accept' : 'save')" id="actionInput">

            <div class="grid-2">
                <div class="field-group">
                    <label class="field-label">Your Name *</label>
                    <input class="field-input" type="text" name="guest_name" value="{{ old('guest_name', $quotation->guest_name) }}" required>
                </div>
                <div class="field-group">
                    <label class="field-label">Phone Number</label>
                    <input class="field-input" type="tel" name="guest_phone" value="{{ old('guest_phone', $quotation->guest_phone) }}" placeholder="+60 12 345 6789">
                </div>
                <div class="field-group">
                    <label class="field-label">Event Date *</label>
                    <input class="field-input" type="date" name="event_date" value="{{ old('event_date', $quotation->event_date?->format('Y-m-d')) }}" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="field-group">
                    <label class="field-label">Number of Guests *</label>
                    <input class="field-input" type="number" name="guests" value="{{ old('guests', $quotation->guests) }}" required min="1">
                </div>
                <div class="field-group">
                    <label class="field-label">Start Time *</label>
                    <input class="field-input" type="time" name="start_time" value="{{ old('start_time', $quotation->start_time) }}" required>
                </div>
                <div class="field-group">
                    <label class="field-label">End Time *</label>
                    <input class="field-input" type="time" name="end_time" value="{{ old('end_time', $quotation->end_time) }}" required>
                </div>
            </div>
            <div class="field-group">
                <label class="field-label">Event Type</label>
                <input class="field-input" type="text" name="event_type" value="{{ old('event_type', $quotation->event_type) }}" placeholder="e.g. Wedding, Corporate Dinner, Birthday">
            </div>
            <div class="field-group">
                <label class="field-label">Special Requirements / Notes</label>
                <textarea class="field-input" name="requirements" placeholder="Any special arrangements, dietary requirements, décor preferences...">{{ old('requirements', $quotation->requirements) }}</textarea>
            </div>
            <div class="field-group">
                <label class="field-label">Message to Our Team <span style="color:#94a3b8;font-weight:400;text-transform:none;">(optional)</span></label>
                <textarea class="field-input" name="customer_notes" placeholder="Ask questions or share additional notes for our team...">{{ old('customer_notes', $quotation->customer_notes) }}</textarea>
            </div>

            {{-- Reject reason (shown conditionally) --}}
            <div x-show="view === 'reject'" style="margin-bottom:1rem;">
                <div class="field-group">
                    <label class="field-label" style="color:#ef4444;">Reason for Rejection (optional)</label>
                    <textarea class="field-input" name="rejection_reason" x-model="rejectReason" placeholder="Let us know why, so we can improve..." style="border-color:#fca5a5;"></textarea>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div x-show="view === 'review'" style="display:flex;gap:.75rem;flex-wrap:wrap;padding-top:1rem;border-top:1px solid #f1f5f9;">
                <button type="submit" class="btn btn-primary" @click="view='save'">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
                <button type="button" class="btn btn-success" @click="view='accept'">
                    <i class="fa-solid fa-circle-check"></i> Accept Quotation
                </button>
                <button type="button" class="btn btn-danger" @click="view='reject'">
                    <i class="fa-solid fa-circle-xmark"></i> Reject
                </button>
            </div>

            {{-- Accept confirmation --}}
            <div x-show="view === 'accept'" style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:.875rem;padding:1.25rem;margin-top:.5rem;">
                <div style="font-weight:700;color:#15803d;margin-bottom:.5rem;"><i class="fa-solid fa-circle-check"></i> Confirm Acceptance</div>
                <p style="font-size:.875rem;color:#166534;margin-bottom:1rem;">By accepting, you agree to the pricing and details above. Our team will contact you to confirm and arrange the deposit payment.</p>
                <div style="display:flex;gap:.75rem;">
                    <button type="submit" class="btn btn-success"><i class="fa-solid fa-circle-check"></i> Yes, Accept Quotation</button>
                    <button type="button" class="btn btn-ghost" @click="view='review'">Cancel</button>
                </div>
            </div>

            {{-- Reject confirmation --}}
            <div x-show="view === 'reject'" style="background:#fef2f2;border:1.5px solid #fecaca;border-radius:.875rem;padding:1.25rem;margin-top:.5rem;">
                <div style="font-weight:700;color:#dc2626;margin-bottom:.5rem;"><i class="fa-solid fa-circle-xmark"></i> Confirm Rejection</div>
                <p style="font-size:.875rem;color:#991b1b;margin-bottom:1rem;">Are you sure you want to reject this quotation? You can always contact us to discuss alternatives.</p>
                <div style="display:flex;gap:.75rem;">
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-circle-xmark"></i> Yes, Reject</button>
                    <button type="button" class="btn btn-ghost" @click="view='review'">Cancel</button>
                </div>
            </div>
        </form>
    </div>
    @else
    {{-- Read-only event details --}}
    <div class="card">
        <div class="section-label">Event Details</div>
        <div class="detail-row"><span class="detail-label">Event Date</span><span class="detail-value">{{ $quotation->event_date?->format('d M Y') ?: '—' }}</span></div>
        <div class="detail-row"><span class="detail-label">Time</span><span class="detail-value">{{ $quotation->start_time ?: '—' }} – {{ $quotation->end_time ?: '—' }}</span></div>
        <div class="detail-row"><span class="detail-label">Guests</span><span class="detail-value">{{ $quotation->guests }}</span></div>
        <div class="detail-row"><span class="detail-label">Event Type</span><span class="detail-value">{{ $quotation->event_type ?: '—' }}</span></div>
        @if($quotation->requirements)
        <div style="padding-top:.75rem;">
            <div class="section-label">Requirements</div>
            <p style="font-size:.875rem;color:#475569;line-height:1.6;">{{ $quotation->requirements }}</p>
        </div>
        @endif
    </div>
    @endif

    {{-- Quotation Items --}}
    @if($quotation->items->count() > 0)
    <div class="card">
        <div class="section-label">Quotation Breakdown</div>
        @foreach($quotation->items as $item)
        <div class="item-row">
            <div>
                <div style="font-weight:600;color:#0f172a;">{{ $item->description }}</div>
                <div style="font-size:.75rem;color:#94a3b8;">{{ $item->quantity }} × RM {{ number_format($item->unit_price, 2) }} / {{ $item->unit ?: 'unit' }}</div>
            </div>
            <div style="font-weight:700;color:#0f172a;">RM {{ number_format($item->total, 2) }}</div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- Pricing Summary --}}
    <div class="card">
        <div class="section-label">Pricing Summary</div>
        <div class="price-row"><span style="color:#475569;">Hall Rental (Subtotal)</span><span style="font-weight:700;">RM {{ number_format($quotation->subtotal, 2) }}</span></div>
        @if($quotation->addons_total > 0)
        <div class="price-row"><span style="color:#475569;">Add-ons</span><span style="font-weight:700;">RM {{ number_format($quotation->addons_total, 2) }}</span></div>
        @endif
        @if($quotation->discount_amount > 0)
        <div class="price-row"><span style="color:#16a34a;">Discount {{ $quotation->discount_reason ? "($quotation->discount_reason)" : '' }}</span><span style="font-weight:700;color:#16a34a;">− RM {{ number_format($quotation->discount_amount, 2) }}</span></div>
        @endif
        @if($quotation->service_charge_amount > 0)
        <div class="price-row"><span style="color:#475569;">Service Charge ({{ $quotation->service_charge_rate }}%)</span><span style="font-weight:700;">RM {{ number_format($quotation->service_charge_amount, 2) }}</span></div>
        @endif
        @if($quotation->tax_amount > 0)
        <div class="price-row"><span style="color:#475569;">Tax ({{ $quotation->tax_rate }}%)</span><span style="font-weight:700;">RM {{ number_format($quotation->tax_amount, 2) }}</span></div>
        @endif
        <div class="price-row total">
            <span>Total Amount</span><span>RM {{ number_format($quotation->total_amount, 2) }}</span>
        </div>
        @if($quotation->deposit_required > 0)
        <div class="price-row" style="margin-top:.5rem;padding-top:.5rem;">
            <span style="color:#f97316;font-weight:600;">Deposit Required</span>
            <span style="font-weight:800;color:#f97316;">RM {{ number_format($quotation->deposit_required, 2) }}</span>
        </div>
        <div class="price-row">
            <span style="color:#475569;">Balance Due</span>
            <span style="font-weight:700;">RM {{ number_format($quotation->balance_due, 2) }}</span>
        </div>
        @endif
    </div>

    {{-- Terms --}}
    @if($quotation->terms_conditions)
    <div class="card" style="background:#f8fafc;">
        <div class="section-label">Terms & Conditions</div>
        <div style="font-size:.8rem;color:#475569;line-height:1.7;white-space:pre-line;">{{ $quotation->terms_conditions }}</div>
    </div>
    @endif

    {{-- Footer --}}
    <div style="text-align:center;margin-top:2rem;color:#94a3b8;font-size:.8rem;font-weight:500;">
        <p>Questions? Contact us at <strong style="color:#f97316;">info@savannahill.com</strong></p>
        <p style="margin-top:.25rem;">Savanna Hill Event Hall · All rights reserved</p>
    </div>

</div>
</body>
</html>
