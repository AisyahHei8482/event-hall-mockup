@extends('layouts.admin')

@section('title', 'Dashboard - Admin')
@section('page_title', 'Dashboard')

@section('content')
<div class="grid grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
    <x-card class="p-6 border-slate-100 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Bookings</span>
            <span class="w-10 h-10 rounded-xl flex items-center justify-center bg-forest-100 text-forest-700 shadow-lg shadow-forest-500/20">
                <i class="fa-solid fa-calendar-check text-base"></i>
            </span>
        </div>
        <div class="text-4xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_bookings']) }}</div>
    </x-card>
    <x-card class="p-6 border-slate-100 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Pending</span>
            <span class="w-10 h-10 rounded-xl flex items-center justify-center bg-amber-100 text-amber-700 shadow-lg shadow-amber-500/20">
                <i class="fa-solid fa-clock text-base"></i>
            </span>
        </div>
        <div class="text-4xl font-black text-slate-900 tracking-tight">{{ number_format($stats['pending_bookings']) }}</div>
    </x-card>
    <div class="bg-forest-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden group">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:bg-white/20 transition-all duration-500"></div>
        <div class="relative z-10 flex flex-col h-full justify-between">
            <div class="text-xs font-bold uppercase tracking-widest text-forest-200 mb-2">Revenue</div>
            <div class="text-3xl lg:text-4xl font-black tracking-tight mt-2">RM {{ number_format($stats['revenue'], 2) }}</div>
        </div>
    </div>
    <x-card class="p-6 border-slate-100 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Facilities</span>
            <span class="w-10 h-10 rounded-xl flex items-center justify-center bg-blue-100 text-blue-700 shadow-lg shadow-blue-500/20">
                <i class="fa-solid fa-building text-base"></i>
            </span>
        </div>
        <div class="text-4xl font-black text-slate-900 tracking-tight">{{ number_format($stats['facilities']) }}</div>
    </x-card>
    <x-card class="p-6 border-slate-100 hover:shadow-lg transition-all duration-300">
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">New Inquiries</span>
            <span class="w-10 h-10 rounded-xl flex items-center justify-center bg-purple-100 text-purple-700 shadow-lg shadow-purple-500/20">
                <i class="fa-solid fa-envelope text-base"></i>
            </span>
        </div>
        <div class="text-4xl font-black text-slate-900 tracking-tight">{{ number_format($stats['new_inquiries']) }}</div>
    </x-card>
</div>

<x-card class="p-8 border-slate-100 mb-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-lg font-bold text-slate-900 tracking-tight">Monthly Revenue (Last 6 Months)</h2>
    </div>
    <canvas id="revenueChart" height="80"></canvas>
</x-card>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Upcoming Bookings -->
    <x-card class="overflow-hidden border-slate-100">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Upcoming Bookings</h2>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm font-bold text-forest-600 hover:text-forest-700 transition-colors">View all &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($upcomingBookings as $booking)
            <div class="flex items-center gap-5 p-6 hover:bg-slate-50 transition-colors group">
                <div class="flex-shrink-0 w-14 h-14 bg-white border border-slate-200 rounded-xl flex flex-col items-center justify-center shadow-sm group-hover:border-forest-200 group-hover:shadow-md transition-all">
                    <div class="text-[10px] font-black text-forest-600 uppercase tracking-widest">{{ $booking->check_in->format('M') }}</div>
                    <div class="text-xl font-black text-slate-900 leading-none mt-0.5">{{ $booking->check_in->format('d') }}</div>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-900 truncate group-hover:text-forest-700 transition-colors">{{ $booking->facility->name }}</div>
                    <div class="text-xs font-medium text-slate-500 mt-1">{{ $booking->guest_name }}</div>
                </div>
                <x-badge variant="{{ $booking->status === 'confirmed' ? 'emerald' : 'amber' }}" class="capitalize shrink-0 shadow-sm">
                    {{ str_replace('_', ' ', $booking->status) }}
                </x-badge>
            </div>
            @empty
            <div class="p-10 text-center text-sm font-medium text-slate-500 bg-slate-50/50">No upcoming bookings.</div>
            @endforelse
        </div>
    </x-card>

    <!-- Recent Inquiries -->
    <x-card class="overflow-hidden border-slate-100">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <h2 class="text-lg font-bold text-slate-900 tracking-tight">Recent Inquiries</h2>
            <a href="{{ route('admin.inquiries.index') }}" class="text-sm font-bold text-forest-600 hover:text-forest-700 transition-colors">View all &rarr;</a>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse ($recentInquiries as $inquiry)
            <div class="flex items-center gap-5 p-6 hover:bg-slate-50 transition-colors group">
                <div class="flex-shrink-0 w-12 h-12 bg-sand-100 text-sand-700 rounded-full flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-slate-900 truncate group-hover:text-forest-700 transition-colors">{{ $inquiry->name }}</div>
                    <div class="text-xs font-medium text-slate-500 mt-1 truncate">{{ Str::limit($inquiry->message, 60) }}</div>
                </div>
                <x-badge variant="{{ $inquiry->status === 'pending' ? 'amber' : 'slate' }}" class="capitalize shrink-0 shadow-sm">
                    {{ $inquiry->status }}
                </x-badge>
            </div>
            @empty
            <div class="p-10 text-center text-sm font-medium text-slate-500 bg-slate-50/50">No inquiries yet.</div>
            @endforelse
        </div>
    </x-card>
</div>

    <script>
        new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($revenueLabels),
                datasets: [{
                    label: 'Revenue (RM)',
                    data: @json($revenueData),
                    borderColor: '#3c7038',
                    backgroundColor: 'rgba(76, 140, 72, 0.15)',
                    tension: 0.3,
                    fill: true,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } },
            },
        });
    </script>
@endsection
