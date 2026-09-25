@extends('layouts.app')

@section('title', 'Gallery - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Gallery</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">A glimpse into leisure, weddings, events, and dining moments at Savanna Hill Resort.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14"
              x-data="{ lightbox: false, active: 0, images: {{ $images->map(fn ($i) => ['src' => Storage::url($i->image_path), 'title' => $i->title, 'description' => $i->description])->values()->toJson() }} }">
        <div class="flex flex-wrap gap-3 mb-10">
            <a href="{{ route('gallery.index') }}" class="px-5 py-2 rounded-full text-sm font-semibold transition {{ ! $category ? 'bg-forest-600 text-white' : 'bg-forest-50 text-forest-700 hover:bg-forest-100' }}">All</a>
            @foreach ($categories as $value => $label)
                <a href="{{ route('gallery.index', ['category' => $value]) }}" class="px-5 py-2 rounded-full text-sm font-semibold transition {{ $category === $value ? 'bg-forest-600 text-white' : 'bg-forest-50 text-forest-700 hover:bg-forest-100' }}">{{ $label }}</a>
            @endforeach
        </div>

        @if ($images->isEmpty())
            <p class="text-forest-500">No images in this category yet.</p>
        @else
            <div class="columns-2 sm:columns-3 lg:columns-4 gap-4 space-y-4">
                @foreach ($images as $index => $image)
                    <button type="button" @click="active = {{ $index }}; lightbox = true" class="block w-full break-inside-avoid rounded-xl overflow-hidden group relative">
                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->title ?? $image->category }}" class="w-full object-cover group-hover:opacity-90 transition" loading="lazy">
                        @if ($image->title)
                            <span class="absolute bottom-0 left-0 right-0 bg-black/50 text-white text-xs px-3 py-2 opacity-0 group-hover:opacity-100 transition">{{ $image->title }}</span>
                        @endif
                    </button>
                @endforeach
            </div>
        @endif

        <div x-show="lightbox" x-cloak x-transition class="fixed inset-0 z-50 bg-black/90 flex items-center justify-center p-4" @keydown.escape.window="lightbox = false">
            <button @click="lightbox = false" class="absolute top-5 right-5 text-white text-2xl"><i class="fa-solid fa-xmark"></i></button>
            <button @click="active = (active - 1 + images.length) % images.length" class="absolute left-3 sm:left-8 text-white text-3xl px-2"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="max-w-4xl max-h-[85vh]">
                <template x-if="images.length">
                    <img :src="images[active].src" :alt="images[active].title" class="max-h-[75vh] mx-auto rounded-xl">
                </template>
                <p class="text-white text-center mt-4" x-text="images[active] ? images[active].title : ''"></p>
                <p class="text-forest-200 text-center text-sm" x-text="images[active] ? images[active].description : ''"></p>
            </div>
            <button @click="active = (active + 1) % images.length" class="absolute right-3 sm:right-8 text-white text-3xl px-2"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>
@endsection
