@csrf
@isset($image) @method('PUT') @endisset

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
        <input type="text" name="title" value="{{ old('title', $image->title ?? '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Category</label>
        <select name="category" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            @foreach (['leisure' => 'Leisure', 'wedding' => 'Wedding', 'event' => 'Event', 'dining' => 'Dining'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $image->category ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Description</label>
    <input type="text" name="description" value="{{ old('description', $image->description ?? '') }}"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Image</label>
    @isset($image)
        <img src="{{ Storage::url($image->image_path) }}" class="h-24 rounded-lg mb-2 object-cover">
    @endisset
    <input type="file" name="image" accept="image/*"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $image->is_featured ?? false)) class="rounded border-forest-300">
        Featured
    </label>
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $image->is_active ?? true)) class="rounded border-forest-300">
        Active
    </label>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $image->sort_order ?? 0) }}"
               class="w-24 border border-forest-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($image) ? 'Update Image' : 'Upload Image' }}
    </button>
</div>
