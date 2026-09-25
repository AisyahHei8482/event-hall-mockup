@extends('layouts.app')

@section('title', 'Experience - Savanna Hill Resort')
@section('meta_description', 'Your tranquil haven, rural resort. Host elegant weddings, corporate events, and dynamic team-building activities.')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Experience</h1>
            <p class="mt-2 text-forest-300 font-medium">Your Tranquil Haven, Rural Resort</p>
            <p class="mt-3 text-forest-200 max-w-2xl">Host your events in our serene setting, perfect for elegant weddings, corporate events, and dynamic team-building activities. Savor authentic flavours and enjoy a seamless blend of tranquillity and celebration, where every moment is crafted to perfection.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($types as $slug => $meta)
                @php $cover = $experiences->get($slug, collect())->first()?->cover_image; @endphp
                <a href="{{ route('experience.show', $slug) }}" class="group block rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm hover:shadow-lg transition">
                    <div class="h-40 bg-forest-100 overflow-hidden">
                        @if ($cover)
                            <img src="{{ Storage::url($cover) }}" alt="{{ $meta['label'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-forest-300"><i class="fa-solid fa-champagne-glasses text-4xl"></i></div>
                        @endif
                    </div>
                    <div class="p-5">
                        <p class="text-xs font-semibold text-forest-500 uppercase tracking-wide">{{ $meta['tagline'] }}</p>
                        <h3 class="font-bold text-lg text-forest-900 group-hover:text-forest-600 transition">{{ $meta['label'] }}</h3>
                        <span class="text-forest-600 text-sm font-semibold group-hover:underline mt-3 inline-block">Explore &rarr;</span>
                    </div>
                </a>
            @endforeach
        </div>
    </section>
@endsection
