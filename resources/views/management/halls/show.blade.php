@extends('layouts.management')
@section('title', $hall->name)
@section('page_title', $hall->name)
@section('page_subtitle', $hall->franchise->name . ' · ' . $hall->code)

@section('content')
<div x-data="{ tab: 'overview', editModal: null, editData: {} }">

{{-- Flash --}}
@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-bold flex items-center gap-3 shadow-sm">
    <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
</div>
@endif
@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-bold flex items-start gap-3 shadow-sm">
    <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
    <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

{{-- Tabs --}}
<x-card class="p-2 mb-6 bg-white shadow-sm overflow-x-auto">
    <div class="flex gap-2 min-w-max">
        @foreach([
            ['overview', 'fa-chart-line', 'Overview'],
            ['pricing', 'fa-tag', 'Pricing Rules'],
            ['slots', 'fa-clock', 'Time Slots'],
            ['blockouts', 'fa-ban', 'Blockouts'],
            ['addons', 'fa-plus-circle', 'Add-ons'],
            ['bookings', 'fa-calendar-check', 'Bookings'],
        ] as [$key, $icon, $label])
        <button @click="tab = '{{ $key }}'"
                :class="tab === '{{ $key }}' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/20 font-bold' : 'text-slate-500 font-bold hover:bg-slate-50 hover:text-slate-900'"
                class="flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm transition-all uppercase tracking-widest">
            <i class="fa-solid {{ $icon }}"></i> {{ $label }}
        </button>
        @endforeach
    </div>
</x-card>

{{-- ═══════════ OVERVIEW TAB ═══════════ --}}
<div x-show="tab === 'overview'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <x-card class="p-6 border-slate-100 shadow-sm">
        @if($hall->cover_image)
        <div class="relative rounded-2xl overflow-hidden mb-6 aspect-video bg-slate-100 border border-slate-200 shadow-inner group">
            <img src="{{ asset('media/'.$hall->cover_image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        </div>
        @endif
        <dl class="space-y-4 text-sm">
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Status</dt>
                <dd><x-badge variant="{{ $hall->is_active ? 'emerald' : 'slate' }}" class="uppercase tracking-widest shadow-sm">{{ $hall->is_active ? 'Active' : 'Inactive' }}</x-badge></dd>
            </div>
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Capacity</dt><dd class="font-black text-slate-900">{{ $hall->capacity }} pax</dd></div>
            @if($hall->floor_area)<div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Floor Area</dt><dd class="font-black text-slate-900">{{ $hall->floor_area }} m²</dd></div>@endif
            @if($hall->hall_type)<div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Hall Type</dt><dd class="font-black text-slate-900">{{ $hall->hall_type }}</dd></div>@endif
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Min Booking</dt><dd class="font-black text-slate-900">{{ $hall->min_booking_hours }} hr(s)</dd></div>
            @if($hall->max_booking_hours)<div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Max Booking</dt><dd class="font-black text-slate-900">{{ $hall->max_booking_hours }} hr(s)</dd></div>@endif
            <div class="flex justify-between items-center"><dt class="font-bold text-slate-500 uppercase tracking-widest text-xs">Buffer</dt><dd class="font-black text-slate-900">{{ $hall->buffer_before }}m / {{ $hall->buffer_after }}m</dd></div>
        </dl>
        <div class="mt-6 pt-6 border-t border-slate-100">
            <a href="{{ route('management.halls.edit', $hall) }}"
               class="flex items-center justify-center gap-2 w-full text-sm font-bold bg-slate-50 hover:bg-slate-100 text-slate-700 px-4 py-3 rounded-xl transition-colors uppercase tracking-widest">
                <i class="fa-solid fa-pen"></i> Edit Hall Details
            </a>
        </div>
    </x-card>

    <div class="lg:col-span-2 space-y-6">
        <x-card class="p-6 border-slate-100 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-3">Description</h3>
            <p class="text-sm font-medium text-slate-600 leading-relaxed">{{ $hall->description ?: 'No description set.' }}</p>
        </x-card>

        {{-- Amenities & Facilities CRUD --}}
        <x-card class="p-6 border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Amenities & Facilities</h3>
                <button @click="editModal = 'amenities'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold bg-brand-50 text-brand-700 hover:bg-brand-100 rounded-lg transition-colors uppercase tracking-widest">
                    <i class="fa-solid fa-pen"></i> Edit
                </button>
            </div>
            <div class="space-y-5">
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Amenities</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($hall->amenities ?? [] as $item)
                        <span class="text-xs font-bold uppercase tracking-widest bg-slate-50 text-slate-600 border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $item }}</span>
                        @empty
                        <span class="text-xs text-slate-400 italic">None set</span>
                        @endforelse
                    </div>
                </div>
                <div>
                    <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Facilities</div>
                    <div class="flex flex-wrap gap-2">
                        @forelse($hall->facilities ?? [] as $item)
                        <span class="text-xs font-bold uppercase tracking-widest bg-brand-50 text-brand-700 border border-brand-100 px-3 py-1.5 rounded-lg shadow-sm">{{ $item }}</span>
                        @empty
                        <span class="text-xs text-slate-400 italic">None set</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

{{-- ═══════════ PRICING RULES TAB ═══════════ --}}
<div x-show="tab === 'pricing'" class="space-y-6">
    <x-card class="p-6 border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Add Pricing Rule</h3>
        <form method="POST" action="{{ route('management.halls.pricing-rules.store', $hall) }}" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @csrf
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Label</label><x-input type="text" name="label" placeholder="e.g. Weekday Hourly" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Rate Type</label>
                <x-select name="rate_type"><option value="hourly">Hourly</option><option value="half_day">Half Day</option><option value="full_day">Full Day</option><option value="custom">Custom</option></x-select></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Day Type</label>
                <x-select name="day_type"><option value="all">All Days</option><option value="weekday">Weekday</option><option value="weekend">Weekend</option><option value="public_holiday">Public Holiday</option></x-select></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Price (RM)</label><x-input type="number" name="price" step="0.01" min="0" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Half Day Hours</label><x-input type="number" name="half_day_hours" step="0.5" min="0.5" placeholder="e.g. 4" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Day Hours</label><x-input type="number" name="full_day_hours" step="0.5" min="0.5" placeholder="e.g. 8" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Season Start</label><x-input type="date" name="season_start" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Season End</label><x-input type="date" name="season_end" /></div>
            <div class="col-span-2 md:col-span-4 flex justify-end mt-2">
                <x-button type="submit" variant="primary" class="shadow-lg shadow-brand-500/20 px-6"><i class="fa-solid fa-plus mr-1"></i> Add Rule</x-button>
            </div>
        </form>
    </x-card>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Label</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Type</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Day</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Price</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Season</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hall->pricingRules as $rule)
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $rule->label }}</td>
                        <td class="px-6 py-4"><span class="text-xs font-bold uppercase tracking-widest bg-blue-50 text-blue-700 px-2 py-1 rounded-md">{{ ucfirst(str_replace('_',' ',$rule->rate_type)) }}</span></td>
                        <td class="px-6 py-4 font-medium text-slate-600">{{ ucfirst(str_replace('_',' ',$rule->day_type)) }}</td>
                        <td class="px-6 py-4 font-black text-brand-600">RM {{ number_format($rule->price,2) }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">{{ $rule->season_start ? $rule->season_start->format('d M').' – '.$rule->season_end->format('d M Y') : 'Year-round' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editModal = 'pricing-{{ $rule->id }}'"
                                        class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-800 transition-colors">Edit</button>
                                <form method="POST" action="{{ route('management.halls.pricing-rules.destroy', [$hall, $rule]) }}" onsubmit="return confirm('Remove this rule?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    {{-- Inline edit row --}}
                    <tr x-show="editModal === 'pricing-{{ $rule->id }}'" class="bg-amber-50/60 border-b border-amber-100">
                        <td colspan="6" class="px-6 py-4">
                            <form method="POST" action="{{ route('management.halls.pricing-rules.update', [$hall, $rule]) }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @csrf @method('PATCH')
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Label</label><x-input type="text" name="label" value="{{ $rule->label }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Rate Type</label>
                                    <x-select name="rate_type">
                                        <option value="hourly" @selected($rule->rate_type==='hourly')>Hourly</option>
                                        <option value="half_day" @selected($rule->rate_type==='half_day')>Half Day</option>
                                        <option value="full_day" @selected($rule->rate_type==='full_day')>Full Day</option>
                                        <option value="custom" @selected($rule->rate_type==='custom')>Custom</option>
                                    </x-select></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Day Type</label>
                                    <x-select name="day_type">
                                        <option value="all" @selected($rule->day_type==='all')>All Days</option>
                                        <option value="weekday" @selected($rule->day_type==='weekday')>Weekday</option>
                                        <option value="weekend" @selected($rule->day_type==='weekend')>Weekend</option>
                                        <option value="public_holiday" @selected($rule->day_type==='public_holiday')>Public Holiday</option>
                                    </x-select></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Price (RM)</label><x-input type="number" name="price" value="{{ $rule->price }}" step="0.01" min="0" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Half Day Hrs</label><x-input type="number" name="half_day_hours" value="{{ $rule->half_day_hours }}" step="0.5" min="0.5" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Full Day Hrs</label><x-input type="number" name="full_day_hours" value="{{ $rule->full_day_hours }}" step="0.5" min="0.5" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Season Start</label><x-input type="date" name="season_start" value="{{ $rule->season_start?->format('Y-m-d') }}" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Season End</label><x-input type="date" name="season_end" value="{{ $rule->season_end?->format('Y-m-d') }}" /></div>
                                <div class="md:col-span-4 flex gap-3 justify-end mt-1">
                                    <button type="button" @click="editModal = null" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900 transition-colors">Cancel</button>
                                    <x-button type="submit" variant="primary" class="px-6">Save Changes</x-button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-500">No pricing rules yet. Add one above.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- ═══════════ TIME SLOTS TAB ═══════════ --}}
<div x-show="tab === 'slots'" class="space-y-6">
    <x-card class="p-6 border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Add Time Slot</h3>
        <form method="POST" action="{{ route('management.halls.time-slots.store', $hall) }}" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @csrf
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Label</label><x-input type="text" name="label" placeholder="e.g. Morning Session" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Start Time</label><x-input type="time" name="start_time" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">End Time</label><x-input type="time" name="end_time" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Specific Date (optional)</label><x-input type="date" name="specific_date" /></div>
            <div class="col-span-2 md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Days of Week (leave empty for all)</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>7] as $day=>$num)
                    <label class="inline-flex items-center gap-1.5 cursor-pointer bg-slate-50 px-3 py-2 rounded-lg border border-slate-200 hover:bg-slate-100 transition-colors">
                        <input type="checkbox" name="days_of_week[]" value="{{ $num }}" class="rounded text-brand-500 border-slate-300 focus:ring-brand-500 w-4 h-4">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">{{ $day }}</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <div class="col-span-2 md:col-span-1 flex items-end justify-end mt-2">
                <x-button type="submit" variant="primary" class="shadow-lg shadow-brand-500/20 w-full justify-center"><i class="fa-solid fa-plus mr-1"></i> Add Slot</x-button>
            </div>
        </form>
    </x-card>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Slot</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Time</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Days</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Specific Date</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hall->timeSlots as $slot)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $slot->label ?: '—' }}</td>
                        <td class="px-6 py-4 font-bold font-mono text-slate-600">{{ $slot->start_time }} – {{ $slot->end_time }}</td>
                        <td class="px-6 py-4 text-xs font-medium text-slate-500">
                            @if($slot->days_of_week)
                                <div class="flex flex-wrap gap-1">
                                @foreach($slot->days_of_week as $d)
                                    <span class="bg-slate-100 font-bold px-2 py-1 rounded-md">{{ ['','Mon','Tue','Wed','Thu','Fri','Sat','Sun'][$d] }}</span>
                                @endforeach
                                </div>
                            @else <span class="font-bold text-slate-600 uppercase tracking-widest">All days</span> @endif
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-widest">{{ $slot->specific_date?->format('d M Y') ?: 'Recurring' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editModal = 'slot-{{ $slot->id }}'"
                                        class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-800 transition-colors">Edit</button>
                                <form method="POST" action="{{ route('management.halls.time-slots.destroy', [$hall, $slot]) }}" onsubmit="return confirm('Remove this slot?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="editModal === 'slot-{{ $slot->id }}'" class="bg-amber-50/60 border-b border-amber-100">
                        <td colspan="5" class="px-6 py-4">
                            <form method="POST" action="{{ route('management.halls.time-slots.update', [$hall, $slot]) }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @csrf @method('PATCH')
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Label</label><x-input type="text" name="label" value="{{ $slot->label }}" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Start Time</label><x-input type="time" name="start_time" value="{{ $slot->start_time }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">End Time</label><x-input type="time" name="end_time" value="{{ $slot->end_time }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Specific Date</label><x-input type="date" name="specific_date" value="{{ $slot->specific_date?->format('Y-m-d') }}" /></div>
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Days of Week</label>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(['Mon'=>1,'Tue'=>2,'Wed'=>3,'Thu'=>4,'Fri'=>5,'Sat'=>6,'Sun'=>7] as $day=>$num)
                                        <label class="inline-flex items-center gap-1.5 cursor-pointer bg-white px-2 py-1.5 rounded-lg border border-slate-200 hover:bg-slate-50">
                                            <input type="checkbox" name="days_of_week[]" value="{{ $num }}"
                                                   @checked(is_array($slot->days_of_week) && in_array($num, $slot->days_of_week))
                                                   class="rounded text-brand-500 border-slate-300 w-3.5 h-3.5">
                                            <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">{{ $day }}</span>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="md:col-span-4 flex gap-3 justify-end mt-1">
                                    <button type="button" @click="editModal = null" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900">Cancel</button>
                                    <x-button type="submit" variant="primary" class="px-6">Save Changes</x-button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-12 text-center text-sm font-medium text-slate-500">No time slots configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- ═══════════ BLOCKOUTS TAB ═══════════ --}}
<div x-show="tab === 'blockouts'" class="space-y-6">
    <x-card class="p-6 border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Add Blockout Period</h3>
        <form method="POST" action="{{ route('management.halls.blockouts.store', $hall) }}" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @csrf
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Type</label>
                <x-select name="blockout_type"><option value="full_day">Full Day</option><option value="time_range">Time Range</option><option value="maintenance">Maintenance</option></x-select></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Reason</label><x-input type="text" name="reason" placeholder="e.g. Private event" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">From Date</label><x-input type="date" name="date_from" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">To Date</label><x-input type="date" name="date_to" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Time From</label><x-input type="time" name="time_from" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Time To</label><x-input type="time" name="time_to" /></div>
            <div class="col-span-2 md:col-span-4 flex justify-end mt-2">
                <x-button type="submit" variant="primary" class="shadow-lg shadow-brand-500/20 px-6"><i class="fa-solid fa-ban mr-1"></i> Add Blockout</x-button>
            </div>
        </form>
    </x-card>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Type</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Reason</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">From</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">To</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Time</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hall->blockouts as $blockout)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4"><span class="text-xs font-bold uppercase tracking-widest bg-red-50 text-red-700 px-2 py-1 rounded-md">{{ ucfirst(str_replace('_',' ',$blockout->blockout_type)) }}</span></td>
                        <td class="px-6 py-4 font-medium text-slate-700">{{ $blockout->reason ?: '—' }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ \Carbon\Carbon::parse($blockout->date_from)->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ \Carbon\Carbon::parse($blockout->date_to)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-xs font-mono text-slate-600">{{ $blockout->time_from ? $blockout->time_from.' – '.$blockout->time_to : 'All day' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editModal = 'blockout-{{ $blockout->id }}'"
                                        class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-800 transition-colors">Edit</button>
                                <form method="POST" action="{{ route('management.halls.blockouts.destroy', [$hall, $blockout]) }}" onsubmit="return confirm('Remove this blockout?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="editModal === 'blockout-{{ $blockout->id }}'" class="bg-amber-50/60 border-b border-amber-100">
                        <td colspan="6" class="px-6 py-4">
                            <form method="POST" action="{{ route('management.halls.blockouts.update', [$hall, $blockout]) }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @csrf @method('PATCH')
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Type</label>
                                    <x-select name="blockout_type">
                                        <option value="full_day" @selected($blockout->blockout_type==='full_day')>Full Day</option>
                                        <option value="time_range" @selected($blockout->blockout_type==='time_range')>Time Range</option>
                                        <option value="maintenance" @selected($blockout->blockout_type==='maintenance')>Maintenance</option>
                                    </x-select></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Reason</label><x-input type="text" name="reason" value="{{ $blockout->reason }}" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">From Date</label><x-input type="date" name="date_from" value="{{ \Carbon\Carbon::parse($blockout->date_from)->format('Y-m-d') }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">To Date</label><x-input type="date" name="date_to" value="{{ \Carbon\Carbon::parse($blockout->date_to)->format('Y-m-d') }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Time From</label><x-input type="time" name="time_from" value="{{ $blockout->time_from }}" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Time To</label><x-input type="time" name="time_to" value="{{ $blockout->time_to }}" /></div>
                                <div class="md:col-span-4 flex gap-3 justify-end mt-1">
                                    <button type="button" @click="editModal = null" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900">Cancel</button>
                                    <x-button type="submit" variant="primary" class="px-6">Save Changes</x-button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-500">No blockout periods set.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- ═══════════ ADD-ONS TAB ═══════════ --}}
<div x-show="tab === 'addons'" class="space-y-6">
    <x-card class="p-6 border-slate-100 shadow-sm">
        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest mb-4">Add Add-on</h3>
        <form method="POST" action="{{ route('management.halls.addons.store', $hall) }}" class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @csrf
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Name *</label><x-input type="text" name="name" required placeholder="e.g. Sound System" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category</label><x-input type="text" name="category" placeholder="e.g. Equipment" /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Price (RM) *</label><x-input type="number" name="price" step="0.01" min="0" required /></div>
            <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Unit</label><x-input type="text" name="unit" placeholder="e.g. per event" /></div>
            <div class="col-span-2 md:col-span-3"><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Description</label><x-input type="text" name="description" /></div>
            <div class="col-span-2 md:col-span-1 flex items-end justify-end mt-2">
                <x-button type="submit" variant="primary" class="shadow-lg shadow-brand-500/20 w-full justify-center"><i class="fa-solid fa-plus mr-1"></i> Add</x-button>
            </div>
        </form>
    </x-card>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Add-on</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Category</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Price</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Unit</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($hall->addons as $addon)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ $addon->name }}</div>
                            @if($addon->description)<div class="text-xs font-medium text-slate-500 mt-1">{{ $addon->description }}</div>@endif
                        </td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-widest">{{ $addon->category ?: '—' }}</td>
                        <td class="px-6 py-4 font-black text-brand-600">RM {{ number_format($addon->price,2) }}</td>
                        <td class="px-6 py-4 text-xs font-bold text-slate-600 uppercase tracking-widest">{{ $addon->unit ?: '—' }}</td>
                        <td class="px-6 py-4"><x-badge variant="{{ $addon->is_active ? 'emerald' : 'slate' }}" class="uppercase tracking-widest">{{ $addon->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click="editModal = 'addon-{{ $addon->id }}'"
                                        class="text-xs font-bold uppercase tracking-widest text-brand-600 hover:text-brand-800 transition-colors">Edit</button>
                                <form method="POST" action="{{ route('management.halls.addons.destroy', [$hall, $addon]) }}" onsubmit="return confirm('Remove this add-on?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-700 transition-colors">Remove</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr x-show="editModal === 'addon-{{ $addon->id }}'" class="bg-amber-50/60 border-b border-amber-100">
                        <td colspan="6" class="px-6 py-4">
                            <form method="POST" action="{{ route('management.halls.addons.update', [$hall, $addon]) }}" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                @csrf @method('PATCH')
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Name *</label><x-input type="text" name="name" value="{{ $addon->name }}" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Category</label><x-input type="text" name="category" value="{{ $addon->category }}" /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Price (RM) *</label><x-input type="number" name="price" value="{{ $addon->price }}" step="0.01" min="0" required /></div>
                                <div><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Unit</label><x-input type="text" name="unit" value="{{ $addon->unit }}" /></div>
                                <div class="md:col-span-2"><label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Description</label><x-input type="text" name="description" value="{{ $addon->description }}" /></div>
                                <div class="flex items-end gap-3">
                                    <label class="inline-flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" @checked($addon->is_active) class="rounded text-brand-500 border-slate-300 w-4 h-4">
                                        <span class="text-xs font-bold text-slate-700 uppercase tracking-widest">Active</span>
                                    </label>
                                </div>
                                <div class="md:col-span-4 flex gap-3 justify-end mt-1">
                                    <button type="button" @click="editModal = null" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900">Cancel</button>
                                    <x-button type="submit" variant="primary" class="px-6">Save Changes</x-button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-500">No add-ons configured.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- ═══════════ BOOKINGS TAB ═══════════ --}}
<div x-show="tab === 'bookings'">
    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Upcoming Bookings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Booking #</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guest</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Date / Time</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guests</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($upcomingBookings as $booking)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <a href="{{ route('management.bookings.show', $booking) }}"
                               class="font-mono text-xs font-bold text-brand-600 hover:text-brand-700 hover:underline">{{ $booking->booking_number }}</a>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-900">{{ $booking->guest_name }}</td>
                        <td class="px-6 py-4 font-bold text-slate-600">{{ $booking->check_in->format('d M Y') }}<br><span class="text-xs font-mono bg-slate-50 px-2 py-0.5 rounded-md mt-1 inline-block">{{ $booking->start_time }} – {{ $booking->end_time }}</span></td>
                        <td class="px-6 py-4 font-medium text-slate-600">{{ $booking->guests }}</td>
                        <td class="px-6 py-4">
                            <x-badge variant="{{ $booking->status === 'confirmed' ? 'emerald' : 'amber' }}" class="uppercase tracking-widest">{{ ucfirst($booking->status) }}</x-badge>
                        </td>
                        <td class="px-6 py-4 text-right font-black text-slate-900">RM {{ number_format($booking->total_price,2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-12 text-center text-sm font-medium text-slate-500">No upcoming bookings.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-card>
</div>

{{-- ═══════════ AMENITIES EDIT MODAL ═══════════ --}}
<div x-show="editModal === 'amenities'"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm"
     @click.self="editModal = null">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-8 space-y-6" @click.stop>
        <div class="flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-widest">Edit Amenities & Facilities</h3>
            <button @click="editModal = null" class="text-slate-400 hover:text-slate-900 transition-colors">
                <i class="fa-solid fa-xmark text-lg"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('management.halls.amenities.update', $hall) }}" class="space-y-5">
            @csrf @method('PATCH')
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Amenities <span class="text-slate-400 normal-case font-medium">(comma separated)</span></label>
                <x-input type="text" name="amenities"
                         value="{{ is_array($hall->amenities) ? implode(', ', $hall->amenities) : '' }}"
                         placeholder="e.g. Air Conditioning, Parking, WiFi" />
                <p class="text-xs text-slate-400 mt-1.5">These are comfort/service features shown as grey tags.</p>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Facilities <span class="text-slate-400 normal-case font-medium">(comma separated)</span></label>
                <x-input type="text" name="facilities"
                         value="{{ is_array($hall->facilities) ? implode(', ', $hall->facilities) : '' }}"
                         placeholder="e.g. Stage, Projector, AV System" />
                <p class="text-xs text-slate-400 mt-1.5">These are physical hall features shown as orange tags.</p>
            </div>
            <div class="flex gap-3 justify-end pt-2 border-t border-slate-100">
                <button type="button" @click="editModal = null" class="px-4 py-2 text-xs font-bold uppercase tracking-widest text-slate-500 hover:text-slate-900 transition-colors">Cancel</button>
                <x-button type="submit" variant="primary" class="px-6 shadow-lg shadow-brand-500/20">Save Changes</x-button>
            </div>
        </form>
    </div>
</div>

</div>{{-- end x-data --}}
@endsection
