@extends('layouts.app')

@section('title', 'Blog - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Blog</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Resort news, guest stories, travel tips, and everything happening at Savanna Hill Resort.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if ($posts->isEmpty())
            <p class="text-forest-500">No articles published yet. Check back soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <a href="{{ route('blog.show', $post) }}" class="group block rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm hover:shadow-lg transition">
                        <div class="h-44 bg-forest-100 overflow-hidden">
                            @if ($post->featured_image)
                                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-forest-300"><i class="fa-solid fa-newspaper text-4xl"></i></div>
                            @endif
                        </div>
                        <div class="p-5">
                            <span class="text-xs font-semibold text-forest-500 uppercase tracking-wide">{{ str_replace('-', ' ', $post->category) }}</span>
                            <h3 class="font-bold text-lg text-forest-900 group-hover:text-forest-600 transition mt-1">{{ $post->title }}</h3>
                            <p class="text-sm text-forest-600 mt-2 line-clamp-2">{{ $post->excerpt }}</p>
                            <p class="text-xs text-forest-400 mt-3">{{ $post->author_name }} &middot; {{ $post->published_at?->format('d M Y') }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @endif
    </section>
@endsection
