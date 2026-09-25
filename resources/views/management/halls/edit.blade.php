@extends('layouts.management')
@section('title', 'Edit Hall')
@section('page_title', 'Edit ' . $hall->name)

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('management.halls.update', $hall) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        <x-card class="p-6 border-slate-100 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-5">Hall Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Hall Name <span class="text-red-500">*</span></label>
                    <x-input type="text" name="name" value="{{ old('name', $hall->name) }}" required />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Code <span class="text-red-500">*</span></label>
                    <x-input type="text" name="code" value="{{ old('code', $hall->code) }}" required class="font-mono uppercase" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Hall Type</label>
                    <x-input type="text" name="hall_type" value="{{ old('hall_type', $hall->hall_type) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Capacity <span class="text-red-500">*</span></label>
                    <x-input type="number" name="capacity" value="{{ old('capacity', $hall->capacity) }}" required min="1" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Floor Area (m²)</label>
                    <x-input type="number" name="floor_area" value="{{ old('floor_area', $hall->floor_area) }}" step="0.01" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Description</label>
                    <textarea name="description" rows="3"
                              class="w-full px-4 py-3 bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-brand-500 focus:border-brand-500 transition-all font-medium text-sm resize-none">{{ old('description', $hall->description) }}</textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Facilities</label>
                    <x-input type="text" name="facilities" value="{{ old('facilities', is_array($hall->facilities) ? implode(', ', $hall->facilities) : '') }}" placeholder="Comma separated, e.g. Stage, Projector" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Amenities</label>
                    <x-input type="text" name="amenities" value="{{ old('amenities', is_array($hall->amenities) ? implode(', ', $hall->amenities) : '') }}" placeholder="Comma separated, e.g. WiFi, Parking" />
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Cover Image</label>
                    @if($hall->cover_image)
                    <div class="mb-3 relative group rounded-xl overflow-hidden w-32 h-20 border border-slate-200">
                        <img src="{{ asset('storage/'.$hall->cover_image) }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/10 group-hover:bg-slate-900/0 transition-colors"></div>
                    </div>
                    @endif
                    <x-input type="file" name="cover_image" accept="image/*" class="py-2.5" />
                </div>
            </div>
        </x-card>

        <x-card class="p-6 border-slate-100 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-5">Booking Rules</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Min Hours <span class="text-red-500">*</span></label>
                    <x-input type="number" name="min_booking_hours" value="{{ old('min_booking_hours', $hall->min_booking_hours) }}" required min="1" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Max Hours</label>
                    <x-input type="number" name="max_booking_hours" value="{{ old('max_booking_hours', $hall->max_booking_hours) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Buffer Before (min)</label>
                    <x-input type="number" name="buffer_before" value="{{ old('buffer_before', $hall->buffer_before) }}" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Buffer After (min)</label>
                    <x-input type="number" name="buffer_after" value="{{ old('buffer_after', $hall->buffer_after) }}" />
                </div>
            </div>
            <div class="flex items-center gap-3 p-4 bg-slate-50 rounded-xl border border-slate-100 mt-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', $hall->is_active))
                       class="w-5 h-5 rounded border-slate-300 text-brand-600 focus:ring-brand-500 bg-white">
                <label for="is_active" class="text-sm font-bold text-slate-900 cursor-pointer">Hall is active</label>
            </div>
        </x-card>

        <div class="flex items-center gap-4 pt-2">
            <x-button type="submit" variant="primary" class="px-8 shadow-lg shadow-brand-500/20">
                Save Changes
            </x-button>
            <a href="{{ route('management.halls.show', $hall) }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
