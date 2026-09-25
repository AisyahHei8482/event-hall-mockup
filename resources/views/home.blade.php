@extends('layouts.app')

@section('title', 'Savanna Hill Resort - Truly Different Within a Traditional Neighbourhood')

@section('content')

    <!-- Hero Section -->
    <section class="relative max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4 sm:mt-8">
        <div class="relative bg-forest-900 rounded-3xl overflow-hidden shadow-2xl min-h-[600px] flex items-center">
            <!-- Simulated background image with gradient overlay -->
            <div class="absolute inset-0 bg-gradient-to-br from-forest-800 to-forest-900 opacity-90"></div>
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542314831-c6a4d14d2301?auto=format&fit=crop&q=80')] bg-cover bg-center mix-blend-overlay opacity-30"></div>
            
            <div class="relative z-10 w-full px-8 py-20 lg:py-32 flex flex-col items-center text-center">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-sand-300 text-xs font-semibold uppercase tracking-widest backdrop-blur-md mb-8 animate-fade-in-down">
                    <i class="fa-solid fa-location-dot"></i> Sungai Tiram · Johor
                </span>
                
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white max-w-4xl leading-tight mb-6 drop-shadow-lg animate-fade-in-up" style="animation-delay: 100ms;">
                    Truly Different Within a Traditional Neighbourhood
                </h1>
                
                <p class="text-lg sm:text-xl text-forest-100 max-w-2xl mb-12 drop-shadow-md animate-fade-in-up" style="animation-delay: 200ms;">
                    Relax • Stay • Celebrate. Discover nature dining, outdoor sports, and unforgettable group experiences just 27km from JB city centre.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 animate-fade-in-up" style="animation-delay: 300ms;">
                    <a href="{{ route('facilities.index') }}">
                        <x-button variant="primary" size="lg" class="w-full sm:w-auto text-base">
                            Explore Resort
                        </x-button>
                    </a>
                    <a href="{{ route('event-halls.index') }}">
                        <x-button variant="amber" size="lg" class="w-full sm:w-auto text-base">
                            <i class="fa-solid fa-building mr-2"></i> Event Halls
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 mt-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <x-card class="p-6 text-center group hover:bg-forest-50 transition-colors">
                <div class="w-12 h-12 mx-auto rounded-full bg-forest-100 text-forest-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-map-location-dot text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-forest-800">27km</p>
                <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-wide">From City Centre</p>
            </x-card>
            <x-card class="p-6 text-center group hover:bg-forest-50 transition-colors">
                <div class="w-12 h-12 mx-auto rounded-full bg-forest-100 text-forest-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-forest-800">200+</p>
                <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-wide">Guest Capacity</p>
            </x-card>
            <x-card class="p-6 text-center group hover:bg-forest-50 transition-colors">
                <div class="w-12 h-12 mx-auto rounded-full bg-forest-100 text-forest-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-compass text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-forest-800">6+</p>
                <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-wide">Experiences</p>
            </x-card>
            <x-card class="p-6 text-center group hover:bg-forest-50 transition-colors">
                <div class="w-12 h-12 mx-auto rounded-full bg-forest-100 text-forest-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <i class="fa-solid fa-leaf text-xl"></i>
                </div>
                <p class="text-3xl font-bold text-forest-800">100%</p>
                <p class="text-sm font-medium text-slate-500 mt-1 uppercase tracking-wide">Nature Immersed</p>
            </x-card>
        </div>
    </section>

    <!-- Accommodation Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div class="max-w-2xl">
                <h2 class="text-3xl md:text-4xl font-bold text-forest-900 tracking-tight">Stay & Relax</h2>
                <p class="text-lg text-slate-600 mt-4 leading-relaxed">Take a fresh and greenery moment with full relaxation from the hustle and bustle of the city at any of our available accommodation.</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
            @foreach ($accommodationTypes as $slug => $label)
                <a href="{{ route('accommodation.show', $slug) }}" class="block group">
                    <x-card class="h-full p-6 flex flex-col items-center justify-center text-center transition-all duration-300 hover:shadow-lg hover:-translate-y-1 hover:border-forest-200">
                        <div class="w-14 h-14 rounded-full bg-sand-100 text-sand-600 flex items-center justify-center mb-4 group-hover:bg-forest-600 group-hover:text-white transition-colors">
                            <i class="fa-solid fa-bed text-xl"></i>
                        </div>
                        <h3 class="font-semibold text-slate-800 group-hover:text-forest-700 transition-colors">{{ $label }}</h3>
                    </x-card>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Experience Section -->
    <section class="bg-forest-50 py-20 mt-12 rounded-t-[3rem]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-14">
                <h2 class="text-3xl md:text-4xl font-bold text-forest-900 tracking-tight">Your Tranquil Haven</h2>
                <p class="text-lg text-slate-600 mt-4 leading-relaxed">Host your events in our serene setting, perfect for elegant weddings, corporate events, and dynamic team-building activities.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($experienceTypes as $slug => $meta)
                    <a href="{{ route('experience.show', $slug) }}" class="block group">
                        <x-card class="h-full overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="h-40 bg-forest-100 flex items-center justify-center relative overflow-hidden">
                                <!-- Subtle overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-forest-900/60 to-transparent z-10"></div>
                                <i class="fa-solid fa-camera text-4xl text-forest-200/50 absolute"></i>
                                <div class="absolute bottom-4 left-4 z-20">
                                    <p class="text-xs font-bold text-sand-300 uppercase tracking-wider mb-1">{{ $meta['tagline'] }}</p>
                                    <h3 class="text-xl font-bold text-white">{{ $meta['label'] }}</h3>
                                </div>
                            </div>
                            <div class="p-5 bg-white flex justify-between items-center">
                                <span class="text-sm font-medium text-slate-500 group-hover:text-forest-600 transition-colors">Explore Experience</span>
                                <div class="w-8 h-8 rounded-full bg-forest-50 flex items-center justify-center group-hover:bg-forest-100 group-hover:text-forest-700 transition-colors">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </x-card>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if ($featuredFacilities->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-forest-900 tracking-tight">Featured Facilities</h2>
                    <p class="text-lg text-slate-600 mt-3">Handpicked facilities and activities our guests love most.</p>
                </div>
                <a href="{{ route('facilities.index') }}">
                    <x-button variant="outline">
                        View All Facilities <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                    </x-button>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($featuredFacilities as $facility)
                    @include('facilities.partials.card', ['facility' => $facility])
                @endforeach
            </div>
        </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 px-4">
        <div class="max-w-5xl mx-auto relative rounded-3xl overflow-hidden shadow-xl bg-forest-900">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1542314831-c6a4d14d2301?auto=format&fit=crop&q=80')] bg-cover bg-center mix-blend-overlay opacity-20"></div>
            <div class="relative z-10 px-6 py-16 md:py-24 text-center flex flex-col items-center">
                <h2 class="text-3xl md:text-5xl font-bold text-white tracking-tight mb-6">Ready to plan your escape?</h2>
                <p class="text-lg text-forest-100 max-w-2xl mb-10">Browse our facilities and book your next getaway, corporate retreat, or family adventure today.</p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('facilities.index') }}">
                        <x-button variant="primary" size="lg" class="bg-white text-forest-900 hover:bg-forest-50 w-full sm:w-auto">
                            Start Booking
                        </x-button>
                    </a>
                    <a href="{{ route('contact.create') }}">
                        <x-button variant="outline" size="lg" class="border-white/30 text-white hover:bg-white/10 w-full sm:w-auto">
                            Contact Us
                        </x-button>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection
