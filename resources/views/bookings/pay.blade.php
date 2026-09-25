@extends('layouts.app')

@section('title', 'Payment - Savanna Hill Resort')

@section('content')
    <div class="min-h-screen bg-slate-50 py-12">
        <section class="max-w-lg mx-auto px-4 sm:px-6 lg:px-8">
            <x-card class="p-8 md:p-10 border-slate-100 shadow-sm">
                <div class="flex items-center gap-2 text-emerald-600 text-sm font-bold uppercase tracking-widest mb-6">
                    <i class="fa-solid fa-lock"></i> Secure Checkout (Sandbox)
                </div>

                <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Complete Your Payment</h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">Booking reference <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded-md">{{ $booking->booking_number }}</span></p>

                <div class="mt-8 bg-emerald-50 rounded-2xl p-5 border border-emerald-100 flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                    <span class="text-emerald-800 text-sm font-bold uppercase tracking-widest">Amount Due</span>
                    <span class="text-3xl font-bold text-emerald-700 tracking-tight">RM {{ number_format($booking->total_price, 2) }}</span>
                </div>

                <x-alert variant="warning" class="mt-6">
                    This is a demo payment gateway for testing purposes. No real charges are made and no card details are collected.
                </x-alert>

                <form method="POST" action="{{ route('bookings.pay.process', $booking) }}" class="mt-8 space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Card Number</label>
                        <x-input type="text" value="4242 4242 4242 4242" disabled class="bg-slate-100 text-slate-400 font-mono" />
                    </div>
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Expiry</label>
                            <x-input type="text" value="12/30" disabled class="bg-slate-100 text-slate-400 font-mono" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">CVC</label>
                            <x-input type="text" value="123" disabled class="bg-slate-100 text-slate-400 font-mono" />
                        </div>
                    </div>
                    <div class="pt-4">
                        <x-button type="submit" variant="primary" size="lg" class="w-full justify-center text-lg py-4 shadow-xl">
                            Simulate Payment
                        </x-button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('bookings.confirmation', $booking) }}" class="inline-block text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">
                        Pay later
                    </a>
                </div>
            </x-card>
        </section>
    </div>
@endsection
