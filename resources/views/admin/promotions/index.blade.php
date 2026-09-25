@extends('layouts.admin')

@section('title', 'Promotions - Admin')
@section('page_title', 'Promotions')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.promotions.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> Add Promotion
            </x-button>
        </a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Title</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Code</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Discount</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Period</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Active</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($promotions as $promotion)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $promotion->title }}</td>
                            <td class="px-6 py-4">
                                @if($promotion->code)
                                    <span class="inline-block bg-slate-100 text-slate-700 border border-slate-200 font-mono text-xs font-bold px-2 py-1 rounded-md">{{ $promotion->code }}</span>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-black text-emerald-600 text-base">
                                {{ $promotion->discount_type === 'percentage' ? $promotion->discount_value.'%' : 'RM '.number_format($promotion->discount_value, 2) }}
                            </td>
                            <td class="px-6 py-4 text-xs font-medium text-slate-500">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-700">{{ $promotion->starts_at?->format('d M Y') ?? 'Anytime' }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-slate-300"></i>
                                    <span class="text-slate-700">{{ $promotion->ends_at?->format('d M Y') ?? 'No end date' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $promotion->is_active ? 'emerald' : 'slate' }}" class="shadow-sm">
                                    {{ $promotion->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.promotions.edit', $promotion) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.promotions.destroy', $promotion) }}" onsubmit="return confirm('Delete this promotion?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 font-medium">No promotions found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($promotions->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $promotions->links() }}</div>
        @endif
    </x-card>
@endsection
