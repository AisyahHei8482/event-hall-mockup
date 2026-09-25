@csrf
@isset($facility) @method('PUT') @endisset

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Name</label>
        <input type="text" name="name" value="{{ old('name', $facility->name ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Type</label>
        <select name="type" x-data x-on:change="$el.closest('form').querySelector('[data-accommodation-type]').classList.toggle('hidden', $el.value !== 'accommodation'); $el.closest('form').querySelector('[data-experience-type]').classList.toggle('hidden', $el.value !== 'experience')"
                class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            @foreach (['facility' => 'Facility', 'accommodation' => 'Accommodation', 'activity' => 'Activity', 'dining' => 'Dining', 'experience' => 'Experience'] as $value => $label)
                <option value="{{ $value }}" @selected(old('type', $facility->type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div data-accommodation-type class="{{ old('type', $facility->type ?? '') === 'accommodation' ? '' : 'hidden' }}">
        <label class="block text-sm font-medium text-forest-800 mb-1">Accommodation Type</label>
        <select name="accommodation_type" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            <option value="">Select type</option>
            @foreach (\App\Http\Controllers\AccommodationController::TYPES as $value => $label)
                <option value="{{ $value }}" @selected(old('accommodation_type', $facility->accommodation_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div data-experience-type class="{{ old('type', $facility->type ?? '') === 'experience' ? '' : 'hidden' }}">
        <label class="block text-sm font-medium text-forest-800 mb-1">Experience Type</label>
        <select name="experience_type" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            <option value="">Select type</option>
            @foreach (\App\Http\Controllers\ExperienceController::TYPES as $value => $meta)
                <option value="{{ $value }}" @selected(old('experience_type', $facility->experience_type ?? '') === $value)>{{ $meta['label'] }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Short Description</label>
    <input type="text" name="short_description" value="{{ old('short_description', $facility->short_description ?? '') }}"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Description</label>
    <textarea name="description" rows="6"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('description', $facility->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Capacity</label>
        <input type="number" name="capacity" value="{{ old('capacity', $facility->capacity ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Price (RM)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $facility->price ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Price Unit</label>
        <input type="text" name="price_unit" placeholder="per person / per night" value="{{ old('price_unit', $facility->price_unit ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Amenities (comma separated)</label>
    <input type="text" name="amenities" value="{{ old('amenities', isset($facility) ? implode(', ', $facility->amenities ?? []) : '') }}"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Cover Image</label>
    <input type="file" name="cover_image" accept="image/*" class="w-full border border-forest-200 rounded-lg px-4 py-2.5">
    @isset($facility)
        @if ($facility->cover_image)
            <img src="{{ Storage::url($facility->cover_image) }}" class="mt-3 h-32 rounded-lg object-cover">
        @endif
    @endisset
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">360&deg; Virtual Tour URL</label>
    <input type="url" name="virtual_tour_url" placeholder="https://..." value="{{ old('virtual_tour_url', $facility->virtual_tour_url ?? '') }}"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    <p class="text-xs text-forest-400 mt-1">Embeddable link (e.g. Matterport, Kuula, Google Street View embed URL).</p>
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $facility->is_featured ?? false)) class="rounded border-forest-300">
        Featured
    </label>
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $facility->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $facility->sort_order ?? 0) }}"
               class="w-24 border border-forest-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($facility) ? 'Update Facility' : 'Create Facility' }}
    </button>
</div>
