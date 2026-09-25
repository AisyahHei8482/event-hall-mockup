@extends('layouts.app')

@section('title', $package->title.' - Savanna Hill Resort')
@section('meta_description', $package->short_description)

@section('content')
    <section class="h-72 sm:h-96 bg-forest-800 relative overflow-hidden">
        @if ($package->cover_image)
            <img src="{{ Storage::url($package->cover_image) }}" alt="{{ $package->title }}" class="w-full h-full object-cover opacity-80">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 text-white">
            @if ($package->badge)
                <span class="inline-block bg-sand-400 text-xs font-semibold px-3 py-1 rounded-full mb-3">{{ $package->badge }}</span>
            @endif
            <h1 class="text-3xl sm:text-4xl font-bold">{{ $package->title }}</h1>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <p class="text-lg text-forest-700 leading-relaxed">{{ $package->short_description }}</p>
            <div class="prose prose-forest max-w-none mt-6 text-forest-700 whitespace-pre-line">{{ $package->description }}</div>

            @if ($package->facilities->isNotEmpty())
                <div class="mt-10">
                    <h2 class="text-xl font-bold text-forest-900 mb-4">What's Included</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($package->facilities as $facility)
                            <a href="{{ route('facilities.show', $facility) }}" class="flex items-center gap-3 border border-forest-100 rounded-xl px-4 py-3 hover:bg-forest-50 transition">
                                <i class="fa-solid fa-circle-check text-forest-500"></i>
                                <span class="text-sm font-medium text-forest-800">{{ $facility->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <aside class="lg:col-span-1">
            <div class="border border-forest-100 rounded-2xl p-6 shadow-sm sticky top-24">
                <p class="text-2xl font-bold text-forest-900">
                    RM {{ number_format($package->price, 2) }}
                    @if ($package->original_price)
                        <span class="text-sm font-normal text-forest-400 line-through ml-1">RM {{ number_format($package->original_price, 2) }}</span>
                    @endif
                </p>

                @if ($package->facilities->isNotEmpty())
                    <a href="{{ route('bookings.create', $package->facilities->first()) }}" class="mt-6 block text-center bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-3 rounded-full transition">
                        Book This Package
                    </a>
                @endif
                <a href="{{ route('contact.create') }}" class="mt-3 block text-center border border-forest-200 text-forest-700 font-semibold px-6 py-3 rounded-full hover:bg-forest-50 transition">
                    Ask a Question
                </a>
            </div>
        </aside>
    </section>
@endsection
