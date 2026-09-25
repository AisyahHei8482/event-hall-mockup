@csrf
@isset($post) @method('PUT') @endisset

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
    <label class="block text-sm font-medium text-forest-800 mb-1">Title</label>
    <input type="text" name="title" value="{{ old('title', $post->title ?? '') }}" required
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Excerpt</label>
    <textarea name="excerpt" rows="2"
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('excerpt', $post->excerpt ?? '') }}</textarea>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Body</label>
    <textarea name="body" rows="10" required
              class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('body', $post->body ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-6">
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Category</label>
        <select name="category" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            @foreach ([
                'resort-news' => 'Resort News', 'guest-stories' => 'Guest Stories', 'travel-tips' => 'Travel Tips',
                'event-highlights' => 'Event Highlights', 'seasonal-promotions' => 'Seasonal Promotions', 'behind-the-scenes' => 'Behind the Scenes',
            ] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $post->category ?? '') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Author</label>
        <input type="text" name="author_name" value="{{ old('author_name', $post->author_name ?? 'Savanna Hill Resort') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
    <div>
        <label class="block text-sm font-medium text-forest-800 mb-1">Publish Date</label>
        <input type="date" name="published_at" value="{{ old('published_at', isset($post) && $post->published_at ? $post->published_at->format('Y-m-d') : '') }}"
               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
    </div>
</div>

<div class="mt-6">
    <label class="block text-sm font-medium text-forest-800 mb-1">Featured Image</label>
    @isset($post)
        @if ($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" class="h-24 rounded-lg mb-2 object-cover">
        @endif
    @endisset
    <input type="file" name="featured_image" accept="image/*"
           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
</div>

<div class="flex items-center gap-6 mt-6">
    <label class="flex items-center gap-2 text-sm text-forest-700">
        <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post->is_published ?? false)) class="rounded border-forest-300">
        Published
    </label>
</div>

<div class="mt-8">
    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
        {{ isset($post) ? 'Update Post' : 'Create Post' }}
    </button>
</div>
