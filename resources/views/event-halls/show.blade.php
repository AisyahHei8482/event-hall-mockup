@extends('layouts.eventhall-demo')

@section('title', $hall->name . ' — ' . ($hall->franchise->name ?? 'Savanna Hill Resort'))
@section('description', $hall->description ?: 'Book ' . $hall->name . ' for your event at Savanna Hill Resort.')

@section('content')
<div class="bg-slate-50 min-h-screen">
    <!-- Hero with cover image -->
    <section class="relative h-[450px] overflow-hidden">
        @if($hall->cover_image)
        <img src="{{ asset('storage/'.$hall->cover_image) }}" alt="{{ $hall->name }}" class="w-full h-full object-cover">
        @else
        <div class="w-full h-full bg-gradient-to-br from-slate-900 to-slate-800"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
        
        <div class="absolute bottom-0 left-0 right-0 p-8 pb-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-3 text-brand-400 text-sm font-semibold tracking-wide uppercase mb-4 animate-fade-in-up">
                    <a href="{{ route('event-halls.index') }}" class="hover:text-white transition-colors">Event Halls</a>
                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    <span class="text-white/70">{{ $hall->franchise->name ?? '' }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white tracking-tight drop-shadow-md animate-fade-in-up" style="animation-delay: 100ms;">{{ $hall->name }}</h1>
                <div class="flex flex-wrap gap-5 text-white/90 text-base mt-6 animate-fade-in-up" style="animation-delay: 200ms;">
                    <span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fa-solid fa-people-group text-amber-400"></i> Up to {{ $hall->capacity }} pax</span>
                    @if($hall->floor_area)<span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full"><i class="fa-solid fa-ruler-combined text-amber-400"></i> {{ $hall->floor_area }} m²</span>@endif
                    @if($hall->hall_type)<span class="flex items-center gap-2 bg-white/10 backdrop-blur-sm px-3 py-1.5 rounded-full capitalize"><i class="fa-solid fa-tag text-amber-400"></i> {{ $hall->hall_type }}</span>@endif
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                @if($hall->description)
                <x-card class="p-8 border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 tracking-tight">About This Venue</h2>
                    <p class="text-slate-600 text-lg leading-relaxed">{{ $hall->description }}</p>
                </x-card>
                @endif

                <!-- Gallery -->
                @if($hall->images->isNotEmpty())
                <x-card class="p-8 border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight">Gallery</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach($hall->images->take(6) as $img)
                        <div class="relative rounded-xl overflow-hidden aspect-[4/3] group cursor-pointer shadow-sm">
                            <img src="{{ asset('storage/'.$img->path) }}" alt="{{ $img->caption }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                        </div>
                        @endforeach
                    </div>
                </x-card>
                @endif

                <!-- Amenities / Facilities -->
                @if(count($hall->amenities ?? []) > 0 || count($hall->facilities ?? []) > 0)
                <x-card class="p-8 border-slate-100 shadow-sm">
                    <h2 class="text-2xl font-bold text-slate-900 mb-6 tracking-tight">What's Included</h2>
                    @if(count($hall->facilities ?? []) > 0)
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Facilities</h3>
                    <div class="flex flex-wrap gap-3 mb-8">
                        @foreach($hall->facilities as $f)
                        <x-badge variant="amber" class="px-4 py-2 text-sm shadow-sm">
                            <i class="fa-solid fa-check mr-1.5 opacity-70"></i>{{ $f }}
                        </x-badge>
                        @endforeach
                    </div>
                    @endif
                    @if(count($hall->amenities ?? []) > 0)
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-4">Amenities</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($hall->amenities as $a)
                        <span class="bg-slate-100 border border-slate-200 text-slate-700 text-sm font-medium px-4 py-1.5 rounded-full shadow-sm">{{ $a }}</span>
                        @endforeach
                    </div>
                    @endif
                </x-card>
                @endif

                <!-- Pricing -->
                @if($hall->pricingRules->isNotEmpty())
                <div class="mb-10">
                    <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-tags text-brand-500"></i> Pricing Options
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($hall->pricingRules as $rule)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-brand-200 transition-all group">
                            <div>
                                <div class="font-bold text-slate-800">{{ $rule->label }}</div>
                                <div class="text-xs font-semibold text-slate-400 mt-0.5">{{ ucfirst(str_replace('_',' ',$rule->day_type)) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-brand-600">RM {{ number_format($rule->price, 0) }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">/ {{ str_replace('_',' ',$rule->rate_type) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Add-ons -->
                @if($hall->addons->isNotEmpty())
                <div>
                    <h2 class="text-xl font-black text-slate-900 mb-6 flex items-center gap-3">
                        <i class="fa-solid fa-puzzle-piece text-brand-500"></i> Optional Enhancements
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($hall->addons as $addon)
                        <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-white border border-transparent hover:border-slate-200 transition-colors">
                            <div class="pr-4">
                                <div class="font-bold text-slate-800 text-sm">{{ $addon->name }}</div>
                                @if($addon->category)
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $addon->category }}</div>
                                @endif
                            </div>
                            <div class="text-right whitespace-nowrap">
                                <div class="font-black text-slate-900 text-sm">RM {{ number_format($addon->price, 0) }}</div>
                                <div class="text-[10px] font-bold text-brand-500 uppercase tracking-wider">{{ $addon->unit ?: 'per item' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar — Quote CTA & Info -->
            <div class="space-y-6 lg:sticky lg:top-24 h-fit">
                
                <!-- CTA Card -->
                <div class="bg-gradient-to-br from-amber-600 to-amber-800 rounded-3xl p-8 text-white shadow-xl shadow-amber-900/20 relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-amber-500/30">
                        <i class="fa-solid fa-file-invoice text-9xl"></i>
                    </div>
                    <div class="relative z-10">
                        <h3 class="text-2xl font-bold mb-2 tracking-tight">Book This Venue</h3>
                        <p class="text-amber-100 text-base mb-8">Get an instant price quote — no commitment required.</p>

                        @if($hall->pricingRules->isNotEmpty())
                        @php $cheapest = $hall->pricingRules->where('rate_type','hourly')->sortBy('price')->first() ?? $hall->pricingRules->first(); @endphp
                        <div class="bg-black/20 backdrop-blur-md rounded-2xl p-5 mb-8 border border-white/10">
                            <div class="text-sm font-medium text-amber-200 uppercase tracking-wider mb-1">Starting from</div>
                            <div class="text-4xl font-bold text-white tracking-tight">RM {{ number_format($cheapest->price,0) }}<span class="text-lg font-medium text-amber-200/80">/{{ str_replace('_',' ',$cheapest->rate_type) }}</span></div>
                        </div>
                        @endif

                        <a href="{{ route('quotations.create', ['hall_id' => $hall->id]) }}" class="block w-full">
                            <button type="button" class="w-full flex items-center justify-center bg-white text-amber-800 hover:bg-amber-50 font-bold text-lg rounded-xl shadow-lg border-0 py-4 transition-colors">
                                <i class="fa-solid fa-calculator mr-2"></i> Get Instant Quote
                            </button>
                        </a>
                        <div class="mt-5 flex items-center justify-center gap-2 text-sm font-medium text-amber-100 bg-black/10 py-2 rounded-full">
                            <i class="fa-solid fa-shield-check"></i> Free quote · No deposit required
                        </div>
                    </div>
                </div>

                <!-- Hall Info -->
                <x-card class="p-6 border-slate-100 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 tracking-tight">Venue Details</h3>
                    <dl class="space-y-4 text-sm">
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium flex items-center gap-2"><i class="fa-solid fa-hourglass-start w-4"></i> Min Booking</dt>
                            <dd class="font-bold text-slate-800 bg-slate-50 px-3 py-1 rounded-lg">{{ $hall->min_booking_hours }} hour(s)</dd>
                        </div>
                        @if($hall->max_booking_hours)
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium flex items-center gap-2"><i class="fa-solid fa-hourglass-end w-4"></i> Max Booking</dt>
                            <dd class="font-bold text-slate-800 bg-slate-50 px-3 py-1 rounded-lg">{{ $hall->max_booking_hours }} hour(s)</dd>
                        </div>
                        @endif
                        @if($hall->franchise->phone ?? false)
                        <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                            <dt class="text-slate-500 font-medium flex items-center gap-2"><i class="fa-solid fa-phone w-4"></i> Contact</dt>
                            <dd class="font-bold text-slate-800">{{ $hall->franchise->phone }}</dd>
                        </div>
                        @endif
                        @if($hall->franchise->address ?? false)
                        <div>
                            <dt class="text-slate-500 font-medium flex items-center gap-2 mb-2"><i class="fa-solid fa-map-location-dot w-4"></i> Address</dt>
                            <dd class="text-slate-700 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-100">{{ $hall->franchise->address }}</dd>
                        </div>
                        @endif
                    </dl>
                </x-card>

                <!-- Check Availability -->
                <x-card class="p-6 border-slate-100 shadow-sm" x-data="availabilityChecker({{ $hall->id }})">
                    <h3 class="text-lg font-bold text-slate-900 mb-4 tracking-tight">Check Availability</h3>
                    <x-input type="date" x-model="date" min="{{ now()->toDateString() }}" class="mb-4 bg-slate-50" />
                    <button @click="check()" x-bind:disabled="!date || loading" class="w-full flex items-center justify-center px-5 py-3 bg-brand-600 hover:bg-brand-700 text-white font-bold rounded-xl transition-colors disabled:opacity-50 disabled:cursor-not-allowed shadow-md shadow-brand-500/20 text-base">
                        <i class="fa-solid fa-calendar-check mr-2"></i> <span x-text="loading ? 'Checking...' : 'Check Date'">Check Date</span>
                    </button>

                    <div x-show="result !== null" x-transition x-cloak class="mt-5">
                        <x-alert x-show="!result?.available" variant="danger" class="py-2.5">
                            Not available on this date.
                        </x-alert>
                        <div x-show="result?.available" class="space-y-3">
                            <div class="text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Available Slots</div>
                            <template x-for="slot in result?.slots" :key="slot.id">
                                <div class="flex items-center justify-between bg-emerald-50 border border-emerald-100 rounded-xl px-4 py-3 shadow-sm">
                                    <span class="font-bold text-emerald-800" x-text="slot.label"></span>
                                    <a :href="`{{ route('quotations.create') }}?hall_id={{ $hall->id }}&date=${date}&start_time=${slot.start_time}&end_time=${slot.end_time}`">
                                        <button type="button" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-4 py-2 rounded-lg transition-colors">
                                            Book
                                        </button>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function availabilityChecker(hallId) {
    return {
        date: '',
        today: new Date().toISOString().split('T')[0],
        loading: false,
        result: null,
        async check() {
            if (!this.date) return;
            this.loading = true;
            this.result = null;
            try {
                const res = await fetch(`/event-halls/${hallId}/availability?date=${this.date}`);
                this.result = await res.json();
            } catch(e) { console.error(e); }
            this.loading = false;
        }
    }
}
</script>
@endpush
