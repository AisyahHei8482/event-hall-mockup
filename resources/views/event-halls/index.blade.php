@extends('layouts.eventhall-demo')

@section('title', 'Event Halls & Venues — Savanna Hill Resort')
@section('description', 'Explore our premium event halls and venue spaces. Perfect for weddings, corporate events, and celebrations.')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero -->
    <section class="relative bg-slate-900 text-white py-24 lg:py-32 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 opacity-90 z-0"></div>
        <div class="absolute inset-0 bg-[url('/images/hero-bg.jpg')] bg-cover bg-center opacity-20 mix-blend-overlay z-0"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center">
            <span class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-brand-400 mb-6 animate-fade-in-down">
                <i class="fa-solid fa-building-columns"></i> Premium Venues
            </span>
            
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 tracking-tight drop-shadow-lg animate-fade-in-up" style="animation-delay: 100ms;">
                Premium Event Venues
            </h1>
            
            <p class="text-lg sm:text-xl text-slate-300 max-w-2xl mx-auto mb-10 drop-shadow-md animate-fade-in-up leading-relaxed" style="animation-delay: 200ms;">
                From intimate gatherings to grand celebrations — our venues are designed to make your event unforgettable.
            </p>
            
            <div class="animate-fade-in-up" style="animation-delay: 300ms;">
                <a href="{{ route('quotations.create') }}">
                    <button type="button" class="inline-flex items-center justify-center px-8 py-3 bg-brand-500 hover:bg-brand-600 text-white font-bold rounded-full shadow-xl shadow-brand-500/20 text-base transition-colors">
                        <i class="fa-solid fa-calculator mr-2"></i> Get Instant Quote
                    </button>
                </a>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 relative z-20 mb-16">
        <x-card class="p-6 sm:p-8 shadow-xl border-slate-200/60 backdrop-blur-md bg-white/95">
            <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-5 items-end">
                <!-- Franchise filter removed -->
                <div class="flex-1 w-full sm:w-auto min-w-[150px]">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Hall Type</label>
                    <x-select name="type" class="w-full">
                        <option value="">All Types</option>
                        @foreach($hallTypes as $type)
                        <option value="{{ $type }}" @selected(request('type')===$type)>{{ $type }}</option>
                        @endforeach
                    </x-select>
                </div>
                <div class="flex-1 w-full sm:w-auto min-w-[120px]">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Min Capacity</label>
                    <x-input type="number" name="capacity" value="{{ request('capacity') }}" min="1" placeholder="e.g. 100" class="w-full" />
                </div>
                <div class="flex gap-3 w-full sm:w-auto">
                    <x-button type="submit" variant="primary" class="flex-1 sm:flex-none">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i> Search
                    </x-button>
                    @if(request()->hasAny(['franchise','type','capacity']))
                        <a href="{{ route('event-halls.index') }}" class="flex-1 sm:flex-none">
                            <x-button variant="ghost" class="w-full">Clear</x-button>
                        </a>
                    @endif
                </div>
            </form>
        </x-card>
    </div>

    <!-- Halls Grid -->
    <div class="max-w-6xl mx-auto px-4 pb-20">
        <!-- Franchise Sections -->
        @if(!request()->hasAny(['franchise','type','capacity']))
        @foreach($franchises->filter(fn($f) => $f->activeHalls->isNotEmpty()) as $franchise)
        <div class="mb-12">
            <div class="flex items-center gap-3 mb-6">
                @if($franchise->logo)
                <img src="{{ asset('media/'.$franchise->logo) }}" class="w-10 h-10 rounded-xl object-cover">
                @else
                <div class="w-10 h-10 rounded-xl bg-slate-200 flex items-center justify-center text-slate-400">
                    <i class="fa-solid fa-building"></i>
                </div>
                @endif
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $franchise->name }}</h2>
                    @if($franchise->address)<p class="text-sm text-slate-400">{{ $franchise->address }}</p>@endif
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($franchise->activeHalls as $hall)
                @include('event-halls._card', ['hall' => $hall])
                @endforeach
            </div>
        </div>
        @endforeach
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($halls as $hall)
            @include('event-halls._card', ['hall' => $hall])
            @empty
            <div class="col-span-3 text-center py-20 text-slate-400">
                <i class="fa-solid fa-search text-4xl mb-4 block"></i>
                <p class="text-lg">No halls found matching your criteria.</p>
                <a href="{{ route('event-halls.index') }}" class="text-amber-600 hover:underline mt-2 inline-block">Clear filters</a>
            </div>
            @endforelse
        </div>
        @if($halls->hasPages())<div class="mt-8">{{ $halls->links() }}</div>@endif
        @endif
    </div>
</div>
@endsection
