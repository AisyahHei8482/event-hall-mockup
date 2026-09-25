@extends('layouts.eventhall-demo')

@section('title', 'Quotation ' . $quotation->quote_number . ' — Savanna Hill Event Halls')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-5">
            <a href="{{ route('event-halls.demo.home') }}" class="text-brand-600 text-sm hover:text-brand-700 font-medium mb-4 inline-flex items-center gap-2 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Back to Event Halls
            </a>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mb-1 tracking-tight">Your Quotation</h1>
                    <div class="text-slate-500 font-medium">Ref: <span class="font-mono text-slate-700 bg-slate-200 px-2 py-0.5 rounded-md text-sm ml-1">{{ $quotation->quote_number }}</span></div>
                </div>
                <div>
                    @php
                    $badgeTheme = match($quotation->status) {
                        'generated' => 'blue',
                        'accepted'  => 'emerald',
                        'rejected'  => 'red',
                        'converted' => 'purple',
                        'expired'   => 'slate',
                        default     => 'slate',
                    };
                    @endphp
                    <x-badge variant="{{ $badgeTheme }}" class="px-4 py-2 text-sm shadow-sm capitalize">
                        {{ $quotation->status }}
                    </x-badge>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden mb-5">
            <!-- Details Grid -->
            <div class="p-5 md:p-6 border-b border-slate-100 bg-slate-50/50">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-calendar-day text-slate-300"></i> Event Details</h3>
                        <div class="grid grid-cols-3 gap-y-2 gap-x-4 text-sm">
                            <div class="text-slate-500 font-medium">Venue</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->eventHall->name ?? '—' }}</div>
                            <div class="text-slate-500 font-medium">Date</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->event_date?->format('l, d M Y') }}</div>
                            <div class="text-slate-500 font-medium">Time</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->start_time }} – {{ $quotation->end_time }} ({{ $quotation->duration_hours }} hrs)</div>
                            <div class="text-slate-500 font-medium">Guests</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->guests }} pax</div>
                            @if($quotation->event_type)
                            <div class="text-slate-500 font-medium">Type</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->event_type }}</div>
                            @endif
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 flex items-center gap-2"><i class="fa-solid fa-user text-slate-300"></i> Guest Details</h3>
                        <div class="grid grid-cols-3 gap-y-2 gap-x-4 text-sm">
                            <div class="text-slate-500 font-medium">Name</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->guest_name }}</div>
                            <div class="text-slate-500 font-medium">Email</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->guest_email }}</div>
                            @if($quotation->guest_phone)
                            <div class="text-slate-500 font-medium">Phone</div><div class="col-span-2 font-bold text-slate-900">{{ $quotation->guest_phone }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="p-5 md:p-6">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2"><i class="fa-solid fa-list text-slate-300"></i> Price Breakdown</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b-2 border-slate-100">
                                <th class="text-left pb-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Description</th>
                                <th class="text-center pb-4 font-bold text-slate-900 uppercase tracking-wide text-xs px-4">Qty</th>
                                <th class="text-right pb-4 font-bold text-slate-900 uppercase tracking-wide text-xs px-4">Unit Price</th>
                                <th class="text-right pb-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($quotation->items as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3">
                                    <div class="font-bold text-slate-900 text-base">{{ $item->description }}</div>
                                    @if($item->unit)<div class="text-xs font-medium text-slate-500 uppercase tracking-wider mt-1">per {{ $item->unit }}</div>@endif
                                </td>
                                <td class="py-3 text-center font-medium text-slate-600 px-4">x{{ $item->quantity }}</td>
                                <td class="py-3 text-right font-medium text-slate-600 px-4">RM {{ number_format($item->unit_price, 2) }}</td>
                                <td class="py-3 text-right font-bold text-slate-900 text-base">RM {{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 border-t border-slate-200 pt-8">
                    <div class="w-full md:w-1/2 ml-auto space-y-4 text-sm">
                        <div class="flex justify-between text-slate-500 font-medium pb-2 border-b border-slate-100">
                            <span>Subtotal</span>
                            <span class="text-slate-900">RM {{ number_format($quotation->subtotal + $quotation->addons_total, 2) }}</span>
                        </div>
                        @if($quotation->discount_amount > 0)
                        <div class="flex justify-between text-emerald-600 font-medium pb-2 border-b border-slate-100">
                            <span>Discount {{ $quotation->discount_reason ? '('.$quotation->discount_reason.')' : '' }}</span>
                            <span>−RM {{ number_format($quotation->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($quotation->tax_amount > 0)
                        <div class="flex justify-between text-slate-500 font-medium pb-2 border-b border-slate-100">
                            <span>Tax ({{ $quotation->tax_rate }}%)</span>
                            <span class="text-slate-900">RM {{ number_format($quotation->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($quotation->service_charge_amount > 0)
                        <div class="flex justify-between text-slate-500 font-medium pb-2 border-b border-slate-100">
                            <span>Service Charge ({{ $quotation->service_charge_rate }}%)</span>
                            <span class="text-slate-900">RM {{ number_format($quotation->service_charge_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between items-center pt-4">
                            <span class="text-slate-900 font-bold text-lg uppercase tracking-wide">Total Amount</span>
                            <span class="text-3xl font-bold text-brand-600 tracking-tight">RM {{ number_format($quotation->total_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center bg-slate-50 p-4 rounded-xl border border-slate-100 mt-4">
                            <span class="text-slate-600 font-medium">Deposit Required to Confirm</span>
                            <span class="font-bold text-slate-900 text-lg">RM {{ number_format($quotation->deposit_required, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="p-5 md:p-6 bg-brand-50/50 border-t border-brand-100 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-start gap-4 max-w-xl">
                    <div class="w-12 h-12 rounded-full bg-brand-500 flex items-center justify-center text-white shrink-0 shadow-lg shadow-brand-500/30">
                        <i class="fa-solid fa-headset text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">What happens next?</h4>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed">
                            Our sales team will contact you shortly to discuss your event and provide a <strong>customized, finalized quotation</strong> based on these preliminary requirements. 
                        </p>
                    </div>
                </div>
                <div class="w-full md:w-auto shrink-0">
                    <a href="{{ route('quotations.create', ['quotation_id' => $quotation->quote_number]) }}" 
                       class="inline-flex items-center justify-center w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold text-white bg-slate-900 hover:bg-slate-800 transition-colors shadow-xl shadow-slate-900/20">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Update Requirements
                    </a>
                </div>
            </div>
        </div>

        @if($quotation->terms_conditions)
        <x-card class="p-8 border-slate-100 shadow-sm mb-5">
            <h3 class="text-lg font-bold text-slate-900 mb-4 tracking-tight flex items-center gap-2"><i class="fa-solid fa-file-contract text-amber-500"></i> Terms & Conditions</h3>
            <div class="prose prose-sm prose-slate max-w-none text-slate-600 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100">
                {!! nl2br(e($quotation->terms_conditions)) !!}
            </div>
        </x-card>
        @endif

    </div>
</div>
@endsection
