@extends('layouts.app')

@section('title', 'Booking Confirmation - Savanna Hill Resort')

@section('content')
    <div class="min-h-screen bg-slate-50 py-12">
        <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-lg shadow-emerald-500/20">
                <i class="fa-solid fa-check text-4xl"></i>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-slate-900 mt-6 tracking-tight">Booking Request Received</h1>
            <p class="text-slate-600 mt-3 text-lg leading-relaxed">Your booking reference is <span class="font-mono font-bold text-slate-900 bg-slate-200 px-2 py-0.5 rounded-md">{{ $booking->booking_number }}</span>.<br>We've sent the details to <strong>{{ $booking->guest_email }}</strong>.</p>

            <x-card class="mt-10 p-8 border-slate-100 shadow-sm text-left">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6 border-b border-slate-100 pb-4">Booking Summary</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Facility</span><span class="font-bold text-slate-900">{{ $booking->facility->name }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Check-in</span><span class="font-bold text-slate-900">{{ $booking->check_in->format('d M Y') }}</span></div>
                    @if ($booking->check_out)
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Check-out</span><span class="font-bold text-slate-900">{{ $booking->check_out->format('d M Y') }}</span></div>
                    @endif
                    <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Guests</span><span class="font-bold text-slate-900">{{ $booking->guests }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Status</span><span class="font-bold text-slate-900 capitalize">{{ $booking->status }}</span></div>
                    <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Payment</span>
                        <x-badge variant="{{ $booking->payment_status === 'paid' ? 'emerald' : 'amber' }}" class="capitalize">
                            {{ $booking->payment_status }}
                        </x-badge>
                    </div>
                    @if ($booking->addons->isNotEmpty())
                        <div class="border-t border-slate-100 pt-4 mt-2">
                            <span class="text-slate-500 font-medium block mb-3">Add-ons</span>
                            @foreach ($booking->addons as $addon)
                                <div class="flex justify-between text-sm mb-2"><span class="font-medium text-slate-700">{{ $addon->name }}</span><span class="font-bold text-slate-900">RM {{ number_format($addon->pivot->price_at_booking, 2) }}</span></div>
                            @endforeach
                        </div>
                    @endif
                    <div class="flex justify-between items-center border-t border-slate-100 pt-4 mt-2"><span class="text-slate-500 font-medium">Subtotal</span><span class="font-bold text-slate-900">RM {{ number_format($booking->subtotal, 2) }}</span></div>
                    @if ($booking->discount_amount > 0)
                        <div class="flex justify-between items-center"><span class="text-slate-500 font-medium">Discount{{ $booking->promotion ? ' ('.$booking->promotion->code.')' : '' }}</span><span class="font-bold text-emerald-600">- RM {{ number_format($booking->discount_amount, 2) }}</span></div>
                    @endif
                    <div class="flex justify-between items-center border-t border-slate-100 pt-4 mt-2"><span class="text-slate-900 font-bold uppercase tracking-wide">Total Amount</span><span class="font-bold text-2xl text-emerald-600 tracking-tight">RM {{ number_format($booking->total_price, 2) }}</span></div>
                </div>
            </x-card>

            <div class="mt-8 flex flex-col items-center gap-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 inline-block shadow-sm">
                    {!! $qrCode !!}
                </div>
                <p class="text-sm font-medium text-slate-500">Show this QR code at check-in for quick verification.</p>
            </div>

            <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
                @if ($booking->payment_status !== 'paid')
                    <a href="{{ route('bookings.pay', $booking) }}" class="inline-block w-full sm:w-auto">
                        <x-button variant="primary" class="w-full justify-center px-8 py-3 bg-emerald-600 hover:bg-emerald-700 h-auto">Pay Now</x-button>
                    </a>
                @endif
                @auth
                    @if ($booking->user_id === auth()->id())
                        <a href="{{ route('guest.bookings.receipt', $booking) }}" class="inline-block w-full sm:w-auto">
                            <x-button variant="outline" class="w-full justify-center px-8 py-3 bg-white h-auto"><i class="fa-solid fa-download mr-2"></i> Download Receipt</x-button>
                        </a>
                    @endif
                @endauth
                <a href="{{ route('facilities.index') }}" class="inline-block w-full sm:w-auto">
                    <x-button variant="outline" class="w-full justify-center px-8 py-3 bg-white h-auto">Browse More Experiences</x-button>
                </a>
            </div>
        </section>
    </div>
@endsection
