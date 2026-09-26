@extends('layouts.management')
@section('title', 'Bookings')
@section('page_title', 'Event Hall Bookings')

@section('content')
<style>
@media (max-width: 768px) {
    .fc .fc-header-toolbar { flex-direction: column !important; gap: 0.75rem; }
    .fc .fc-toolbar-chunk { display: flex; justify-content: center; width: 100%; }
    .fc .fc-toolbar-title { font-size: 1.25rem !important; }
}
</style>
<div x-data="bookingManager()">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end mb-6 gap-4">
        <!-- Filters -->
        <div class="flex-1 w-full">
            <x-card class="p-6 border-slate-100 bg-white shadow-sm h-full">
                <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Search</label>
                        <x-input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, booking#..." class="bg-slate-50 w-full" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status</label>
                        <x-select name="status" class="bg-slate-50 w-full">
                            <option value="">All</option>
                            @foreach(['pending','confirmed','checked_in','completed','cancelled'] as $s)
                            <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                            @endforeach
                        </x-select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">From</label>
                        <x-input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-slate-50 w-full" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">To</label>
                        <x-input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-slate-50 w-full" />
                    </div>
                    <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                        <x-button type="submit" variant="primary" class="h-[42px] px-6 flex-1">Filter</x-button>
                        <a href="{{ route('management.bookings.index') }}" class="inline-flex items-center justify-center h-[42px] px-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors flex-1">Clear</a>
                    </div>
                </form>
            </x-card>
        </div>
        
        <!-- View Toggles -->
        <div class="flex bg-slate-100 p-1 rounded-xl shrink-0">
            <button @click="setView('list')" :class="view === 'list' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all flex items-center">
                <i class="fa-solid fa-list mr-2"></i> List
            </button>
            <button @click="setView('calendar')" :class="view === 'calendar' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-sm font-bold rounded-lg transition-all flex items-center">
                <i class="fa-regular fa-calendar-days mr-2"></i> Calendar
            </button>
        </div>
    </div>

<div x-show="view === 'list'">
<x-card class="overflow-hidden border-slate-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Booking</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guest</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden md:table-cell">Hall</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Date / Time</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden lg:table-cell">Payment</th>
                    <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($bookings as $booking)
                <tr class="hover:bg-amber-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <a href="{{ route('management.bookings.show', $booking) }}"
                           class="font-mono text-sm font-bold text-amber-600 group-hover:text-amber-700 transition-colors">{{ $booking->booking_number }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $booking->guest_name }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">{{ $booking->event_type ?: $booking->guests.' pax' }}</div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <div class="font-bold text-slate-900">{{ $booking->eventHall->name ?? '—' }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">{{ $booking->eventHall->franchise->name ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $booking->check_in->format('d M Y') }}</div>
                        <div class="text-xs font-mono font-medium text-slate-500 mt-1">{{ $booking->start_time }} – {{ $booking->end_time }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $statusTheme = match($booking->status) {
                            'confirmed'   => 'emerald',
                            'pending'     => 'amber',
                            'cancelled'   => 'red',
                            'completed'   => 'slate',
                            'checked_in'  => 'blue',
                            default       => 'slate',
                        };
                        @endphp
                        <x-badge variant="{{ $statusTheme }}" class="capitalize shadow-sm">
                            {{ str_replace('_', ' ', $booking->status) }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell">
                        <x-badge variant="{{ $booking->payment_status === 'paid' ? 'emerald' : 'amber' }}" class="capitalize shadow-sm">
                            {{ $booking->payment_status }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 text-right font-black text-slate-900 text-base">RM {{ number_format($booking->total_price,2) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-16 text-center text-slate-500 font-medium">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($bookings->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $bookings->links() }}</div>
    @endif
</x-card>
</div>

<div x-show="view === 'calendar'" style="display: none;">
    <x-card class="p-6 border-slate-100 shadow-sm">
        <div id="calendar" class="min-h-[600px]"></div>
    </x-card>
</div>
</div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('bookingManager', () => ({
            view: localStorage.getItem('bookingView') || 'list',
            calendar: null,
            init() {
                this.$watch('view', val => {
                    localStorage.setItem('bookingView', val);
                    if (val === 'calendar' && !this.calendar) {
                        this.initCalendar();
                    } else if (val === 'calendar' && this.calendar) {
                        setTimeout(() => this.calendar.render(), 100);
                    }
                });
                if (this.view === 'calendar') {
                    setTimeout(() => this.initCalendar(), 100);
                }
            },
            setView(val) {
                this.view = val;
            },
            initCalendar() {
                const el = document.getElementById('calendar');
                if (!el) return;
                
                this.calendar = new FullCalendar.Calendar(el, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    height: 800,
                    events: function(info, successCallback, failureCallback) {
                        // Extract query params from current URL to preserve filters
                        const params = new URLSearchParams(window.location.search);
                        const url = new URL("{{ route('management.bookings.calendar') }}", window.location.origin);
                        
                        // Pass month/year from FullCalendar
                        url.searchParams.append('start', info.startStr);
                        url.searchParams.append('end', info.endStr);
                        
                        // Pass existing filters
                        if(params.has('status')) url.searchParams.append('status', params.get('status'));
                        
                        fetch(url)
                            .then(res => res.json())
                            .then(data => successCallback(data))
                            .catch(err => failureCallback(err));
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            window.location.href = info.event.url;
                            info.jsEvent.preventDefault();
                        }
                    },
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        meridiem: false,
                        hour12: false
                    }
                });
                this.calendar.render();
            }
        }));
    });
</script>
@endpush
@endsection
