@extends('layouts.app')

@section('title', 'Accommodation - Savanna Hill Resort')
@section('meta_description', 'Take a fresh and greenery moment with full relaxation at Savanna Hill Resort\'s accommodation.')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Accommodation</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Take a fresh and greenery moment with full relaxation from the hustle and bustle of the city at any of our available accommodation.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 space-y-16">
        @foreach ($types as $slug => $label)
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-forest-900">{{ $label }}</h2>
                    <a href="{{ route('accommodation.show', $slug) }}" class="text-forest-600 font-semibold text-sm hover:underline">View all &rarr;</a>
                </div>

                @php $items = $accommodations->get($slug, collect()); @endphp

                @if ($items->isEmpty())
                    <p class="text-forest-500 text-sm">Coming soon.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach ($items->take(3) as $facility)
                            @include('facilities.partials.card', ['facility' => $facility])
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    </section>
@endsection
