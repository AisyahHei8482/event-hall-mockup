@extends('layouts.management')
@section('title', $quotation->quote_number)
@section('page_title', 'Quotation ' . $quotation->quote_number)

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    <!-- Main Quote Details -->
    <div class="xl:col-span-2 space-y-6">

        <!-- Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                <div>
                    <div class="text-xs text-slate-400 mb-1">Quote Number</div>
                    <div class="font-mono text-2xl font-bold text-slate-900">{{ $quotation->quote_number }}</div>
                </div>
                @php
                $badge = match($quotation->status) {
                    'generated' => 'bg-blue-100 text-blue-700 border-blue-200',
                    'accepted'  => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                    'rejected'  => 'bg-red-100 text-red-700 border-red-200',
                    'converted' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'expired'   => 'bg-slate-100 text-slate-500 border-slate-200',
                    default     => 'bg-amber-100 text-amber-700 border-amber-200',
                };
                @endphp
                <span class="text-sm font-semibold px-4 py-2 rounded-xl border {{ $badge }}">{{ ucfirst($quotation->status) }}</span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 text-sm">
                <div><div class="text-slate-400 text-xs mb-0.5">Guest</div><div class="font-medium">{{ $quotation->guest_name }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Email</div><div class="font-medium">{{ $quotation->guest_email }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Phone</div><div class="font-medium">{{ $quotation->guest_phone ?: '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Guests</div><div class="font-medium">{{ $quotation->guests }} pax</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Hall</div><div class="font-medium">{{ $quotation->eventHall->name ?? '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Franchise</div><div class="font-medium">{{ $quotation->eventHall->franchise->name ?? '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Event Date</div><div class="font-medium">{{ $quotation->event_date?->format('d M Y') ?: '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Time</div><div class="font-medium font-mono">{{ $quotation->start_time }} – {{ $quotation->end_time }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Event Type</div><div class="font-medium">{{ $quotation->event_type ?: '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Duration</div><div class="font-medium">{{ $quotation->duration_hours }} hrs</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Valid Until</div><div class="font-medium {{ $quotation->isExpired() ? 'text-red-500' : '' }}">{{ $quotation->valid_until?->format('d M Y') ?: '—' }}</div></div>
                <div><div class="text-slate-400 text-xs mb-0.5">Created</div><div class="font-medium">{{ $quotation->created_at->format('d M Y H:i') }}</div></div>
            </div>
        </div>

        <!-- Line Items -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-900">Pricing Breakdown</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-5 py-3 text-slate-500">Description</th>
                        <th class="text-center px-4 py-3 text-slate-500">Qty</th>
                        <th class="text-right px-4 py-3 text-slate-500">Unit Price</th>
                        <th class="text-right px-5 py-3 text-slate-500">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($quotation->items as $item)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="font-medium">{{ $item->description }}</div>
                            @if($item->unit)<div class="text-xs text-slate-400">per {{ $item->unit }}</div>@endif
                        </td>
                        <td class="px-4 py-3 text-center">{{ $item->quantity }}</td>
                        <td class="px-4 py-3 text-right">RM {{ number_format($item->unit_price,2) }}</td>
                        <td class="px-5 py-3 text-right font-semibold">RM {{ number_format($item->total,2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-slate-200">
                    <tr><td colspan="3" class="px-5 py-2.5 text-right text-slate-500">Subtotal</td><td class="px-5 py-2.5 text-right font-medium">RM {{ number_format($quotation->subtotal + $quotation->addons_total,2) }}</td></tr>
                    @if($quotation->discount_amount > 0)
                    <tr><td colspan="3" class="px-5 py-2 text-right text-green-600">Discount{{ $quotation->discount_reason ? ' ('.$quotation->discount_reason.')' : '' }}</td><td class="px-5 py-2 text-right text-green-600">−RM {{ number_format($quotation->discount_amount,2) }}</td></tr>
                    @endif
                    @if($quotation->tax_amount > 0)
                    <tr><td colspan="3" class="px-5 py-2 text-right text-slate-500">Tax ({{ $quotation->tax_rate }}%)</td><td class="px-5 py-2 text-right">RM {{ number_format($quotation->tax_amount,2) }}</td></tr>
                    @endif
                    @if($quotation->service_charge_amount > 0)
                    <tr><td colspan="3" class="px-5 py-2 text-right text-slate-500">Service Charge ({{ $quotation->service_charge_rate }}%)</td><td class="px-5 py-2 text-right">RM {{ number_format($quotation->service_charge_amount,2) }}</td></tr>
                    @endif
                    <tr class="bg-slate-50"><td colspan="3" class="px-5 py-3 text-right font-bold text-slate-900">Total</td><td class="px-5 py-3 text-right font-bold text-xl text-brand-600">RM {{ number_format($quotation->total_amount,2) }}</td></tr>
                    <tr><td colspan="3" class="px-5 py-2 text-right text-slate-500">Deposit Required</td><td class="px-5 py-2 text-right font-semibold text-amber-600">RM {{ number_format($quotation->deposit_required,2) }}</td></tr>
                    <tr><td colspan="3" class="px-5 py-2 text-right text-slate-500">Balance Due</td><td class="px-5 py-2 text-right font-semibold">RM {{ number_format($quotation->balance_due,2) }}</td></tr>
                </tfoot>
            </table>
        </div>

        @if($quotation->requirements)
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h3 class="font-semibold text-slate-900 mb-3">Requirements</h3>
            <p class="text-sm text-slate-600">{{ $quotation->requirements }}</p>
        </div>
        @endif
    </div>

    <!-- Sidebar Actions -->
    <div class="space-y-4">

        <!-- Actions -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-semibold text-slate-900 mb-4">Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('management.quotations.pdf', $quotation) }}"
                   class="flex items-center gap-2 w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm transition-colors">
                    <i class="fa-solid fa-file-pdf text-red-500"></i> Download PDF
                </a>
                @if(in_array($quotation->status, ['generated','draft','viewed']))
                <form method="POST" action="{{ route('management.quotations.send', $quotation) }}">
                    @csrf
                    <button class="flex items-center gap-2 w-full px-4 py-2.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-xl text-sm transition-colors">
                        <i class="fa-solid fa-paper-plane"></i> Mark as Sent
                    </button>
                </form>
                @endif
                @if(in_array($quotation->status, ['generated','sent','viewed']))
                <form method="POST" action="{{ route('management.quotations.accept', $quotation) }}">
                    @csrf
                    <button class="flex items-center gap-2 w-full px-4 py-2.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-xl text-sm transition-colors">
                        <i class="fa-solid fa-check-circle"></i> Accept (on behalf)
                    </button>
                </form>
                <form method="POST" action="{{ route('management.quotations.reject', $quotation) }}">
                    @csrf
                    <button class="flex items-center gap-2 w-full px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-sm transition-colors">
                        <i class="fa-solid fa-xmark-circle"></i> Reject
                    </button>
                </form>
                @endif
                @if($quotation->canBeConverted())
                <form method="POST" action="{{ route('management.quotations.convert', $quotation) }}">
                    @csrf
                    <button class="flex items-center gap-2 w-full px-4 py-2.5 bg-purple-500 hover:bg-purple-600 text-white rounded-xl text-sm font-medium transition-colors">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i> Convert to Booking
                    </button>
                </form>
                @endif
                @if($quotation->booking)
                <a href="{{ route('management.bookings.show', $quotation->booking) }}"
                   class="flex items-center gap-2 w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm transition-colors">
                    <i class="fa-solid fa-calendar-check text-brand-500"></i> View Booking
                </a>
                @endif
                <a href="{{ route('management.quotations.edit', $quotation) }}"
                   class="flex items-center gap-2 w-full px-4 py-2.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-sm transition-colors">
                    <i class="fa-solid fa-pen text-slate-500"></i> Edit / Override
                </a>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <h3 class="font-semibold text-slate-900 mb-4">Timeline</h3>
            <div class="space-y-3 text-sm">
                <div class="flex gap-3"><div class="w-2 h-2 bg-brand-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Created</div><div class="text-xs text-slate-400">{{ $quotation->created_at->format('d M Y H:i') }}</div></div></div>
                @if($quotation->sent_at)<div class="flex gap-3"><div class="w-2 h-2 bg-blue-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Sent</div><div class="text-xs text-slate-400">{{ $quotation->sent_at->format('d M Y H:i') }}</div></div></div>@endif
                @if($quotation->viewed_at)<div class="flex gap-3"><div class="w-2 h-2 bg-indigo-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Viewed</div><div class="text-xs text-slate-400">{{ $quotation->viewed_at->format('d M Y H:i') }}</div></div></div>@endif
                @if($quotation->accepted_at)<div class="flex gap-3"><div class="w-2 h-2 bg-emerald-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Accepted</div><div class="text-xs text-slate-400">{{ $quotation->accepted_at->format('d M Y H:i') }}</div></div></div>@endif
                @if($quotation->rejected_at)<div class="flex gap-3"><div class="w-2 h-2 bg-red-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Rejected</div><div class="text-xs text-slate-400">{{ $quotation->rejected_at->format('d M Y H:i') }}</div></div></div>@endif
                @if($quotation->converted_at)<div class="flex gap-3"><div class="w-2 h-2 bg-purple-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">Converted to Booking</div><div class="text-xs text-slate-400">{{ $quotation->converted_at->format('d M Y H:i') }}</div></div></div>@endif
                
                @foreach($quotation->activityLogs()->orderBy('created_at', 'asc')->get() as $log)
                <div class="flex gap-3"><div class="w-2 h-2 bg-slate-400 rounded-full mt-1.5 shrink-0"></div><div><div class="font-medium">{{ ucwords(str_replace('_', ' ', $log->action)) }}</div><div class="text-xs text-slate-400">{{ $log->created_at->format('d M Y H:i') }} — {{ $log->description }}</div></div></div>
                @endforeach
            </div>
        </div>

        @if($quotation->internal_notes)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5">
            <h3 class="font-semibold text-amber-900 mb-2"><i class="fa-solid fa-note-sticky mr-1"></i> Internal Notes</h3>
            <p class="text-sm text-amber-800">{{ $quotation->internal_notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
