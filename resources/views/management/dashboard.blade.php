@extends('layouts.management')

@section('title', 'Management Dashboard')
@section('page_title', 'Management Dashboard')
@section('page_subtitle', 'Event Hall Franchise Overview')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-5 mb-8">
    @php
    $statCards = [
        ['label' => 'Total Bookings', 'value' => $stats['total_bookings'], 'icon' => 'fa-calendar-check', 'color' => 'blue'],
        ['label' => "Today's Events", 'value' => $stats['today_bookings'], 'icon' => 'fa-clock', 'color' => 'purple'],
        ['label' => 'Pending Quotes', 'value' => $stats['pending_quotations'], 'icon' => 'fa-file-invoice', 'color' => 'amber'],
        ['label' => 'Available Halls', 'value' => $stats['available_halls'], 'icon' => 'fa-door-open', 'color' => 'emerald'],
        ['label' => 'Cancellations', 'value' => $stats['cancellations'], 'icon' => 'fa-xmark-circle', 'color' => 'red'],
    ];
    $colorMap = [
        'blue'    => 'bg-blue-100 text-blue-700 shadow-blue-500/20',
        'purple'  => 'bg-purple-100 text-purple-700 shadow-purple-500/20',
        'amber'   => 'bg-amber-100 text-amber-700 shadow-amber-500/20',
        'emerald' => 'bg-emerald-100 text-emerald-700 shadow-emerald-500/20',
        'red'     => 'bg-red-100 text-red-700 shadow-red-500/20',
    ];
    @endphp
    @foreach($statCards as $card)
    <x-card class="p-3 sm:p-6 border-slate-100 hover:shadow-lg transition-all duration-300 last:col-span-2 lg:last:col-span-1">
        <div class="flex items-center justify-between mb-2 sm:mb-4">
            <span class="text-[10px] sm:text-xs font-bold text-slate-500 uppercase tracking-widest leading-tight truncate mr-2">{{ $card['label'] }}</span>
            <span class="shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl flex items-center justify-center {{ $colorMap[$card['color']] }} shadow-md sm:shadow-lg">
                <i class="fa-solid {{ $card['icon'] }} text-xs sm:text-base"></i>
            </span>
        </div>
        <div class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight">{{ number_format($card['value']) }}</div>
    </x-card>
    @endforeach
</div>

<!-- Revenue Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
    <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
        <div class="relative z-10">
            <div class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-2">Event Hall Revenue</div>
            <div class="text-4xl md:text-5xl font-black tracking-tight mb-2">RM {{ number_format($stats['event_hall_revenue'], 2) }}</div>
            <div class="text-sm font-medium text-slate-500">All time (paid)</div>
        </div>
    </div>
    <x-card class="p-8 border-slate-100">
        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Outstanding Payments</div>
        <div class="text-4xl font-black text-slate-900 tracking-tight mb-2">RM {{ number_format($stats['outstanding_payments'], 2) }}</div>
        <div class="text-sm font-medium text-slate-500">Unpaid confirmed/pending</div>
    </x-card>
    <x-card class="p-8 border-slate-100">
        <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Active Franchises</div>
        <div class="text-4xl font-black text-slate-900 tracking-tight mb-2">{{ $stats['active_franchises'] }}</div>
        <div class="text-sm font-medium text-slate-500">Franchises in operation</div>
    </x-card>
</div>

<!-- Charts + Upcoming -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-8">
    <!-- Revenue Chart -->
    <x-card class="xl:col-span-2 p-8 border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Event Hall Revenue (6 Months)</h2>
            <a href="{{ route('management.reports.revenue') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors">Full report &rarr;</a>
        </div>
        <canvas id="revenueChart" height="100"></canvas>
    </x-card>

    <!-- Franchise Summary -->
    <x-card class="p-8 border-slate-100">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Franchise Summary</h2>
        </div>
        <div class="space-y-4">
            @forelse($franchises as $franchise)
            <a href="{{ route('management.franchises.show', $franchise) }}"
               class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-amber-200 hover:shadow-md hover:bg-slate-50 transition-all group">
                <div>
                    <div class="font-bold text-slate-900 group-hover:text-amber-700 transition-colors">{{ $franchise->name }}</div>
                    <div class="text-xs font-medium text-slate-500 mt-1">{{ $franchise->event_halls_count }} halls &middot; {{ $franchise->bookings_count }} bookings</div>
                </div>
                <x-badge variant="{{ $franchise->status === 'active' ? 'emerald' : 'slate' }}" class="capitalize shadow-sm">
                    {{ $franchise->status }}
                </x-badge>
            </a>
            @empty
            <div class="text-center p-6 bg-slate-50 rounded-2xl border border-slate-100 text-sm font-medium text-slate-500">
                No franchises yet. <a href="{{ route('management.franchises.create') }}" class="text-amber-600 hover:text-amber-700 font-bold">Create one &rarr;</a>
            </div>
            @endforelse
        </div>
    </x-card>
</div>

<!-- Upcoming Events + Recent Quotes -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
    <!-- Upcoming Events -->
    <x-card class="overflow-hidden border-slate-100">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Upcoming Events</h2>
            <a href="{{ route('management.bookings.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors">View all &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($upcomingEvents as $booking)
            <a href="{{ route('management.bookings.show', $booking) }}"
               class="flex items-center gap-5 p-6 hover:bg-slate-50 transition-colors group">
                <div class="flex-shrink-0 w-14 h-14 bg-white border border-slate-200 rounded-xl flex flex-col items-center justify-center shadow-sm group-hover:border-amber-200 group-hover:shadow-md transition-all">
                    <div class="text-[10px] font-black text-amber-600 uppercase tracking-widest">{{ $booking->check_in->format('M') }}</div>
                    <div class="text-xl font-black text-slate-900 leading-none mt-0.5">{{ $booking->check_in->format('d') }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-900 truncate group-hover:text-amber-700 transition-colors">{{ $booking->guest_name }}</div>
                    <div class="text-xs font-medium text-slate-500 mt-1">{{ $booking->eventHall->franchise->name ?? '' }} &middot; {{ $booking->eventHall->name ?? '' }}</div>
                    <div class="text-xs font-medium text-slate-400 mt-0.5"><i class="fa-regular fa-clock"></i> {{ $booking->start_time }} &ndash; {{ $booking->end_time }}</div>
                </div>
                <x-badge variant="{{ $booking->status === 'confirmed' ? 'emerald' : 'amber' }}" class="capitalize shrink-0 shadow-sm">
                    {{ $booking->status }}
                </x-badge>
            </a>
            @empty
            <div class="p-10 text-center text-sm font-medium text-slate-500 bg-slate-50/50">No upcoming events.</div>
            @endforelse
        </div>
    </x-card>

    <!-- Recent Quotations -->
    <x-card class="overflow-hidden border-slate-100">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Recent Quotations</h2>
            <a href="{{ route('management.quotations.index') }}" class="text-sm font-bold text-amber-600 hover:text-amber-700 transition-colors">View all &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentQuotations as $quote)
            <a href="{{ route('management.quotations.show', $quote) }}"
               class="flex items-center gap-5 p-6 hover:bg-slate-50 transition-colors group">
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-900 group-hover:text-amber-700 transition-colors">{{ $quote->guest_name }}</div>
                    <div class="text-xs font-medium text-slate-500 mt-1"><span class="font-mono text-slate-400 bg-slate-100 px-1 py-0.5 rounded">{{ $quote->quote_number }}</span> &middot; {{ $quote->eventHall->name ?? 'N/A' }}</div>
                </div>
                <div class="text-right shrink-0 flex flex-col items-end gap-2">
                    <div class="text-sm font-black text-slate-900 tracking-tight">RM {{ number_format($quote->total_amount, 2) }}</div>
                    @php
                    $badgeTheme = match($quote->status) {
                        'generated' => 'blue',
                        'accepted'  => 'emerald',
                        'rejected'  => 'red',
                        'converted' => 'purple',
                        'expired'   => 'slate',
                        default     => 'amber',
                    };
                    @endphp
                    <x-badge variant="{{ $badgeTheme }}" class="capitalize shadow-sm">{{ $quote->status }}</x-badge>
                </div>
            </a>
            @empty
            <div class="p-10 text-center text-sm font-medium text-slate-500 bg-slate-50/50">No quotations yet.</div>
            @endforelse
        </div>
    </x-card>
</div>
@endsection

@push('scripts')
<script>
const ctx = document.getElementById('revenueChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($revenueLabels),
        datasets: [{
            label: 'Revenue (RM)',
            data: @json($revenueData),
            backgroundColor: 'rgba(232, 108, 24, 0.15)',
            borderColor: '#e86c18',
            borderWidth: 2,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { callback: v => 'RM ' + v.toLocaleString() }
            },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
