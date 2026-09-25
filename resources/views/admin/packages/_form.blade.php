@csrf
@isset($package) @method('PUT') @endisset

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
        <label class="block text-sm font-medium text-forest-800 mb-1">Title</label>
        <input type="text" name="title" value="{{ old('title', $package->title ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Badge</label>
        <input type="text" name="badge" placeholder="e.g. Best Value" value="{{ old('badge', $package->badge ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Short Description</label>
    <input type="text" name="short_description" value="{{ old('short_description', $package->short_description ?? '') }}"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Description</label>
    <textarea name="description" rows="6"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('description', $package->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Price (RM)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $package->price ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Original Price (RM)</label>
        <input type="number" step="0.01" name="original_price" value="{{ old('original_price', $package->original_price ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Cover Image</label>
    <input type="file" name="cover_image" accept="image/*" class="w-full border border-forest-200 rounded-lg px-4 py-2.5">
    @isset($package)
        @if ($package->cover_image)
            <img src="{{ Storage::url($package->cover_image) }}" class="mt-3 h-32 rounded-lg object-cover">
        @endif
    @endisset
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-2">Included Facilities</label>
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 border border-forest-200 rounded-lg p-4 max-h-64 overflow-y-auto">
        @php $selected = old('facility_ids', isset($package) ? $package->facilities->pluck('id')->toArray() : []); @endphp
        @foreach ($facilities as $facility)
            <label class="flex items-center gap-2 text-sm text-forest-700">
                <input type="checkbox" name="facility_ids[]" value="{{ $facility->id }}" @checked(in_array($facility->id, $selected)) class="rounded border-forest-300">
                {{ $facility->name }}
            </label>
        @endforeach
    </div>
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($package) ? 'Update Package' : 'Create Package' }}
    </button>
</div>
