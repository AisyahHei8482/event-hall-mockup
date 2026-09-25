@extends('layouts.admin')

@section('title', 'Packages - Admin')
@section('page_title', 'Packages')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.packages.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add Package
            </x-button>
        </a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Title</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Price</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Facilities</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Active</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($packages as $package)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $package->title }}</td>
                            <td class="px-6 py-4 font-black text-slate-900 text-base">RM {{ number_format($package->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-700 font-bold text-xs">{{ $package->facilities->count() }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $package->is_active ? 'emerald' : 'slate' }}" class="shadow-sm">
                                    {{ $package->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.packages.edit', $package) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-slate-500 font-medium">No packages found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($packages->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $packages->links() }}</div>
        @endif
    </x-card>
@endsection
