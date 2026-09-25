@extends('layouts.admin')

@section('title', 'Seasonal Rates - Admin')
@section('page_title', 'Seasonal Rates')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.seasonal-rates.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-plus mr-2"></i> New Seasonal Rate
            </x-button>
        </a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Facility</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Label</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Dates</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Rate</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($seasonalRates as $rate)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $rate->facility->name }}</td>
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $rate->label }}</td>
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ $rate->starts_on->format('d M Y') }} - {{ $rate->ends_on->format('d M Y') }}</td>
                            <td class="px-6 py-4 font-black text-slate-900">
                                @if ($rate->price_override !== null)
                                    <span class="bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md border border-blue-100 shadow-sm">RM {{ number_format($rate->price_override, 2) }}</span>
                                @elseif ($rate->price_multiplier !== null)
                                    <span class="bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md border border-purple-100 shadow-sm">&times;{{ number_format($rate->price_multiplier, 2) }}</span>
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $rate->is_active ? 'emerald' : 'slate' }}" class="shadow-sm">
                                    {{ $rate->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.seasonal-rates.edit', $rate) }}"
                                       class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.seasonal-rates.destroy', $rate) }}" onsubmit="return confirm('Delete this seasonal rate?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 font-medium">No seasonal rates found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($seasonalRates->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $seasonalRates->links() }}</div>
        @endif
    </x-card>
@endsection
