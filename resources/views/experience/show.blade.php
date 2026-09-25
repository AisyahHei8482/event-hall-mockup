@extends('layouts.app')

@section('title', $meta['label'].' - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('experience.index') }}" class="text-forest-300 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> All Experiences</a>
            <p class="mt-3 text-forest-300 font-medium uppercase text-xs tracking-wide">{{ $meta['tagline'] }}</p>
            <h1 class="text-4xl font-bold">{{ $meta['label'] }}</h1>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if ($facilities->isEmpty())
            <p class="text-forest-500">No {{ strtolower($meta['label']) }} experiences available right now. Please check back soon or contact us to inquire.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($facilities as $facility)
                    @include('facilities.partials.card', ['facility' => $facility])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $facilities->links() }}
            </div>
        @endif

        <div class="mt-14 border border-forest-100 rounded-2xl p-8 text-center bg-forest-50">
            <h2 class="text-xl font-bold text-forest-900">Planning something special?</h2>
            <p class="text-forest-600 mt-2">Get in touch with our events team for a tailored quote.</p>
            <a href="{{ route('contact.create') }}" class="mt-5 inline-block bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-3 rounded-full transition">
                Inquire Now
            </a>
        </div>
    </section>
@endsection
