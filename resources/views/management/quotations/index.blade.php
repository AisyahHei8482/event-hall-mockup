@extends('layouts.management')
@section('title', 'Quotations')
@section('page_title', 'Quotations')

@section('content')
<!-- Filters -->
<x-card class="p-6 border-slate-100 mb-6 bg-white shadow-sm">
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
        <div class="sm:col-span-2 lg:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Search</label>
            <x-input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, quote#..." class="bg-slate-50 w-full" />
        </div>
        <div class="sm:col-span-1 lg:col-span-2">
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status</label>
            <x-select name="status" class="bg-slate-50 w-full">
                <option value="">All Statuses</option>
                @foreach(['draft','generated','sent','viewed','accepted','rejected','expired','converted','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </x-select>
        </div>
        <div class="flex gap-2 sm:col-span-1 lg:col-span-1">
            <x-button type="submit" variant="primary" class="h-[42px] px-6 flex-1">Filter</x-button>
            <a href="{{ route('management.quotations.index') }}" class="inline-flex items-center justify-center h-[42px] px-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors flex-1">Clear</a>
        </div>
    </form>
</x-card>

<x-card class="overflow-hidden border-slate-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Quote #</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guest</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden md:table-cell">Hall</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden lg:table-cell">Event Date</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                    <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Total</th>
                    <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($quotations as $quote)
                <tr class="hover:bg-amber-50/30 transition-colors group">
                    <td class="px-6 py-4">
                        <a href="{{ route('management.quotations.show', $quote) }}"
                           class="font-mono text-sm font-bold text-amber-600 group-hover:text-amber-700 transition-colors">{{ $quote->quote_number }}</a>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-900">{{ $quote->guest_name }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">{{ $quote->guest_email }}</div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell text-slate-600">
                        <div class="font-bold text-slate-900">{{ $quote->eventHall->name ?? '—' }}</div>
                        <div class="text-xs font-medium text-slate-500 mt-1">{{ $quote->eventHall->franchise->name ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell text-slate-600">
                        <div class="font-bold text-slate-900">{{ $quote->event_date?->format('d M Y') ?: '—' }}</div>
                        @if($quote->start_time)<div class="text-xs font-mono font-medium text-slate-500 mt-1">{{ $quote->start_time }} – {{ $quote->end_time }}</div>@endif
                    </td>
                    <td class="px-6 py-4">
                        @php
                        $badgeTheme = match($quote->status) {
                            'generated' => 'blue',
                            'accepted'  => 'emerald',
                            'rejected'  => 'red',
                            'converted' => 'purple',
                            'expired'   => 'slate',
                            'sent'      => 'slate',
                            'viewed'    => 'slate',
                            default     => 'amber',
                        };
                        @endphp
                        <x-badge variant="{{ $badgeTheme }}" class="capitalize shadow-sm">{{ $quote->status }}</x-badge>
                        @if($quote->isExpired() && !in_array($quote->status,['expired','cancelled','converted']))
                            <span class="text-[10px] font-black text-red-500 uppercase tracking-widest block mt-2">Expired</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right font-black text-slate-900 text-base">RM {{ number_format($quote->total_amount,2) }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('management.quotations.show', $quote) }}"
                               class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-amber-100 hover:text-amber-700 rounded-lg transition-colors">View</a>
                            @if($quote->canBeConverted())
                            <form method="POST" action="{{ route('management.quotations.convert', $quote) }}">
                                @csrf
                                <button class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-purple-100 text-purple-700 hover:bg-purple-200 rounded-lg transition-colors">Convert</button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-16 text-center text-slate-500 font-medium">No quotations found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($quotations->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $quotations->links() }}</div>
    @endif
</x-card>
@endsection
