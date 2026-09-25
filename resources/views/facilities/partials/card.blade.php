<a href="{{ route('facilities.show', $facility) }}" class="block group h-full">
    <x-card class="h-full flex flex-col overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
        <div class="h-56 bg-forest-100 overflow-hidden relative">
            @if ($facility->cover_image)
                <img src="{{ Storage::url($facility->cover_image) }}" alt="{{ $facility->name }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-out">
            @else
                <div class="w-full h-full flex items-center justify-center text-forest-300 group-hover:scale-110 transition duration-700 ease-out bg-gradient-to-br from-forest-50 to-forest-100">
                    <i class="fa-solid fa-tree text-6xl opacity-30"></i>
                </div>
            @endif
            <div class="absolute top-3 left-3 flex gap-2">
                <span class="bg-white/95 backdrop-blur-sm shadow-sm text-forest-800 text-xs font-bold px-3 py-1.5 rounded-full capitalize tracking-wide">
                    {{ str_replace('_', ' ', $facility->type) }}
                </span>
            </div>
        </div>
        <div class="p-6 flex flex-col flex-1 bg-white">
            <h3 class="font-bold text-xl text-forest-900 group-hover:text-forest-600 transition-colors">{{ $facility->name }}</h3>
            <p class="text-sm text-slate-600 mt-2 line-clamp-2 leading-relaxed">{{ $facility->short_description }}</p>
            
            <div class="mt-auto pt-5 mt-5 flex items-center justify-between border-t border-slate-100">
                @if ($facility->price)
                    <div class="flex flex-col">
                        <span class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-0.5">Starting at</span>
                        <span class="text-forest-800 font-bold text-lg">RM {{ number_format($facility->price, 2) }}
                            <span class="text-xs font-medium text-slate-500">/{{ $facility->price_unit }}</span>
                        </span>
                    </div>
                @else
                    <span class="text-sm font-medium text-slate-500">Contact for pricing</span>
                @endif
                <div class="w-10 h-10 rounded-full bg-forest-50 flex items-center justify-center text-forest-600 group-hover:bg-forest-600 group-hover:text-white transition-colors">
                    <i class="fa-solid fa-arrow-right text-sm"></i>
                </div>
            </div>
        </div>
    </x-card>
</a>
