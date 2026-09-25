@extends('layouts.admin')

@section('title', 'Finance - Admin')
@section('page_title', 'Finance Report')

@section('content')
    <form method="GET" action="{{ route('admin.finance.index') }}" class="flex flex-wrap items-end gap-4 mb-6 bg-white rounded-2xl border border-forest-100 p-4">
        <div>
            <label class="block text-xs font-medium text-forest-600 mb-1">From</label>
            <input type="date" name="from" value="{{ $from->format('Y-m-d') }}" class="rounded-lg border-forest-200 text-sm">
        </div>
        <div>
            <label class="block text-xs font-medium text-forest-600 mb-1">To</label>
            <input type="date" name="to" value="{{ $to->format('Y-m-d') }}" class="rounded-lg border-forest-200 text-sm">
        </div>
        <button type="submit" class="bg-forest-700 hover:bg-forest-800 text-white text-sm font-medium px-4 py-2 rounded-lg">Filter</button>
        <a href="{{ route('admin.finance.export', request()->only('from', 'to')) }}" class="bg-forest-100 hover:bg-forest-200 text-forest-800 text-sm font-medium px-4 py-2 rounded-lg">
            <i class="fa-solid fa-download mr-1"></i>Export CSV
        </a>
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-forest-100 p-5">
            <p class="text-xs text-forest-500 uppercase tracking-wide">Total Revenue</p>
            <p class="text-2xl font-bold text-forest-900 mt-1">RM {{ number_format($totalRevenue, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-forest-100 p-5">
            <p class="text-xs text-forest-500 uppercase tracking-wide">Add-ons Revenue</p>
            <p class="text-2xl font-bold text-forest-900 mt-1">RM {{ number_format($totalAddons, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-forest-100 p-5">
            <p class="text-xs text-forest-500 uppercase tracking-wide">Discounts Given</p>
            <p class="text-2xl font-bold text-forest-900 mt-1">RM {{ number_format($totalDiscount, 2) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-forest-100 p-5">
            <p class="text-xs text-forest-500 uppercase tracking-wide">Paid Bookings</p>
            <p class="text-2xl font-bold text-forest-900 mt-1">{{ $bookingCount }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-forest-100 p-5 mb-6">
        <h2 class="text-sm font-semibold text-forest-800 mb-3">Revenue by Facility</h2>
        @forelse ($revenueByFacility as $facilityName => $revenue)
            <div class="flex items-center justify-between py-1.5 text-sm border-b border-forest-50 last:border-0">
                <span class="text-forest-700">{{ $facilityName }}</span>
                <span class="font-medium text-forest-900">RM {{ number_format($revenue, 2) }}</span>
            </div>
        @empty
            <p class="text-sm text-forest-400">No revenue in this period.</p>
        @endforelse
    </div>

    <div class="bg-white rounded-2xl border border-forest-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-forest-50 text-forest-600 text-left">
                <tr>
                    <th class="px-4 py-3">Booking #</th>
                    <th class="px-4 py-3">Facility</th>
                    <th class="px-4 py-3">Guest</th>
                    <th class="px-4 py-3">Check-in</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3">Paid At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-forest-100">
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-forest-700">{{ $booking->booking_number }}</td>
                        <td class="px-4 py-3 text-forest-700">{{ $booking->facility->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-forest-700">{{ $booking->guest_name }}</td>
                        <td class="px-4 py-3 text-forest-500">{{ $booking->check_in->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right font-medium text-forest-900">RM {{ number_format($booking->total_price, 2) }}</td>
                        <td class="px-4 py-3 text-forest-500">{{ $booking->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-8 text-center text-forest-400">No paid bookings in this period.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
