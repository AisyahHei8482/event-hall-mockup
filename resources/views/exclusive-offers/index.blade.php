@extends('layouts.app')

@section('title', 'Exclusive Offers - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Exclusive Offers</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Dive into nature's charm with our current promotions and packages.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if ($offers->isEmpty())
            <p class="text-forest-500">No active promotions right now. Check back soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8" x-data="{ open: null }">
                @foreach ($offers as $offer)
                    <div class="rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm">
                        <div class="h-52 bg-forest-100">
                            @if ($offer->image)
                                <img src="{{ Storage::url($offer->image) }}" alt="{{ $offer->title }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-forest-300"><i class="fa-solid fa-tags text-4xl"></i></div>
                            @endif
                        </div>
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-forest-900">{{ $offer->title }}</h2>
                            <p class="text-forest-600 mt-2 text-sm leading-relaxed">{{ $offer->description }}</p>

                            @if ($offer->code)
                                <p class="mt-3 text-sm font-semibold text-forest-700">Code: <span class="bg-forest-50 px-2 py-1 rounded">{{ $offer->code }}</span></p>
                            @endif

                            @if ($offer->terms)
                                <button type="button" @click="open = open === {{ $offer->id }} ? null : {{ $offer->id }}" class="mt-3 text-xs text-forest-500 hover:underline">
                                    Terms & Conditions <span x-text="open === {{ $offer->id }} ? '▲' : '▼'"></span>
                                </button>
                                <p x-show="open === {{ $offer->id }}" x-cloak class="text-xs text-forest-500 mt-2 whitespace-pre-line">{{ $offer->terms }}</p>
                            @endif

                            <a href="{{ route('facilities.index') }}" class="mt-5 inline-block bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                                Book Now
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    @if ($packages->isNotEmpty())
        <section class="bg-forest-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-forest-900 mb-8">Packages</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($packages as $package)
                        <a href="{{ route('packages.show', $package) }}" class="group block rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm hover:shadow-lg transition">
                            <div class="h-32 bg-forest-100 overflow-hidden">
                                @if ($package->cover_image)
                                    <img src="{{ Storage::url($package->cover_image) }}" alt="{{ $package->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-forest-900 group-hover:text-forest-600 transition text-sm">{{ $package->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($accommodations->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <h2 class="text-2xl font-bold text-forest-900 mb-8">Explore Our Accommodation</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($accommodations->take(3) as $facility)
                    @include('facilities.partials.card', ['facility' => $facility])
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('accommodation.index') }}" class="text-forest-600 font-semibold hover:underline">View all accommodation &rarr;</a>
            </div>
        </section>
    @endif
@endsection
