@extends('layouts.admin')

@section('title', 'Bookings - Admin')
@section('page_title', 'Bookings')

@section('content')
    <x-card class="p-6 border-slate-100 mb-6 bg-white shadow-sm">
        <form method="GET" class="flex gap-4 items-end">
            <div class="w-64">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Status</label>
                <x-select name="status" onchange="this.form.submit()" class="bg-slate-50">
                    <option value="">All Statuses</option>
                    @foreach (['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                    @endforeach
                </x-select>
            </div>
            <a href="{{ route('admin.bookings.index') }}" class="inline-flex items-center justify-center h-[42px] px-4 text-sm font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-100 rounded-xl transition-colors">Clear</a>
        </form>
    </x-card>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Reference</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Facility</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guest</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Check-in</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Total</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($bookings as $booking)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-mono text-sm font-bold text-forest-600 group-hover:text-forest-700 transition-colors">{{ $booking->booking_number }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $booking->facility->name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $booking->guest_name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $booking->check_in->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-black text-slate-900 text-base">RM {{ number_format($booking->total_price, 2) }}</td>
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
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-forest-100 hover:text-forest-700 rounded-lg transition-colors">Manage</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-16 text-center text-slate-500 font-medium">No bookings found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>

    <div class="mt-6">{{ $bookings->links() }}</div>
@endsection
