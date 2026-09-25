@extends('layouts.eventhall-demo')

@section('title', 'Get an Instant Quote — Savanna Hill Resort Event Halls')
@section('description', 'Get an instant price quote for your event. No commitment required — choose your hall, date, time, and add-ons.')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 bg-brand-100 text-brand-800 rounded-full px-4 py-1.5 text-xs font-bold uppercase tracking-widest mb-4">
                <i class="fa-solid fa-calculator"></i> Instant Quote Calculator
            </span>
            <h1 class="text-3xl md:text-5xl font-bold text-slate-900 mb-4 tracking-tight">Plan Your Event</h1>
            <p class="text-slate-500 text-lg max-w-xl mx-auto leading-relaxed">Select your venue, date, and required services to instantly generate a detailed price breakdown without commitment.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 sm:p-6" x-data="quoteBuilder()" x-init="init()">

            <!-- Form -->
            <div class="lg:col-span-2">
                <form id="quoteForm" method="POST" action="{{ route('quotations.store') }}" @submit="submitting = true">
                    @csrf
                    @if($existingQuotation)
                        <input type="hidden" name="quotation_id" value="{{ $existingQuotation->quote_number }}">
                    @endif
                    <div class="space-y-6">

                        <!-- Step 1: Hall + Date + Time -->
                        <x-card class="p-5 sm:p-6 border-slate-100 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3 tracking-tight">
                                <span class="w-8 h-8 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                                Select Venue & Date
                            </h2>

                            <!-- Franchise / Hall Selection -->
                            @if(!$selectedHall)
                            <div class="mb-6">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Event Hall</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <template x-for="h in availableHalls" :key="h.id">
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" name="event_hall_id" :value="h.id"
                                                   x-model="selectedHallId" @change="fetchHallAddons(h.id)"
                                                   class="peer sr-only">
                                            <div :class="selectedHallId == h.id ? 'border-brand-500 bg-brand-50 ring-1 ring-brand-500 shadow-md' : 'border-slate-200 bg-white group-hover:border-brand-300 group-hover:bg-brand-50/30'"
                                                 class="border-2 rounded-2xl p-5 transition-all h-full">
                                                <div class="font-bold text-slate-900 text-lg" x-text="h.name"></div>
                                                <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mt-1" x-text="`Capacity: ${h.capacity} pax`"></div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </div>
                            @else
                            <input type="hidden" name="event_hall_id" value="{{ $selectedHall->id }}">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-5 bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4 shadow-sm">
                                @if($selectedHall->cover_image)
                                <img src="{{ asset('storage/'.$selectedHall->cover_image) }}" class="w-full sm:w-16 sm:h-16 rounded-xl object-cover shadow-sm">
                                @endif
                                <div class="flex-1">
                                    <div class="font-bold text-slate-900 text-xl">{{ $selectedHall->name }}</div>
                                    <div class="text-sm font-semibold text-slate-500 mt-1 uppercase tracking-widest">Premium Event Space · {{ $selectedHall->capacity }} pax</div>
                                </div>
                                <a href="{{ route('event-halls.demo.home') }}">
                                    <button type="button" class="w-full sm:w-auto mt-4 sm:mt-0 bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-xs px-4 py-2 rounded-xl transition-colors">
                                        Change Venue
                                    </button>
                                </a>
                            </div>
                            @endif

                            <!-- Date + Time + Guests -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Date <span class="text-red-500">*</span></label>
                                    <x-input type="date" name="event_date" x-model="eventDate" x-bind:min="minDate" @change="recalculate()" class="bg-slate-50" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Start Time <span class="text-red-500">*</span></label>
                                    <x-input type="time" name="start_time" x-model="startTime" @change="recalculate()" class="bg-slate-50" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">End Time <span class="text-red-500">*</span></label>
                                    <x-input type="time" name="end_time" x-model="endTime" @change="recalculate()" class="bg-slate-50" required />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Guests <span class="text-red-500">*</span></label>
                                    <x-input type="number" name="guests" x-model="guests" @change="recalculate()" min="1" class="bg-slate-50" required />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Event Type</label>
                                    <x-select name="event_type" class="bg-slate-50">
                                        <option value="">Select event type...</option>
                                        @foreach(['Wedding','Corporate Event','Birthday Party','Anniversary','Conference','Exhibition','Product Launch','Networking Event','Baby Shower','Other'] as $et)
                                        <option>{{ $et }}</option>
                                        @endforeach
                                    </x-select>
                                </div>
                            </div>
                        </x-card>

                        <!-- Step 2: Add-ons -->
                        <div x-show="addons.length > 0" x-cloak x-transition>
                            <x-card class="p-5 sm:p-6 border-slate-100 shadow-sm">
                                <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3 tracking-tight">
                                    <span class="w-8 h-8 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                                    Optional Add-ons
                                </h2>
                                <div class="space-y-0 border-t border-slate-100">
                                    <template x-for="addon in addons" :key="addon.id">
                                        <label class="flex items-center justify-between py-3 px-2 border-b border-slate-100 hover:bg-slate-50 transition-colors cursor-pointer group">
                                            <div class="flex items-center gap-3">
                                                <input type="checkbox" :name="`addons[]`" :value="addon.id"
                                                       x-model="selectedAddons[addon.id]" @change="recalculate()"
                                                       class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500 shadow-sm cursor-pointer mt-0.5">
                                                <div>
                                                    <div class="font-bold text-slate-900 text-sm leading-none" x-text="addon.name"></div>
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center gap-6">
                                                <!-- Quantity Input (Shows only when selected & quantifiable) -->
                                                <div x-show="selectedAddons[addon.id] && addon.is_quantifiable" x-cloak class="flex items-center gap-2">
                                                    <span class="text-xs text-slate-400 font-medium">Qty:</span>
                                                    <input type="number" :name="`addon_quantities[${addon.id}]`"
                                                           x-model="addonQty[addon.id]" @change="recalculate()"
                                                           min="1" :max="addon.max_quantity || 999"
                                                           class="w-14 text-center text-sm border border-slate-200 rounded py-0.5 px-1 focus:ring-brand-500 focus:border-brand-500" />
                                                </div>
                                                <!-- Price & Unit -->
                                                <div class="text-right w-24 shrink-0">
                                                    <div class="font-bold text-brand-600 text-sm" x-text="`RM ${parseFloat(addon.price).toFixed(2)}`"></div>
                                                    <div class="text-[9px] text-slate-400 uppercase tracking-widest mt-0.5" x-text="`${addon.unit || 'per event'}`"></div>
                                                </div>
                                            </div>
                                        </label>
                                    </template>
                                </div>
                            </x-card>
                        </div>

                        <!-- Step 3: Guest Details -->
                        <x-card class="p-5 sm:p-6 border-slate-100 shadow-sm">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-3 tracking-tight">
                                <span class="w-8 h-8 bg-brand-100 text-brand-600 rounded-full flex items-center justify-center text-sm font-bold" x-text="addons.length > 0 ? '3' : '2'">3</span>
                                Your Details
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <x-input type="text" name="guest_name" value="{{ old('guest_name', $existingQuotation ? $existingQuotation->guest_name : auth()->user()?->name) }}" required class="bg-slate-50" />
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email <span class="text-red-500">*</span></label>
                                    <x-input type="email" name="guest_email" value="{{ old('guest_email', $existingQuotation ? $existingQuotation->guest_email : auth()->user()?->email) }}" required class="bg-slate-50" />
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Phone</label>
                                    <x-input type="tel" name="guest_phone" value="{{ old('guest_phone', $existingQuotation?->guest_phone) }}" class="bg-slate-50" />
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Special Requirements</label>
                                <textarea name="requirements" rows="3"
                                          class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-brand-500 resize-none bg-slate-50"
                                          placeholder="Any special requirements, setup preferences, dietary needs...">{{ old('requirements', $existingQuotation?->requirements) }}</textarea>
                            </div>
                        </x-card>

                        <button type="submit" class="w-full flex items-center justify-center bg-brand-500 hover:bg-brand-600 text-white font-black text-lg py-4 rounded-2xl shadow-xl shadow-brand-500/20 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" x-bind:disabled="submitting || !canSubmit()">
                            <span x-show="!submitting"><i class="fa-solid fa-file-invoice-dollar mr-2"></i> Generate Quote</span>
                            <span x-show="submitting" style="display: none;"><i class="fa-solid fa-spinner fa-spin mr-2"></i> Generating...</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Live Price Summary -->
            <div class="lg:sticky lg:top-24 h-fit space-y-6">
                <div class="bg-slate-900 rounded-3xl shadow-2xl overflow-hidden text-slate-300">
                    <div class="bg-gradient-to-br from-brand-600 to-brand-800 px-6 py-5 border-b border-brand-900/50">
                        <h3 class="font-bold text-white text-xl flex items-center justify-between">
                            Price Estimate
                            <i class="fa-solid fa-receipt text-brand-300/50"></i>
                        </h3>
                        <p class="text-brand-100/80 text-sm mt-1">Updates as you configure</p>
                    </div>

                    <div class="p-6">
                        <!-- Duration indicator -->
                        <div x-show="duration > 0" x-cloak class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-2xl px-4 py-3 mb-6">
                            <div class="w-10 h-10 rounded-full bg-brand-500/20 flex items-center justify-center text-brand-400">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div>
                                <div class="text-white font-bold"><span x-text="duration"></span> hours</div>
                                <div class="text-xs text-slate-400 uppercase tracking-wide"><span x-text="rateType"></span> rate</div>
                            </div>
                        </div>

                        <!-- Calculating indicator -->
                        <div x-show="calculating" x-cloak class="text-center py-10">
                            <i class="fa-solid fa-spinner fa-spin text-3xl text-brand-500 mb-3 block"></i>
                            <span class="text-sm font-medium">Calculating price...</span>
                        </div>

                        <!-- Breakdown -->
                        <template x-if="!calculating && breakdown !== null">
                            <div class="space-y-3 text-sm">
                                <template x-for="item in breakdown?.items" :key="item.description">
                                    <div class="flex justify-between items-start pb-2 border-b border-slate-800">
                                        <span class="text-slate-400 flex-1 pr-4" x-text="item.description"></span>
                                        <span class="font-bold text-white shrink-0" x-text="`RM ${item.total}`"></span>
                                    </div>
                                </template>

                                <div class="pt-2">
                                    <div x-show="parseFloat(breakdown?.addons_total || 0) > 0" class="flex justify-between text-slate-400 mb-2">
                                        <span>Add-ons</span><span class="text-white" x-text="`RM ${breakdown?.addons_total}`"></span>
                                    </div>
                                    <div x-show="parseFloat(breakdown?.discount_amount || 0) > 0" class="flex justify-between text-emerald-400 mb-2">
                                        <span>Discount</span><span x-text="`−RM ${breakdown?.discount_amount}`"></span>
                                    </div>
                                    <div x-show="parseFloat(breakdown?.tax_amount || 0) > 0" class="flex justify-between text-slate-400 mb-2">
                                        <span x-text="`Tax (${breakdown?.tax_rate}%)`"></span>
                                        <span class="text-white" x-text="`RM ${breakdown?.tax_amount}`"></span>
                                    </div>
                                    <div x-show="parseFloat(breakdown?.service_charge || 0) > 0" class="flex justify-between text-slate-400 mb-2">
                                        <span x-text="`Service Charge (${breakdown?.service_charge_rate}%)`"></span>
                                        <span class="text-white" x-text="`RM ${breakdown?.service_charge}`"></span>
                                    </div>
                                </div>

                                <div class="bg-white/5 rounded-2xl p-4 mt-6 border border-white/10">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="text-slate-400 font-medium">Total</span>
                                        <span class="text-3xl font-bold text-brand-400 tracking-tight" x-text="`RM ${breakdown?.total_amount}`"></span>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm px-2 pt-2">
                                    <span class="text-slate-400">Deposit required</span>
                                    <span class="text-white font-bold" x-text="`RM ${breakdown?.deposit_required}`"></span>
                                </div>
                            </div>
                        </template>

                        <!-- Empty state -->
                        <div x-show="!calculating && breakdown === null" class="py-10 text-center">
                            <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-600">
                                <i class="fa-solid fa-calculator text-2xl"></i>
                            </div>
                            <p class="text-sm font-medium">Fill in the date, time, and guest count to see your price estimate.</p>
                        </div>
                    </div>
                </div>

                <!-- Trust -->
                <x-card class="p-6 border-slate-100 shadow-sm bg-brand-50/50">
                    <div class="space-y-4 text-sm font-medium text-slate-700">
                        <div class="flex items-center gap-3"><i class="fa-solid fa-shield-check text-emerald-600 text-lg w-5"></i> Free quote — no obligation</div>
                        <div class="flex items-center gap-3"><i class="fa-solid fa-clock-rotate-left text-brand-600 text-lg w-5"></i> Quote valid for 7 days</div>
                        <div class="flex items-center gap-3"><i class="fa-solid fa-headset text-blue-600 text-lg w-5"></i> Support within 24hrs</div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function quoteBuilder() {
    return {
        franchises: @json($franchises),
        selectedFranchiseId: '',
        selectedHallId: @json($selectedHall?->id ?? ''),
        hallAddons: @json($selectedHall ? $selectedHall->addons : []),
        addons: @json($selectedHall ? $selectedHall->addons : []),
        selectedAddons: {!! $existingQuotation ? json_encode($existingQuotation->items->where('item_type', 'addon')->mapWithKeys(function($i) { return [$i->addon_id => true]; })) : '{}' !!},
        addonQty: {!! $existingQuotation ? json_encode($existingQuotation->items->where('item_type', 'addon')->mapWithKeys(function($i) { return [$i->addon_id => (int)$i->quantity]; })) : '{}' !!},
        eventDate: '{{ $existingQuotation ? $existingQuotation->event_date?->format('Y-m-d') : request('date', '') }}',
        startTime: '{{ $existingQuotation ? \Carbon\Carbon::parse($existingQuotation->start_time)->format('H:i') : request('start_time', '') }}',
        endTime: '{{ $existingQuotation ? \Carbon\Carbon::parse($existingQuotation->end_time)->format('H:i') : request('end_time', '') }}',
        guests: {{ $existingQuotation ? $existingQuotation->guests : 50 }},
        breakdown: null,
        calculating: false,
        submitting: false,
        duration: 0,
        rateType: '',
        minDate: new Date().toISOString().split('T')[0],
        calculateTimer: null,

        init() {
            if (this.selectedHallId) {
                this.addons = this.hallAddons;
            }
            if (this.eventDate && this.startTime && this.endTime) {
                this.recalculate();
            }
        },

        get availableHalls() {
            return this.franchises.flatMap(f => f.active_halls || []);
        },

        async fetchHallAddons(hallId) {
            this.addons = [];
            this.selectedAddons = {};
            this.addonQty = {};
            
            const hall = this.availableHalls.find(h => h.id == hallId);
            if (hall && hall.addons) {
                this.addons = hall.addons;
            }
            this.recalculate();
        },

        recalculate() {
            clearTimeout(this.calculateTimer);
            this.calculateTimer = setTimeout(() => this._doCalculate(), 600);
        },

        canSubmit() {
            const hallId = this.selectedHallId || {{ $selectedHall?->id ?? 'null' }};
            return hallId && this.eventDate && this.startTime && this.endTime && this.guests > 0;
        },

        async _doCalculate() {
            const hallId = this.selectedHallId || {{ $selectedHall?->id ?? 'null' }};
            if (!hallId || !this.eventDate || !this.startTime || !this.endTime || !this.guests) return;

            // Calculate duration for display
            const start = new Date(`2000-01-01T${this.startTime}`);
            const end = new Date(`2000-01-01T${this.endTime}`);
            this.duration = ((end - start) / 3600000).toFixed(1);

            if (this.duration <= 0) return;

            this.calculating = true;

            const addons = Object.entries(this.selectedAddons)
                .filter(([_, v]) => v)
                .map(([id]) => parseInt(id));

            const addonQtys = {};
            addons.forEach(id => { addonQtys[id] = this.addonQty[id] || 1; });

            try {
                const res = await fetch('{{ route('quotations.calculate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        event_hall_id: hallId,
                        event_date: this.eventDate,
                        start_time: this.startTime,
                        end_time: this.endTime,
                        guests: this.guests,
                        addons,
                        addon_quantities: addonQtys,
                    }),
                });
                const data = await res.json();
                if (data.success) {
                    this.breakdown = data;
                    this.rateType = data.rate_type;
                } else {
                    console.error("Calculation failed:", data);
                }
            } catch(e) { console.error("AJAX Error:", e); }
            this.calculating = false;
        }
    }
}
</script>
@endpush
