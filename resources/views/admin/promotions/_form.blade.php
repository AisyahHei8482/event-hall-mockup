@csrf
@isset($promotion) @method('PUT') @endisset

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
        <input type="text" name="title" value="{{ old('title', $promotion->title ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Promo Code</label>
        <input type="text" name="code" value="{{ old('code', $promotion->code ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Description</label>
    <textarea name="description" rows="4"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('description', $promotion->description ?? '') }}</textarea>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Terms & Conditions</label>
    <textarea name="terms" rows="3"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('terms', $promotion->terms ?? '') }}</textarea>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Offer Image</label>
    @isset($promotion)
        @if ($promotion->image)
            <img src="{{ Storage::url($promotion->image) }}" class="h-24 rounded-lg mb-2 object-cover">
        @endif
    @endisset
    <input type="file" name="image" accept="image/*"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Discount Type</label>
        <select name="discount_type" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            @foreach (['percentage' => 'Percentage (%)', 'fixed' => 'Fixed Amount (RM)'] as $value => $label)
                <option value="{{ $value }}" @selected(old('discount_type', $promotion->discount_type ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Discount Value</label>
        <input type="number" step="0.01" name="discount_value" value="{{ old('discount_value', $promotion->discount_value ?? '') }}" required
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Starts At</label>
        <input type="date" name="starts_at" value="{{ old('starts_at', isset($promotion) && $promotion->starts_at ? $promotion->starts_at->format('Y-m-d') : '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Ends At</label>
        <input type="date" name="ends_at" value="{{ old('ends_at', isset($promotion) && $promotion->ends_at ? $promotion->ends_at->format('Y-m-d') : '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($promotion) ? 'Update Promotion' : 'Create Promotion' }}
    </button>
</div>
