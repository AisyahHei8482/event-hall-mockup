@extends('layouts.management')
@section('title', 'Revenue Report')
@section('page_title', 'Revenue Report')

@section('content')
<!-- Date Filter -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">From</label>
            <input type="date" name="from" value="{{ $fromDate }}"
                   class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">To</label>
            <input type="date" name="to" value="{{ $toDate }}"
                   class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
        </div>
        <button type="submit" class="bg-brand-500 text-white px-5 py-2 rounded-xl text-sm font-medium">Apply</button>
    </form>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-5 text-white">
        <div class="text-xs opacity-80 mb-1">Total Revenue</div>
        <div class="text-2xl font-bold">RM {{ number_format($byFranchise->sum(),2) }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="text-xs text-slate-400 mb-1">Outstanding</div>
        <div class="text-2xl font-bold text-slate-900">RM {{ number_format($outstanding,2) }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="text-xs text-slate-400 mb-1">Top Franchise</div>
        <div class="text-lg font-bold text-slate-900">{{ $byFranchise->keys()->first() ?: '—' }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
        <div class="text-xs text-slate-400 mb-1">Top Event Type</div>
        <div class="text-lg font-bold text-slate-900">{{ $byEventType->first()?->event_type ?: '—' }}</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Chart -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Monthly Revenue (Last 12 Months)</h3>
        <canvas id="monthlyChart" height="180"></canvas>
    </div>

    <!-- By Franchise -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="font-semibold text-slate-900 mb-4">Revenue by Franchise</h3>
        <div class="space-y-3">
            @foreach($byFranchise as $name => $revenue)
            <div>
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium">{{ $name ?: 'Unknown' }}</span>
                    <span class="font-semibold">RM {{ number_format($revenue,2) }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-2">
                    <div class="bg-brand-500 h-2 rounded-full" style="width:{{ $byFranchise->max() > 0 ? round(($revenue/$byFranchise->max())*100) : 0 }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- By Hall -->
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-900">Revenue by Hall</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Hall</th>
                <th class="text-right px-4 py-3 font-semibold text-slate-600">Bookings</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Revenue</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($byHall as $row)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 font-medium">{{ $row->eventHall->name ?? '—' }}</td>
                <td class="px-4 py-3 text-right">{{ $row->bookings }}</td>
                <td class="px-5 py-3 text-right font-semibold text-brand-600">RM {{ number_format($row->revenue,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- By Event Type -->
@if($byEventType->isNotEmpty())
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100">
        <h3 class="font-semibold text-slate-900">Revenue by Event Type</h3>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Event Type</th>
                <th class="text-right px-4 py-3 font-semibold text-slate-600">Bookings</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Revenue</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($byEventType as $row)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3 font-medium">{{ $row->event_type ?: 'N/A' }}</td>
                <td class="px-4 py-3 text-right">{{ $row->bookings }}</td>
                <td class="px-5 py-3 text-right font-semibold text-brand-600">RM {{ number_format($row->revenue,2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection

@push('scripts')
<script>
const labels = @json($byMonth->keys()->map(fn($k) => \Carbon\Carbon::parse($k.'-01')->format('M Y')));
const data = @json($byMonth->values());
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (RM)',
            data,
            borderColor: '#e86c18',
            backgroundColor: 'rgba(232, 108, 24, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#e86c18'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, ticks: { callback: v => 'RM '+v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
