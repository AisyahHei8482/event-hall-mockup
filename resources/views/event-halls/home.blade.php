@extends('layouts.eventhall-demo')
@section('title', 'Premium Event Venues | Savanna Hill')

@section('content')

{{-- Hero Section --}}
<div class="relative bg-slate-900 overflow-hidden">
    <div class="absolute inset-0">
        @if($hall && $hall->cover_image)
            <img src="{{ asset('storage/'.$hall->cover_image) }}" alt="Hero Background" class="w-full h-full object-cover opacity-40 mix-blend-overlay">
        @else
            <div class="w-full h-full bg-gradient-to-r from-slate-900 to-slate-800 opacity-90"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/60 to-transparent"></div>
    </div>
    
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-48 flex flex-col items-center text-center">
        <span class="px-4 py-1.5 rounded-full bg-white/20 text-white border border-white/30 text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md shadow-lg">
            Event Venue Booking Platform
        </span>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white tracking-tight leading-tight mb-8 max-w-4xl">
            Where Unforgettable <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-orange-300">Moments</span> Begin
        </h1>
        <p class="text-lg md:text-xl text-slate-300 font-medium mb-10 max-w-2xl leading-relaxed">
            Discover our premium event spaces designed for weddings, corporate galas, and grand celebrations.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto">
            <a href="{{ route('event-halls.index') }}" class="w-full sm:w-auto px-8 py-4 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-2xl shadow-lg shadow-brand-500/30 transition-all hover:-translate-y-1 flex items-center justify-center gap-3 text-lg">
                Explore Venues <i class="fa-solid fa-arrow-right"></i>
            </a>
            @if($hall)
            <a href="{{ route('event-halls.show', $hall) }}" class="w-full sm:w-auto px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-bold rounded-2xl backdrop-blur-md border border-white/10 transition-all flex items-center justify-center gap-3 text-lg">
                View {{ $hall->name }}
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Featured Venues --}}
<div class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-sm font-bold text-brand-600 uppercase tracking-widest mb-3">Our Spaces</h2>
            <h3 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Available Venues</h3>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($halls as $h)
            <a href="{{ route('event-halls.show', $h) }}" class="group block rounded-3xl bg-white border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden hover:shadow-2xl hover:shadow-brand-500/10 transition-all hover:-translate-y-1">
                <div class="aspect-[4/3] relative overflow-hidden bg-slate-100">
                    @if($h->cover_image)
                        <img src="{{ asset('storage/'.$h->cover_image) }}" alt="{{ $h->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-slate-300">
                            <i class="fa-regular fa-image text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-white">
                        <span class="font-bold text-lg">{{ $h->name }}</span>
                        @if($h->capacity)
                            <span class="flex items-center gap-2 text-sm font-semibold bg-white/20 backdrop-blur-md px-3 py-1 rounded-lg">
                                <i class="fa-solid fa-users"></i> {{ $h->capacity }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-6">
                    <p class="text-slate-600 font-medium text-sm line-clamp-2 mb-4">{{ $h->description ?? 'Premium event space for your next grand occasion.' }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                        <span class="text-brand-600 font-bold text-sm uppercase tracking-wider flex items-center gap-2 group-hover:translate-x-1 transition-transform">
                            View Details <i class="fa-solid fa-arrow-right"></i>
                        </span>
                    </div>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center py-12 text-slate-500">
                No active venues available in the demo right now.
            </div>
            @endforelse
        </div>
    </div>
</div>

{{-- Features --}}
<div class="py-24 bg-slate-50 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-brand-100 text-brand-600 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm shadow-brand-500/20">
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Custom Packages</h4>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Tailor your event with our extensive list of add-ons, catering options, and setup configurations.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-brand-100 text-brand-600 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm shadow-brand-500/20">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Instant Quotations</h4>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Generate instant pricing estimates online and collaborate directly with our sales team.</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 mx-auto bg-brand-100 text-brand-600 rounded-2xl flex items-center justify-center text-2xl mb-6 shadow-sm shadow-brand-500/20">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <h4 class="text-xl font-bold text-slate-900 mb-3">Real-time Booking</h4>
                <p class="text-slate-600 font-medium text-sm leading-relaxed">Check availability instantly and secure your dates with our seamless online booking platform.</p>
            </div>
        </div>
    </div>
</div>

@endsection
