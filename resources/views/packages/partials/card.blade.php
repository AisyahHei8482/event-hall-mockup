<a href="{{ route('packages.show', $package) }}" class="group block rounded-2xl overflow-hidden border border-forest-100 bg-white shadow-sm hover:shadow-lg transition">
    <div class="h-48 bg-forest-100 overflow-hidden relative">
        @if ($package->cover_image)
            <img src="{{ Storage::url($package->cover_image) }}" alt="{{ $package->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center text-forest-300">
                <i class="fa-solid fa-gift text-5xl"></i>
            </div>
        @endif
        @if ($package->badge)
            <span class="absolute top-3 left-3 bg-sand-400 text-white text-xs font-semibold px-3 py-1 rounded-full">{{ $package->badge }}</span>
        @endif
    </div>
    <div class="p-5">
        <h3 class="font-bold text-lg text-forest-900 group-hover:text-forest-600 transition">{{ $package->title }}</h3>
        <p class="text-sm text-forest-600 mt-2 line-clamp-2">{{ $package->short_description }}</p>
        <div class="flex items-center justify-between mt-4">
            <span class="text-forest-800 font-semibold">
                RM {{ number_format($package->price, 2) }}
                @if ($package->original_price)
                    <span class="text-xs font-normal text-forest-400 line-through ml-1">RM {{ number_format($package->original_price, 2) }}</span>
                @endif
            </span>
            <span class="text-forest-600 text-sm font-semibold group-hover:underline">View package &rarr;</span>
        </div>
    </div>
</a>
