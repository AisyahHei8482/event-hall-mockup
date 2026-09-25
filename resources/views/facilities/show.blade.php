@extends('layouts.app')

@section('title', $facility->name.' - Savanna Hill Resort')
@section('meta_description', $facility->short_description)

@section('content')
    <section class="relative h-[60vh] min-h-[400px] max-h-[600px] bg-forest-900 overflow-hidden">
        @if ($facility->cover_image)
            <img src="{{ Storage::url($facility->cover_image) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover opacity-60 mix-blend-overlay">
        @else
            <div class="w-full h-full bg-[url('https://images.unsplash.com/photo-1542314831-c6a4d14d2301?auto=format&fit=crop&q=80')] bg-cover bg-center opacity-30 mix-blend-overlay"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-forest-950 via-forest-900/60 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 z-10">
            <div class="animate-fade-in-down">
                <span class="inline-block bg-white/10 backdrop-blur-md text-forest-50 border border-white/20 text-xs font-semibold px-4 py-1.5 rounded-full capitalize mb-4 tracking-widest shadow-xl">
                    <i class="fa-solid fa-leaf mr-2"></i> {{ str_replace('_', ' ', $facility->type) }}
                </span>
                @if($facility->accommodation_type)
                    <span class="inline-block bg-brand-500/80 backdrop-blur-md text-white border border-brand-400/50 text-xs font-semibold px-4 py-1.5 rounded-full capitalize mb-4 tracking-widest shadow-xl ml-2">
                        <i class="fa-solid fa-bed mr-2"></i> {{ $facility->accommodation_type }}
                    </span>
                @endif
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white drop-shadow-xl animate-fade-in-up tracking-tight" style="animation-delay: 100ms;">
                {{ $facility->name }}
            </h1>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-24 -mt-8 relative z-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <x-card class="p-8 shadow-xl border-slate-200/60 bg-white/95 backdrop-blur-sm animate-fade-in-up" style="animation-delay: 200ms;">
                    <p class="text-xl text-forest-800 font-medium leading-relaxed mb-6">{{ $facility->short_description }}</p>
                    <div class="prose prose-forest prose-lg max-w-none text-slate-600 whitespace-pre-line">{{ $facility->description }}</div>
                </x-card>

            @if (!empty($facility->amenities))
                <x-card class="p-8 shadow-sm border-slate-200/60 bg-white/95 animate-fade-in-up" style="animation-delay: 300ms;">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6">Amenities</h2>
                    <ul class="grid grid-cols-2 sm:grid-cols-3 gap-y-4 gap-x-6">
                        @foreach ($facility->amenities as $amenity)
                            <li class="flex items-center gap-3 text-slate-700 font-medium">
                                <div class="w-8 h-8 rounded-full bg-forest-50 flex items-center justify-center text-forest-500 shrink-0">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </div>
                                {{ $amenity }}
                            </li>
                        @endforeach
                    </ul>
                </x-card>
            @endif

            @if ($facility->virtual_tour_url)
                <x-card class="p-8 shadow-sm border-slate-200/60 bg-white/95 animate-fade-in-up" style="animation-delay: 400ms;">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6">360&deg; Virtual Tour</h2>
                    <div class="aspect-video rounded-2xl overflow-hidden border border-slate-200 shadow-inner">
                        <iframe src="{{ $facility->virtual_tour_url }}" class="w-full h-full" allowfullscreen loading="lazy"></iframe>
                    </div>
                </x-card>
            @endif

            @if ($facility->images->isNotEmpty())
                <x-card class="p-8 shadow-sm border-slate-200/60 bg-white/95 animate-fade-in-up" style="animation-delay: 500ms;">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-6">Gallery</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach ($facility->images as $image)
                            <a href="{{ Storage::url($image->path) }}" target="_blank" class="block group relative rounded-2xl overflow-hidden aspect-square border border-slate-200 bg-slate-100">
                                <img src="{{ Storage::url($image->path) }}" alt="{{ $image->caption }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-forest-900/0 group-hover:bg-forest-900/20 transition-colors"></div>
                            </a>
                        @endforeach
                    </div>
                </x-card>
            @endif

            <x-card class="p-8 shadow-sm border-slate-200/60 bg-white/95 animate-fade-in-up" style="animation-delay: 600ms;">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-bold text-slate-500 uppercase tracking-widest">Guest Reviews</h2>
                    @if ($facility->approvedReviews->count())
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-star text-amber-500"></i>
                            <span class="font-bold text-slate-800">{{ $facility->average_rating }}</span>
                            <span class="text-slate-500 text-sm">({{ $facility->approvedReviews->count() }})</span>
                        </div>
                    @endif
                </div>
                @forelse ($facility->approvedReviews as $review)
                    <div class="border-b border-forest-100 py-4">
                        <div class="flex items-center gap-2">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa-solid fa-star text-xs {{ $i <= $review->rating ? 'text-sand-400' : 'text-forest-100' }}"></i>
                            @endfor
                            <span class="font-semibold text-sm text-forest-800">{{ $review->user->name ?? $review->guest_name }}</span>
                        </div>
                        <p class="text-sm text-forest-600 mt-2">{{ $review->comment }}</p>
                    </div>
                @empty
                    <p class="text-forest-500 text-sm">No reviews yet. Be the first to share your experience.</p>
                @endforelse

                <div class="mt-8 border border-forest-100 rounded-2xl p-6" x-data="{ rating: 5 }">
                    <h3 class="font-bold text-forest-900 mb-4">Leave a Review</h3>
                    @if (session('success'))
                        <div class="mb-4 bg-forest-100 border border-forest-300 text-forest-800 px-4 py-3 rounded-lg text-sm">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('reviews.store', $facility) }}">
                        @csrf
                        <div class="flex items-center gap-2 mb-6">
                            @for ($i = 1; $i <= 5; $i++)
                                <button type="button" @click="rating = {{ $i }}" class="text-2xl transition-transform hover:scale-110">
                                    <i class="fa-solid fa-star" :class="rating >= {{ $i }} ? 'text-amber-400 drop-shadow-md' : 'text-slate-200'"></i>
                                </button>
                            @endfor
                            <input type="hidden" name="rating" x-model="rating">
                        </div>
                        @guest
                            <div class="mb-5">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Your Name</label>
                                <input type="text" name="guest_name" value="{{ old('guest_name') }}"
                                       class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-forest-400 transition-shadow">
                            </div>
                        @endguest
                        <div class="mb-5">
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Comment</label>
                            <textarea name="comment" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-3 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-forest-400 transition-shadow resize-none">{{ old('comment') }}</textarea>
                        </div>
                        @if ($errors->any())
                            <x-alert variant="danger" class="mb-5 text-sm">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </x-alert>
                        @endif
                        <x-button type="submit" variant="primary" class="shadow-lg shadow-forest-500/20 px-8 py-3">
                            <i class="fa-solid fa-paper-plane mr-2"></i> Submit Review
                        </x-button>
                    </form>
                </x-card>
            </div>
        </div>

        <!-- Sidebar Booking Widget -->
        <aside class="lg:col-span-1 animate-fade-in-up" style="animation-delay: 700ms;">
            <div class="sticky top-28">
                <x-card class="p-8 shadow-2xl border-slate-200/60 bg-white/95 backdrop-blur-md">
                    @if ($facility->price)
                        <div class="mb-8 border-b border-slate-100 pb-6 text-center">
                            <span class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Starting at</span>
                            <div class="flex items-baseline justify-center gap-2">
                                <span class="text-4xl font-black text-brand-600 tracking-tight">RM {{ number_format($facility->price, 2) }}</span>
                                <span class="text-slate-500 font-medium">/{{ $facility->price_unit }}</span>
                            </div>
                            @if ($facility->capacity)
                                <span class="inline-block mt-3 bg-slate-50 border border-slate-200 text-slate-600 text-xs font-bold px-3 py-1 rounded-full uppercase tracking-widest">
                                    <i class="fa-solid fa-users mr-1"></i> Up to {{ $facility->capacity }} pax
                                </span>
                            @endif
                        </div>

                        <a href="{{ route('bookings.create', $facility) }}" class="block w-full">
                            <x-button variant="primary" class="w-full py-4 text-base shadow-xl shadow-forest-500/20 justify-center">
                                <i class="fa-solid fa-calendar-check mr-2"></i> Book Now
                            </x-button>
                        </a>
                    @else
                        <div class="text-center py-6 border-b border-slate-100 mb-6">
                            <div class="w-16 h-16 mx-auto bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                <i class="fa-regular fa-envelope text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-2">Contact for Pricing</h3>
                            <p class="text-slate-500 text-sm">This experience requires a custom quotation.</p>
                        </div>
                    @endif

                    <div class="space-y-3 mt-6">
                        <a href="{{ route('contact.create') }}" class="block w-full text-center border border-slate-200 text-slate-600 font-bold px-6 py-3 rounded-xl hover:bg-slate-50 transition-colors uppercase tracking-widest text-xs">
                            <i class="fa-regular fa-circle-question mr-2"></i> Ask a Question
                        </a>

                        @auth
                            @if (auth()->user()->wishlistedFacilities->contains($facility->id))
                                <form method="POST" action="{{ route('guest.wishlist.destroy', $facility) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-center border border-red-200 bg-red-50 text-red-600 font-bold px-6 py-3 rounded-xl hover:bg-red-100 transition-colors uppercase tracking-widest text-xs">
                                        <i class="fa-solid fa-heart text-red-500 mr-2"></i> Remove Wishlist
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('guest.wishlist.store', $facility) }}">
                                    @csrf
                                    <button type="submit" class="w-full text-center border border-slate-200 text-slate-600 font-bold px-6 py-3 rounded-xl hover:bg-slate-50 transition-colors uppercase tracking-widest text-xs">
                                        <i class="fa-regular fa-heart mr-2"></i> Add to Wishlist
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="block w-full text-center border border-slate-200 text-slate-600 font-bold px-6 py-3 rounded-xl hover:bg-slate-50 transition-colors uppercase tracking-widest text-xs">
                                <i class="fa-regular fa-heart mr-2"></i> Add to Wishlist
                            </a>
                        @endauth
                    </div>
                </x-card>
            </div>
        </aside>

    </section>

    @if ($related->isNotEmpty())
        <section class="bg-forest-50 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-forest-900 mb-8">You may also like</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($related as $item)
                        @include('facilities.partials.card', ['facility' => $item])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
