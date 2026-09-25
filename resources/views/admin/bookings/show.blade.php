@extends('layouts.admin')

@section('title', 'Booking '.$booking->booking_number.' - Admin')
@section('page_title', 'Booking '.$booking->booking_number)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-2xl border border-forest-100 p-6 space-y-3 text-sm">
            <div class="flex justify-between"><span class="text-forest-500">Facility</span><span class="font-medium">{{ $booking->facility->name }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Guest Name</span><span class="font-medium">{{ $booking->guest_name }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Email</span><span class="font-medium">{{ $booking->guest_email }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Phone</span><span class="font-medium">{{ $booking->guest_phone ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Check-in</span><span class="font-medium">{{ $booking->check_in->format('d M Y') }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Check-out</span><span class="font-medium">{{ $booking->check_out?->format('d M Y') ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Guests</span><span class="font-medium">{{ $booking->guests }}</span></div>
            <div class="flex justify-between"><span class="text-forest-500">Total Price</span><span class="font-bold">RM {{ number_format($booking->total_price, 2) }}</span></div>
            @if ($booking->special_requests)
                <div>
                    <span class="text-forest-500 block mb-1">Special Requests</span>
                    <p class="bg-forest-50 rounded-lg p-3">{{ $booking->special_requests }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-forest-100 p-6">
            <h2 class="font-bold text-forest-900 mb-4">Update Status</h2>
            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Booking Status</label>
                    <select name="status" class="w-full border border-forest-200 rounded-lg px-4 py-2.5">
                        @foreach (['pending', 'confirmed', 'checked_in', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Payment Status</label>
                    <select name="payment_status" class="w-full border border-forest-200 rounded-lg px-4 py-2.5">
                        @foreach (['unpaid', 'paid', 'refunded'] as $status)
                            <option value="{{ $status }}" @selected($booking->payment_status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                    Save Changes
                </button>
            </form>
        </div>
    </div>
@endsection
