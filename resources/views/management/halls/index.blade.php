@extends('layouts.management')
@section('title', 'Event Halls')
@section('page_title', 'Event Halls')
@section('page_subtitle', 'Manage all halls across franchises')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div></div>
    <a href="{{ route('management.halls.create') }}" class="inline-block">
        <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
            <i class="fa-solid fa-plus mr-2"></i> New Hall
        </x-button>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    @forelse($halls as $hall)
    <x-card class="overflow-hidden border-slate-100 hover:shadow-xl transition-all duration-300 group flex flex-col h-full bg-white">
        <!-- Cover Image -->
        <div class="relative h-48 bg-slate-100 overflow-hidden shrink-0">
            @if($hall->cover_image)
            <img src="{{ asset('media/'.$hall->cover_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            @else
            <div class="w-full h-full flex items-center justify-center bg-slate-100">
                <i class="fa-solid fa-door-open text-5xl text-slate-300"></i>
            </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
            <div class="absolute top-4 left-4">
                <x-badge variant="{{ $hall->is_active ? 'emerald' : 'slate' }}" class="backdrop-blur-md bg-opacity-90 shadow-sm border-0 {{ $hall->is_active ? 'bg-emerald-500 text-white' : 'bg-slate-700 text-white' }}">
                    {{ $hall->is_active ? 'Active' : 'Inactive' }}
                </x-badge>
            </div>
            <div class="absolute top-4 right-4">
                <span class="text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-full bg-black/60 text-white backdrop-blur-md shadow-sm">
                    {{ $hall->bookings_count }} bookings
                </span>
            </div>
            <div class="absolute bottom-4 left-4 right-4 flex justify-between items-end">
                <div class="min-w-0">
                    <h3 class="font-bold text-white text-xl truncate tracking-tight shadow-sm">{{ $hall->name }}</h3>
                    <div class="text-xs font-medium text-slate-300 mt-1 truncate">{{ $hall->franchise->name }}</div>
                </div>
                <span class="font-mono text-[10px] font-bold bg-white/20 text-white px-2 py-1 rounded-md backdrop-blur-md shrink-0">{{ $hall->code }}</span>
            </div>
        </div>

        <div class="p-6 flex flex-col flex-1">
            <div class="flex flex-wrap items-center gap-4 text-xs font-medium text-slate-500 mb-6">
                <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><i class="fa-solid fa-users text-slate-400"></i> {{ $hall->capacity }} pax</span>
                @if($hall->floor_area)
                <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><i class="fa-solid fa-ruler-combined text-slate-400"></i> {{ $hall->floor_area }}m&sup2;</span>
                @endif
                @if($hall->hall_type)
                <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100"><i class="fa-solid fa-tag text-slate-400"></i> {{ $hall->hall_type }}</span>
                @endif
            </div>

            <div class="flex items-center gap-3 mt-auto pt-4 border-t border-slate-100">
                <a href="{{ route('management.halls.show', $hall) }}" class="flex-1">
                    <x-button variant="outline" class="w-full justify-center bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 transition-colors shadow-sm">
                        Manage
                    </x-button>
                </a>
                <a href="{{ route('management.halls.edit', $hall) }}"
                   class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-slate-900 transition-colors">
                    <i class="fa-solid fa-pen text-sm"></i>
                </a>
                <form method="POST" action="{{ route('management.halls.destroy', $hall) }}"
                      onsubmit="return confirm('Delete this hall?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="flex items-center justify-center w-10 h-10 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 hover:text-red-600 transition-colors">
                        <i class="fa-solid fa-trash text-sm"></i>
                    </button>
                </form>
            </div>
        </div>
    </x-card>
    @empty
    <x-card class="md:col-span-3 p-16 text-center border-slate-100 bg-slate-50/50 shadow-sm flex flex-col items-center justify-center min-h-[300px]">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
            <i class="fa-solid fa-door-open text-4xl text-slate-300"></i>
        </div>
        <p class="text-slate-500 font-medium mb-6 text-lg">No event halls yet.</p>
        <a href="{{ route('management.halls.create') }}" class="inline-block">
            <x-button variant="primary" class="px-8 py-3 shadow-lg">
                <i class="fa-solid fa-plus mr-2"></i> Create First Hall
            </x-button>
        </a>
    </x-card>
    @endforelse
</div>

@if($halls->hasPages())
<div class="mt-8">{{ $halls->links() }}</div>
@endif
@endsection
