@extends('layouts.app')

@section('title', 'Resort Accommodations & Activities - Savanna Hill')

@section('content')
    <!-- Hero Section -->
    <section class="relative bg-forest-900 pt-24 pb-32 sm:pt-32 sm:pb-40 overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0">
            <img src="{{ asset('storage/gallery/facilities-hero.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1542314831-c6a4d14d2301?auto=format&fit=crop&q=80'" class="w-full h-full object-cover opacity-40 mix-blend-overlay">
            <div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-forest-900/80 to-forest-900/40"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-forest-800/80 border border-forest-700/50 text-forest-200 text-sm font-semibold tracking-widest uppercase mb-6 shadow-xl backdrop-blur-sm animate-fade-in-down">
                <i class="fa-solid fa-leaf mr-2"></i> Savanna Hill Resort
            </span>
            
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 tracking-tight drop-shadow-lg text-white animate-fade-in-up" style="animation-delay: 100ms;">
                Nature-Inspired Stays & Experiences
            </h1>
            
            <p class="text-lg sm:text-xl text-forest-100 max-w-2xl mx-auto mb-10 drop-shadow-md animate-fade-in-up leading-relaxed" style="animation-delay: 200ms;">
                From cozy rooms and spacious bungalows to outdoor adventures and exquisite dining.
            </p>
        </div>
    </section>

    <!-- Filters -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-20 mb-16">
        <x-card class="p-6 sm:p-8 shadow-2xl border-slate-200/60 backdrop-blur-md bg-white/95">
            <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-5 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Search</label>
                    <div class="relative">
                        <i class="fa-solid fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search facilities..."
                               class="w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-forest-500 focus:border-forest-500 transition-shadow">
                    </div>
                </div>
                
                <div class="sm:w-64">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Category</label>
                    <x-select name="type" class="w-full py-3 bg-slate-50">
                        <option value="">All Categories</option>
                        @foreach (['accommodation' => 'Accommodations', 'facility' => 'Facilities', 'activity' => 'Activities', 'dining' => 'Dining'] as $value => $label)
                            <option value="{{ $value }}" @selected(request('type') === $value)>{{ $label }}</option>
                        @endforeach
                    </x-select>
                </div>
                
                <div class="w-full sm:w-auto">
                    <x-button type="submit" variant="primary" class="w-full sm:w-auto px-8 py-3 shadow-lg shadow-forest-500/30">
                        <i class="fa-solid fa-filter mr-2"></i> Filter
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        @if ($facilities->isEmpty())
            <div class="text-center py-20 bg-slate-50 rounded-3xl border border-slate-200 border-dashed">
                <div class="w-20 h-20 mx-auto bg-slate-100 rounded-full flex items-center justify-center text-slate-400 mb-6">
                    <i class="fa-solid fa-search text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700 mb-2">No results found</h3>
                <p class="text-slate-500 max-w-md mx-auto">We couldn't find any facilities or accommodations matching your current filters. Try adjusting your search criteria.</p>
                <a href="{{ route('facilities.index') }}" class="mt-6 inline-block text-forest-600 font-semibold hover:text-forest-700">Clear all filters &rarr;</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($facilities as $facility)
                    <div class="animate-fade-in-up" style="animation-delay: {{ $loop->index * 100 }}ms">
                        @include('facilities.partials.card', ['facility' => $facility])
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12">
                {{ $facilities->links() }}
            </div>
        @endif
    </section>
@endsection
