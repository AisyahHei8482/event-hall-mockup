@extends('layouts.admin')

@section('title', 'Facilities - Admin')
@section('page_title', 'Facilities')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.facilities.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add Facility
            </x-button>
        </a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Name</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Type</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Price</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Featured</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Active</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($facilities as $facility)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $facility->name }}</td>
                            <td class="px-6 py-4">
                                <span class="capitalize font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-md">{{ str_replace('_', ' ', $facility->type) }}</span>
                            </td>
                            <td class="px-6 py-4 font-black text-slate-900 text-base">{{ $facility->price ? 'RM '.number_format($facility->price, 2) : '-' }}</td>
                            <td class="px-6 py-4">
                                @if ($facility->is_featured)
                                    <span class="text-amber-500"><i class="fa-solid fa-star"></i></span>
                                @else
                                    <span class="text-slate-300"><i class="fa-regular fa-star"></i></span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $facility->is_active ? 'emerald' : 'slate' }}" class="shadow-sm">
                                    {{ $facility->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.facilities.edit', $facility) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.facilities.destroy', $facility) }}" onsubmit="return confirm('Delete this facility?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 font-medium">No facilities found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facilities->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $facilities->links() }}</div>
        @endif
    </x-card>
@endsection
