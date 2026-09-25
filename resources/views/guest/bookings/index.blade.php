@extends('layouts.app')

@section('title', 'My Bookings - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">My Bookings</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">View and manage your reservations with Savanna Hill Resort.</p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (session('success'))
            <div class="mb-6 bg-forest-100 border border-forest-300 text-forest-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($bookings as $booking)
            <div class="border border-forest-100 rounded-2xl p-6 mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="text-xs text-forest-400 font-mono">{{ $booking->booking_number }}</p>
                    <p class="font-bold text-forest-900 mt-1">
                        {{ $booking->facility->name ?? $booking->package->title ?? 'Booking' }}
                    </p>
                    <p class="text-sm text-forest-600 mt-1">
                        {{ $booking->check_in->format('d M Y') }}
                        @if ($booking->check_out)
                            &rarr; {{ $booking->check_out->format('d M Y') }}
                        @endif
                        &middot; {{ $booking->guests }} guest(s)
                    </p>
                    <p class="text-sm text-forest-800 font-semibold mt-1">RM {{ number_format($booking->total_price, 2) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-forest-100 text-forest-700 capitalize">{{ $booking->status }}</span>
                    <span class="text-xs font-semibold px-3 py-1.5 rounded-full bg-sand-100 text-sand-700 capitalize">{{ $booking->payment_status }}</span>
                    @if ($booking->payment_status !== 'paid' && !in_array($booking->status, ['completed', 'cancelled']))
                        <a href="{{ route('bookings.pay', $booking) }}" class="text-forest-600 text-sm font-semibold hover:underline">Pay Now</a>
                    @endif
                    <a href="{{ route('guest.bookings.receipt', $booking) }}" class="text-forest-600 text-sm font-semibold hover:underline">Download Receipt</a>
                    @if (!in_array($booking->status, ['completed', 'cancelled']))
                        <form method="POST" action="{{ route('guest.bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?');">
                            @csrf
                            <button type="submit" class="text-red-600 text-sm font-semibold hover:underline">Cancel</button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-forest-500">You have no bookings yet. <a href="{{ route('facilities.index') }}" class="text-forest-600 underline">Browse facilities</a> to get started.</p>
        @endforelse

        <div class="mt-6">{{ $bookings->links() }}</div>
    </section>
@endsection
