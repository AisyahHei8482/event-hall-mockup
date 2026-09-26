<x-card class="group flex flex-col h-full overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-slate-100">
    @if($hall->cover_image)
    <div class="h-56 overflow-hidden relative">
        <img src="{{ asset('media/'.$hall->cover_image) }}" alt="{{ $hall->name }}"
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 ease-out">
        @if($hall->hall_type)
        <div class="absolute top-3 left-3">
            <span class="bg-white/95 backdrop-blur-sm shadow-sm text-forest-800 text-xs font-bold px-3 py-1.5 rounded-full capitalize tracking-wide">{{ $hall->hall_type }}</span>
        </div>
        @endif
    </div>
    @else
    <div class="h-56 bg-gradient-to-br from-amber-50 to-amber-100 flex flex-col items-center justify-center group-hover:scale-110 transition-transform duration-700 ease-out">
        <i class="fa-solid fa-door-open text-6xl text-amber-200 mb-2"></i>
    </div>
    @endif

    <div class="p-6 flex flex-col flex-1 bg-white relative">
        <div class="flex items-start justify-between mb-3">
            <h3 class="font-bold text-slate-900 text-xl group-hover:text-amber-700 transition-colors tracking-tight">{{ $hall->name }}</h3>
        </div>
        @if($hall->description)
        <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed mb-4">{{ $hall->description }}</p>
        @endif

        <div class="flex flex-wrap gap-4 text-xs font-medium text-slate-500 mb-6 mt-auto pt-4 border-t border-slate-100">
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-people-group text-amber-500 text-sm"></i> {{ $hall->capacity }} pax</span>
            @if($hall->floor_area)
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-ruler-combined text-amber-500 text-sm"></i> {{ $hall->floor_area }} m²</span>
            @endif
            <span class="flex items-center gap-1.5"><i class="fa-solid fa-clock text-amber-500 text-sm"></i> Min {{ $hall->min_booking_hours }}hr</span>
        </div>

        <!-- Price preview -->
        @if($hall->pricingRules->isNotEmpty())
        @php $cheapest = $hall->pricingRules->where('rate_type','hourly')->sortBy('price')->first() ?? $hall->pricingRules->first(); @endphp
        <div class="text-sm font-medium text-slate-700 mb-5">
            <span class="text-xs text-slate-500 uppercase tracking-wider mb-0.5 block">Starting at</span>
            <span class="text-amber-600 font-bold text-lg">RM {{ number_format($cheapest->price, 2) }}</span>
            <span class="text-slate-500">/ {{ str_replace('_',' ',$cheapest->rate_type) }}</span>
        </div>
        @else
        <div class="text-sm font-medium text-slate-700 mb-5">
            <span class="text-xs text-slate-500 uppercase tracking-wider mb-0.5 block">Pricing</span>
            <span class="text-slate-600 font-bold text-lg">Contact Us</span>
        </div>
        @endif

        <div class="flex gap-3">
            <a href="{{ route('event-halls.show', $hall) }}" class="flex-1">
                <x-button variant="secondary" class="w-full justify-center text-xs">
                    View Details
                </x-button>
            </a>
            <a href="{{ route('quotations.create', ['hall_id' => $hall->id]) }}" class="flex-1">
                <x-button variant="amber" class="w-full justify-center text-xs">
                    Get Quote
                </x-button>
            </a>
        </div>
    </div>
</x-card>
