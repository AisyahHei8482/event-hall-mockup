@extends('layouts.app')

@section('title', $post->title.' - Savanna Hill Resort Blog')
@section('meta_description', $post->excerpt)

@section('content')
    <section class="h-72 sm:h-96 bg-forest-800 relative overflow-hidden">
        @if ($post->featured_image)
            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover opacity-80">
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 text-white">
            <span class="inline-block bg-white/20 backdrop-blur text-xs font-semibold px-3 py-1 rounded-full capitalize mb-3">{{ str_replace('-', ' ', $post->category) }}</span>
            <h1 class="text-3xl sm:text-4xl font-bold">{{ $post->title }}</h1>
            <p class="text-sm text-forest-200 mt-2">{{ $post->author_name }} &middot; {{ $post->published_at?->format('d M Y') }}</p>
        </div>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="prose prose-forest max-w-none text-forest-800 whitespace-pre-line leading-relaxed">{{ $post->body }}</div>

        <div class="mt-10 flex items-center gap-4 border-t border-forest-100 pt-6">
            <span class="text-sm font-semibold text-forest-700">Share:</span>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="text-forest-500 hover:text-forest-700"><i class="fa-brands fa-facebook text-lg"></i></a>
            <a href="https://wa.me/?text={{ urlencode($post->title.' '.url()->current()) }}" target="_blank" rel="noopener" class="text-forest-500 hover:text-forest-700"><i class="fa-brands fa-whatsapp text-lg"></i></a>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="bg-forest-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-forest-900 mb-8">Related Articles</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                    @foreach ($related as $item)
                        <a href="{{ route('blog.show', $item) }}" class="group block rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm hover:shadow-lg transition">
                            <div class="h-32 bg-forest-100 overflow-hidden">
                                @if ($item->featured_image)
                                    <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-forest-900 group-hover:text-forest-600 transition text-sm">{{ $item->title }}</h3>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
