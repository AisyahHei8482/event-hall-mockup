@extends('layouts.management')
@section('title', 'Bookings Report')
@section('page_title', 'Bookings Report')

@section('content')
<div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-slate-500 mb-1">Status</label>
            <select name="status" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500">
                <option value="">All</option>
                @foreach(['pending','confirmed','completed','cancelled'] as $s)
                <option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div><label class="block text-xs font-medium text-slate-500 mb-1">From</label><input type="date" name="from" value="{{ request('from') }}" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"></div>
        <div><label class="block text-xs font-medium text-slate-500 mb-1">To</label><input type="date" name="to" value="{{ request('to') }}" class="border border-slate-300 rounded-xl px-3.5 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500"></div>
        <button type="submit" class="bg-brand-500 text-white px-5 py-2 rounded-xl text-sm font-medium">Filter</button>
        <a href="{{ route('management.reports.bookings.export', request()->query()) }}" class="bg-emerald-500 text-white px-4 py-2 rounded-xl text-sm font-medium hover:bg-emerald-600">Export CSV</a>
    </form>
</div>

<!-- Summary -->
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
    @foreach(['total' => 'Total', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled'] as $k => $l)
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <div class="text-xs text-slate-400 mb-0.5">{{ $l }}</div>
        <div class="text-2xl font-bold">{{ $summary[$k] }}</div>
    </div>
    @endforeach
    <div class="bg-gradient-to-br from-brand-500 to-brand-600 rounded-2xl p-4 text-white">
        <div class="text-xs opacity-80 mb-0.5">Revenue</div>
        <div class="text-xl font-bold">RM {{ number_format($summary['revenue'],2) }}</div>
    </div>
    <div class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        <div class="text-xs text-amber-500 mb-0.5">Outstanding</div>
        <div class="text-xl font-bold text-amber-600">RM {{ number_format($summary['outstanding'],2) }}</div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 border-b border-slate-200">
            <tr>
                <th class="text-left px-5 py-3 font-semibold text-slate-600">Booking</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600">Guest</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600 hidden md:table-cell">Hall</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600">Date</th>
                <th class="text-left px-4 py-3 font-semibold text-slate-600">Status</th>
                <th class="text-right px-5 py-3 font-semibold text-slate-600">Total</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($bookings as $b)
            <tr class="hover:bg-slate-50">
                <td class="px-5 py-3"><a href="{{ route('management.bookings.show', $b) }}" class="font-mono text-xs text-brand-600 hover:underline">{{ $b->booking_number }}</a></td>
                <td class="px-4 py-3 font-medium">{{ $b->guest_name }}</td>
                <td class="px-4 py-3 text-slate-600 hidden md:table-cell">{{ $b->eventHall->name ?? '—' }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $b->check_in->format('d M Y') }}</td>
                <td class="px-4 py-3"><span class="text-xs px-2 py-0.5 rounded-full {{ $b->status==='confirmed'?'bg-emerald-100 text-emerald-700':($b->status==='cancelled'?'bg-red-100 text-red-700':'bg-amber-100 text-amber-700') }}">{{ ucfirst($b->status) }}</span></td>
                <td class="px-5 py-3 text-right font-semibold">RM {{ number_format($b->total_price,2) }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">No bookings match the filter.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($bookings->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $bookings->links() }}</div>@endif
</div>
@endsection
