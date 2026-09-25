@extends('layouts.management')
@section('title', 'Operations Report')
@section('page_title', 'Operations Report')
@section('page_subtitle', 'Upcoming events and scheduling')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" class="flex gap-3 items-end">
        <div><label class="block text-xs font-medium text-slate-500 mb-1">From Date</label>
        <input type="date" name="date" value="{{ $date }}" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"></div>
        <button type="submit" class="bg-brand-500 text-white px-5 py-2 rounded-xl text-sm font-medium">View</button>
    </form>
</div>

<div class="space-y-4">
    @forelse($upcomingEvents as $booking)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <div class="flex flex-wrap items-start justify-between gap-4 mb-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="text-brand-500 font-semibold text-sm">{{ $booking->check_in->format('D, d M Y') }}</span>
                    <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $booking->start_time }} – {{ $booking->end_time }}</span>
                </div>
                <h3 class="font-semibold text-slate-900">{{ $booking->guest_name }}</h3>
                <div class="text-sm text-slate-500">{{ $booking->eventHall->franchise->name ?? '' }} · {{ $booking->eventHall->name ?? '' }} · {{ $booking->guests }} pax</div>
                @if($booking->event_type)<div class="text-xs text-slate-400 mt-0.5">{{ $booking->event_type }}</div>@endif
            </div>
            <div class="flex gap-2">
                <span class="text-xs px-2.5 py-1 rounded-full {{ $booking->status==='confirmed'?'bg-emerald-100 text-emerald-700':'bg-amber-100 text-amber-700' }}">{{ ucfirst($booking->status) }}</span>
                <a href="{{ route('management.bookings.show', $booking) }}" class="text-xs bg-brand-50 text-brand-700 px-3 py-1 rounded-xl hover:bg-brand-100">View →</a>
            </div>
        </div>

        @if($booking->eventSchedules->isNotEmpty())
        <div class="border-t border-slate-100 pt-4">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Event Rundown</div>
            <div class="space-y-2">
                @foreach($booking->eventSchedules as $s)
                <div class="flex items-center gap-3 text-sm">
                    <span class="font-mono text-xs text-slate-400 w-20 shrink-0">{{ $s->start_time }}</span>
                    <span class="text-xs bg-brand-50 text-brand-700 px-2 py-0.5 rounded">{{ $s->activity_type }}</span>
                    <span class="font-medium text-slate-800">{{ $s->title }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-xs text-slate-400 mt-2">No rundown schedule yet.</div>
        @endif
    </div>
    @empty
    <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center">
        <i class="fa-solid fa-calendar-check text-4xl text-slate-200 mb-3"></i>
        <p class="text-slate-400">No upcoming events from {{ $date }}.</p>
    </div>
    @endforelse
</div>
@endsection
