@csrf
@isset($addon) @method('PUT') @endisset

@if ($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label class="block text-sm font-medium text-forest-800 mb-1">Name</label>
    <input type="text" name="name" value="{{ old('name', $addon->name ?? '') }}" required
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Description</label>
    <textarea name="description" rows="3"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('description', $addon->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Facility</label>
        <select name="facility_id" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            <option value="">All Facilities</option>
            @foreach ($facilities as $facility)
                <option value="{{ $facility->id }}" @selected(old('facility_id', $addon->facility_id ?? '') == $facility->id)>{{ $facility->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Price (RM)</label>
        <input type="number" name="price" step="0.01" min="0" value="{{ old('price', $addon->price ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $addon->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($addon) ? 'Update Add-on' : 'Create Add-on' }}
    </button>
</div>
