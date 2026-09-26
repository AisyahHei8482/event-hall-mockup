@extends('layouts.management')
@section('title', $franchise->name)
@section('page_title', $franchise->name)
@section('page_subtitle', $franchise->company_name)

@section('content')
<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
    @foreach([
        ['label' => 'Total Bookings', 'value' => $stats['total_bookings'], 'icon' => 'fa-calendar'],
        ['label' => 'Revenue', 'value' => 'RM '.number_format($stats['total_revenue'],2), 'icon' => 'fa-sack-dollar'],
        ['label' => 'Quotations', 'value' => $stats['total_quotes'], 'icon' => 'fa-file-invoice'],
        ['label' => 'Active Halls', 'value' => $stats['active_halls'], 'icon' => 'fa-door-open'],
    ] as $s)
    <x-card class="p-6 border-slate-100 shadow-sm transition-all hover:shadow-md hover:border-brand-200 group">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center group-hover:bg-brand-500 transition-colors">
                <i class="fa-solid {{ $s['icon'] }} text-brand-600 group-hover:text-white transition-colors text-sm"></i>
            </div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $s['label'] }}</span>
        </div>
        <div class="text-2xl font-black text-slate-900 tracking-tight">{{ $s['value'] }}</div>
    </x-card>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Franchise Info -->
    <x-card class="p-8 border-slate-100 shadow-sm space-y-8">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Franchise Info</h2>
            <a href="{{ route('management.franchises.edit', $franchise) }}"
               class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-widest transition-colors flex items-center gap-1">Edit <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        @if($franchise->logo)
        <div class="w-24 h-24 rounded-2xl border border-slate-200 bg-slate-50 p-2 shadow-sm">
            <img src="{{ asset('media/'.$franchise->logo) }}" class="w-full h-full object-contain">
        </div>
        @endif
        <dl class="space-y-4 text-sm">
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Code</dt><dd class="font-black font-mono text-slate-900 bg-slate-100 px-2 py-1 rounded-md">{{ $franchise->code }}</dd></div>
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Contact</dt><dd class="font-bold text-slate-900">{{ $franchise->contact_person ?: '—' }}</dd></div>
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Phone</dt><dd class="font-bold text-slate-900">{{ $franchise->phone ?: '—' }}</dd></div>
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Email</dt><dd class="font-bold text-slate-900">{{ $franchise->email ?: '—' }}</dd></div>
            <div>
                <dt class="font-bold text-slate-500 uppercase tracking-widest text-xs mb-1">Address</dt>
                <dd class="font-medium text-slate-700 leading-relaxed">{{ $franchise->address ?: '—' }}</dd>
            </div>
            <div class="flex justify-between items-center pt-2"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Status</dt>
                <dd><x-badge variant="{{ $franchise->status === 'active' ? 'emerald' : 'slate' }}" class="uppercase tracking-widest shadow-sm">{{ ucfirst($franchise->status) }}</x-badge></dd>
            </div>
        </dl>

        <!-- Event Halls -->
        <div class="pt-6 border-t border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Event Halls</h3>
                <a href="{{ route('management.halls.create') }}?franchise_id={{ $franchise->id }}" class="text-xs font-bold text-brand-600 hover:text-brand-700 uppercase tracking-widest transition-colors flex items-center gap-1"><i class="fa-solid fa-plus"></i> Add</a>
            </div>
            <div class="space-y-2">
                @foreach($franchise->eventHalls as $hall)
                <a href="{{ route('management.halls.show', $hall) }}"
                   class="flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-brand-200 hover:bg-brand-50/50 transition-all group">
                    <span class="text-sm font-bold text-slate-700 group-hover:text-brand-700 transition-colors">{{ $hall->name }}</span>
                    <x-badge variant="{{ $hall->is_active ? 'emerald' : 'slate' }}" class="uppercase tracking-widest">{{ $hall->is_active ? 'Active' : 'Inactive' }}</x-badge>
                </a>
                @endforeach
            </div>
        </div>
    </x-card>

    <!-- Recent Bookings -->
    <x-card class="lg:col-span-2 overflow-hidden border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Recent Bookings</h2>
        </div>
        <div class="divide-y divide-slate-100">
            @forelse($recentBookings as $booking)
            <a href="{{ route('management.bookings.show', $booking) }}"
               class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-4 hover:bg-brand-50/30 transition-colors group">
                <div>
                    <div class="font-bold text-slate-900 group-hover:text-brand-700 transition-colors">{{ $booking->guest_name }}</div>
                    <div class="text-xs font-bold text-slate-500 mt-1 uppercase tracking-widest">{{ $booking->eventHall->name ?? '—' }} · <span class="text-brand-600">{{ $booking->check_in->format('d M Y') }}</span></div>
                </div>
                <div class="sm:text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-center">
                    <div class="font-black text-slate-900 mb-1">RM {{ number_format($booking->total_price,2) }}</div>
                    <x-badge variant="{{ $booking->status === 'confirmed' ? 'emerald' : 'amber' }}" class="uppercase tracking-widest">{{ ucfirst($booking->status) }}</x-badge>
                </div>
            </a>
            @empty
            <div class="px-6 py-12 text-center text-sm font-medium text-slate-500">No bookings yet.</div>
            @endforelse
        </div>
    </x-card>
</div>
@endsection
