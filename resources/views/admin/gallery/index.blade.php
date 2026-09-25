@extends('layouts.admin')

@section('title', 'Gallery - Admin')
@section('page_title', 'Gallery')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.gallery.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Upload Image
            </x-button>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6">
        @forelse ($images as $image)
            <x-card class="overflow-hidden border-slate-100 hover:shadow-xl transition-all duration-300 group flex flex-col h-full bg-white">
                <div class="relative h-40 bg-slate-100 overflow-hidden shrink-0">
                    <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                    <div class="absolute top-3 right-3">
                        <x-badge variant="{{ $image->is_active ? 'emerald' : 'slate' }}" class="backdrop-blur-md bg-opacity-90 shadow-sm border-0 {{ $image->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                            {{ $image->is_active ? 'Active' : 'Inactive' }}
                        </x-badge>
                    </div>
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $image->category }}</span>
                    <h3 class="text-sm font-bold text-slate-900 truncate mb-4">{{ $image->title ?: '—' }}</h3>
                    <div class="flex items-center gap-2 mt-auto pt-4 border-t border-slate-100">
                        <a href="{{ route('admin.gallery.edit', $image) }}"
                           class="flex-1 flex items-center justify-center h-8 rounded-lg bg-slate-50 text-xs font-bold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-colors shadow-sm border border-slate-100">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.gallery.destroy', $image) }}" onsubmit="return confirm('Delete this image?');" class="shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors">
                                <i class="fa-solid fa-trash text-xs"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </x-card>
        @empty
            <div class="col-span-2 sm:col-span-3 lg:col-span-5 p-16 text-center border border-slate-100 bg-slate-50 rounded-2xl">
                <i class="fa-solid fa-image text-4xl text-slate-300 mb-4"></i>
                <p class="text-slate-500 font-medium">No images found.</p>
            </div>
        @endforelse
    </div>

    @if($images->hasPages())
    <div class="mt-8">{{ $images->links() }}</div>
    @endif
@endsection
