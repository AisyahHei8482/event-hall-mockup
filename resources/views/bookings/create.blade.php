@extends('layouts.app')

@section('title', 'Book '.$facility->name.' - Savanna Hill Resort')

@section('content')
    <div class="min-h-screen bg-slate-50 py-12">
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <span class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4">
                    <i class="fa-solid fa-calendar-check"></i> Booking Request
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4 tracking-tight">Book {{ $facility->name }}</h1>
                <p class="text-slate-500 text-lg max-w-xl mx-auto leading-relaxed">Fill in your details below and our team will confirm your booking shortly.</p>
            </div>

            @if ($errors->any())
                <x-alert variant="danger" class="mb-8">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form method="POST" action="{{ route('bookings.store', $facility) }}">
                @csrf
                <div class="space-y-6">
                    <x-card class="p-8 border-slate-100 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                            Guest Details
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name <span class="text-red-500">*</span></label>
                                <x-input type="text" name="guest_name" value="{{ old('guest_name', auth()->user()->name ?? '') }}" required class="bg-slate-50" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email <span class="text-red-500">*</span></label>
                                <x-input type="email" name="guest_email" value="{{ old('guest_email', auth()->user()->email ?? '') }}" required class="bg-slate-50" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                                <x-input type="text" name="guest_phone" value="{{ old('guest_phone') }}" class="bg-slate-50" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Number of Guests <span class="text-red-500">*</span></label>
                                <x-input type="number" name="guests" min="1" max="{{ $facility->capacity ?? 999 }}" value="{{ old('guests', 1) }}" required class="bg-slate-50" />
                            </div>
                        </div>
                    </x-card>

                    <x-card class="p-8 border-slate-100 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                            Stay Details
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Check-in Date <span class="text-red-500">*</span></label>
                                <x-input type="date" name="check_in" value="{{ old('check_in') }}" required min="{{ date('Y-m-d') }}" class="bg-slate-50" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Check-out Date (optional)</label>
                                <x-input type="date" name="check_out" value="{{ old('check_out') }}" class="bg-slate-50" />
                            </div>
                        </div>
                        
                        @if ($addons->isNotEmpty())
                            <div class="mt-6 border-t border-slate-100 pt-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Add-ons</label>
                                <div class="space-y-3">
                                    @foreach ($addons as $addon)
                                        <label class="flex items-center justify-between gap-4 border-2 border-slate-100 rounded-2xl p-4 cursor-pointer hover:border-emerald-300 transition-colors bg-white">
                                            <span class="flex items-center gap-4">
                                                <input type="checkbox" name="addons[]" value="{{ $addon->id }}"
                                                       @checked(in_array($addon->id, old('addons', []))) class="w-5 h-5 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                                                <span>
                                                    <span class="font-bold text-slate-900 block">{{ $addon->name }}</span>
                                                    @if ($addon->description)
                                                        <span class="block text-sm text-slate-500 mt-1">{{ $addon->description }}</span>
                                                    @endif
                                                </span>
                                            </span>
                                            <span class="text-emerald-700 font-bold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 shrink-0">RM {{ number_format($addon->price, 2) }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </x-card>

                    <x-card class="p-8 border-slate-100 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 tracking-tight flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                            Additional Info
                        </h2>
                        <div class="space-y-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Promo Code</label>
                                <x-input type="text" name="promo_code" value="{{ old('promo_code') }}" placeholder="Have a promo code?" class="bg-slate-50" />
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Special Requests</label>
                                <textarea name="special_requests" rows="4"
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 resize-none bg-slate-50" placeholder="Any dietary requirements, setup preferences, etc.">{{ old('special_requests') }}</textarea>
                            </div>
                        </div>
                    </x-card>

                    <div class="pt-4">
                        <x-button type="submit" variant="primary" size="lg" class="w-full justify-center text-lg py-4 shadow-xl">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Submit Booking Request
                        </x-button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
