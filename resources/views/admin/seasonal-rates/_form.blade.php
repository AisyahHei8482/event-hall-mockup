@csrf
@isset($seasonalRate) @method('PUT') @endisset

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
    <label class="block text-sm font-medium text-forest-800 mb-1">Facility</label>
    <select name="facility_id" required class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
        <option value="">Select a facility</option>
        @foreach ($facilities as $facility)
            <option value="{{ $facility->id }}" @selected(old('facility_id', $seasonalRate->facility_id ?? '') == $facility->id)>{{ $facility->name }}</option>
        @endforeach
    </select>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Label</label>
    <input type="text" name="label" value="{{ old('label', $seasonalRate->label ?? '') }}" placeholder="e.g. School Holidays, Peak Season" required
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Starts On</label>
        <input type="date" name="starts_on" value="{{ old('starts_on', isset($seasonalRate) ? $seasonalRate->starts_on->format('Y-m-d') : '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Ends On</label>
        <input type="date" name="ends_on" value="{{ old('ends_on', isset($seasonalRate) ? $seasonalRate->ends_on->format('Y-m-d') : '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Fixed Price Override (RM)</label>
        <input type="number" name="price_override" step="0.01" min="0" value="{{ old('price_override', $seasonalRate->price_override ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
        <p class="text-xs text-forest-500 mt-1">Leave blank to use a multiplier instead.</p>
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Price Multiplier</label>
        <input type="number" name="price_multiplier" step="0.01" min="0" value="{{ old('price_multiplier', $seasonalRate->price_multiplier ?? '') }}" placeholder="e.g. 1.25 for +25%"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $seasonalRate->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($seasonalRate) ? 'Update Seasonal Rate' : 'Create Seasonal Rate' }}
    </button>
</div>
