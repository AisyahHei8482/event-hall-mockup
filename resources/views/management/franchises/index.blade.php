@extends('layouts.management')
@section('title', 'Franchises')
@section('page_title', 'Franchises')
@section('page_subtitle', 'Manage franchise locations')

@section('content')
<div class="flex justify-end mb-8">
    <a href="{{ route('management.franchises.create') }}" class="inline-block">
        <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
            <i class="fa-solid fa-plus mr-2"></i> New Franchise
        </x-button>
    </a>
</div>

<x-card class="overflow-hidden border-slate-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Franchise</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden md:table-cell">Code</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden lg:table-cell">Halls</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs hidden lg:table-cell">Bookings</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                    <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($franchises as $franchise)
                <tr class="hover:bg-amber-50/30 transition-colors group">
                    <td class="px-6 py-4 min-w-[250px] whitespace-nowrap">
                        <div class="flex items-center gap-4">
                            @if($franchise->logo)
                            <img src="{{ asset('media/'.$franchise->logo) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm group-hover:scale-105 transition-transform">
                            @else
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-building text-amber-600 text-lg"></i>
                            </div>
                            @endif
                            <div>
                                <div class="font-bold text-slate-900 text-base group-hover:text-amber-700 transition-colors">{{ $franchise->name }}</div>
                                <div class="text-xs font-medium text-slate-500 mt-1">{{ $franchise->company_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-md">{{ $franchise->code }}</span>
                    </td>
                    <td class="px-6 py-4 hidden lg:table-cell font-bold text-slate-700">{{ $franchise->event_halls_count }}</td>
                    <td class="px-6 py-4 hidden lg:table-cell font-bold text-slate-700">{{ $franchise->bookings_count }}</td>
                    <td class="px-6 py-4">
                        <x-badge variant="{{ $franchise->status === 'active' ? 'emerald' : 'slate' }}" class="capitalize shadow-sm">
                            {{ $franchise->status }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('management.franchises.show', $franchise) }}"
                               class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-amber-100 hover:text-amber-700 rounded-lg transition-colors">
                                View
                            </a>
                            <a href="{{ route('management.franchises.edit', $franchise) }}"
                               class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('management.franchises.destroy', $franchise) }}"
                                  onsubmit="return confirm('Delete this franchise?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 font-medium">
                    No franchises yet. <a href="{{ route('management.franchises.create') }}" class="text-amber-600 font-bold hover:text-amber-700 transition-colors">Create one &rarr;</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($franchises->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $franchises->links() }}</div>
    @endif
</x-card>
@endsection
