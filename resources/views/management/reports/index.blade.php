@extends('layouts.management')
@section('title', 'Reports')
@section('page_title', 'Reports')
@section('page_subtitle', 'Analytics and reporting for event hall operations')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
    @php
    $reports = [
        ['route' => 'management.reports.bookings', 'icon' => 'fa-calendar-check', 'color' => 'blue', 'title' => 'Bookings Report', 'desc' => 'All event hall bookings, filtered by date, status, and franchise.'],
        ['route' => 'management.reports.revenue', 'icon' => 'fa-sack-dollar', 'color' => 'emerald', 'title' => 'Revenue Report', 'desc' => 'Revenue breakdown by franchise, hall, event type, and month.'],
        ['route' => 'management.reports.quotations', 'icon' => 'fa-file-invoice', 'color' => 'purple', 'title' => 'Quotations Report', 'desc' => 'Quotation pipeline, conversion rates, and trends.'],
        ['route' => 'management.reports.operations', 'icon' => 'fa-clipboard-list', 'color' => 'amber', 'title' => 'Operations Report', 'desc' => 'Upcoming events, staff assignments, and rundown schedules.'],
    ];
    $colors = [
        'blue'    => 'bg-blue-50 text-blue-600 border-blue-100',
        'emerald' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        'purple'  => 'bg-purple-50 text-purple-600 border-purple-100',
        'amber'   => 'bg-amber-50 text-amber-600 border-amber-100',
    ];
    @endphp
    @foreach($reports as $r)
    <a href="{{ route($r['route']) }}"
       class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-all hover:-translate-y-0.5 group">
        <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-4 {{ $colors[$r['color']] }} border">
            <i class="fa-solid {{ $r['icon'] }} text-lg"></i>
        </div>
        <h3 class="font-semibold text-slate-900 mb-1.5 group-hover:text-brand-600 transition-colors">{{ $r['title'] }}</h3>
        <p class="text-sm text-slate-500 leading-relaxed">{{ $r['desc'] }}</p>
        <div class="mt-4 text-xs text-brand-500 font-medium">View Report →</div>
    </a>
    @endforeach
</div>
@endsection
