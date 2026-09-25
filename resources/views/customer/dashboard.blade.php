@extends('layouts.app')
@section('title', 'My Dashboard - Savanna Hill Resort')

@section('content')
<div class="bg-forest-900 text-white pt-24 pb-12 rounded-b-3xl">
    <div class="max-w-6xl mx-auto px-6">
        <h1 class="text-3xl font-bold">Welcome back, {{ auth()->user()->name }}!</h1>
        <p class="mt-2 text-forest-200">Manage your bookings, quotes, and wishlists here.</p>
    </div>
</div>

<div class="max-w-6xl mx-auto px-6 py-12 grid grid-cols-1 md:grid-cols-2 gap-8">
    
    <!-- Quotes -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-brand-500"></i> My Quotations
        </h2>
        <div class="space-y-4">
            @forelse($quotations as $quote)
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $quote->quote_number }}</span>
                        <h3 class="font-bold text-slate-900 mt-2">{{ $quote->eventHall->name ?? 'Event Hall' }}</h3>
                        <p class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($quote->event_date)->format('M d, Y') }}</p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold rounded-lg uppercase tracking-wider
                        {{ $quote->status === 'accepted' ? 'bg-emerald-100 text-emerald-700' : 
                          ($quote->status === 'generated' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-700') }}">
                        {{ $quote->status }}
                    </span>
                </div>
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-50">
                    <span class="font-bold text-slate-900">RM {{ number_format($quote->total_amount, 2) }}</span>
                    <!-- You can add a view quote link when ready -->
                </div>
            </div>
            @empty
            <div class="bg-slate-50 p-6 rounded-2xl text-center border border-slate-100">
                <p class="text-sm text-slate-500">You don't have any event hall quotes.</p>
                <a href="{{ url('/event-halls') }}" class="text-brand-600 text-sm font-bold mt-2 inline-block">Explore Halls &rarr;</a>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Bookings -->
    <div>
        <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fa-solid fa-calendar-check text-forest-500"></i> My Bookings
        </h2>
        <div class="space-y-4">
            @forelse($bookings as $booking)
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <span class="font-mono text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded">{{ $booking->booking_reference }}</span>
                        <h3 class="font-bold text-slate-900 mt-2">
                            {{ $booking->booking_type === 'event_hall' ? ($booking->eventHall->name ?? 'Event') : ($booking->facility->name ?? 'Resort Stay') }}
                        </h3>
                        <p class="text-sm text-slate-500">
                            {{ \Carbon\Carbon::parse($booking->check_in)->format('M d') }} - {{ \Carbon\Carbon::parse($booking->check_out)->format('M d, Y') }}
                        </p>
                    </div>
                    <span class="px-2 py-1 text-xs font-bold rounded-lg uppercase tracking-wider
                        {{ $booking->status === 'confirmed' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                        {{ $booking->status }}
                    </span>
                </div>
            </div>
            @empty
            <div class="bg-slate-50 p-6 rounded-2xl text-center border border-slate-100">
                <p class="text-sm text-slate-500">You don't have any active bookings.</p>
                <a href="{{ url('/') }}" class="text-forest-600 text-sm font-bold mt-2 inline-block">Book a Stay &rarr;</a>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
