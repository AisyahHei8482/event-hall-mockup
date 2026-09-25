@extends('layouts.app')

@section('title', 'My Rewards - Savanna Hill Resort')

@section('content')
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h1 class="text-3xl font-bold text-forest-900 mb-2">My Rewards</h1>
        <p class="text-forest-600 mb-10">Earn 1 loyalty point for every RM10 spent on paid bookings.</p>

        <div class="bg-forest-800 text-white rounded-2xl p-8 mb-10">
            <p class="text-forest-200 text-sm uppercase tracking-wide">Current Tier</p>
            <p class="text-4xl font-bold mt-1">{{ $user->loyaltyTier() }}</p>
            <p class="mt-4 text-forest-100">{{ number_format($user->loyalty_points) }} points</p>

            @if ($nextTier)
                <div class="mt-6">
                    @php
                        $currentThreshold = collect(\App\Models\User::TIERS)->filter(fn ($t) => $t <= $user->loyalty_points)->last() ?? 0;
                        $progress = min(100, round((($user->loyalty_points - $currentThreshold) / max(1, $nextTier['threshold'] - $currentThreshold)) * 100));
                    @endphp
                    <div class="w-full bg-forest-900/40 rounded-full h-2.5">
                        <div class="bg-sand-400 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                    <p class="text-xs text-forest-200 mt-2">{{ $nextTier['threshold'] - $user->loyalty_points }} points to reach {{ $nextTier['name'] }}</p>
                </div>
            @else
                <p class="mt-4 text-sand-300 text-sm">You've reached our highest tier &mdash; thank you for being a loyal guest!</p>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach ($tiers as $tier)
                <div class="border rounded-2xl p-6 text-center {{ $user->loyaltyTier() === $tier['name'] ? 'border-forest-600 bg-forest-50' : 'border-forest-100' }}">
                    <p class="font-bold text-lg text-forest-900">{{ $tier['name'] }}</p>
                    <p class="text-sm text-forest-500 mt-1">{{ $tier['threshold'] }}+ points</p>
                </div>
            @endforeach
        </div>
    </section>
@endsection
