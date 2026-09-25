@extends('layouts.admin')

@section('title', 'Reviews - Admin')
@section('page_title', 'Reviews')

@section('content')
    <div class="flex gap-3 mb-8 text-sm">
        <a href="{{ route('admin.reviews.index') }}" class="px-5 py-2.5 rounded-full font-bold transition-colors {{ !request('status') ? 'bg-forest-600 text-white shadow-lg shadow-forest-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">All</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'pending']) }}" class="px-5 py-2.5 rounded-full font-bold transition-colors {{ request('status') === 'pending' ? 'bg-forest-600 text-white shadow-lg shadow-forest-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Pending</a>
        <a href="{{ route('admin.reviews.index', ['status' => 'approved']) }}" class="px-5 py-2.5 rounded-full font-bold transition-colors {{ request('status') === 'approved' ? 'bg-forest-600 text-white shadow-lg shadow-forest-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">Approved</a>
    </div>

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Facility</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Guest</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Rating</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Comment</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $review->facility->name ?? '-' }}</td>
                            <td class="px-6 py-4 font-medium text-slate-700">{{ $review->user->name ?? $review->guest_name }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="fa-solid fa-star text-[10px] {{ $i <= $review->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                    @endfor
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-slate-600 max-w-xs truncate" title="{{ $review->comment }}">{{ $review->comment }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <x-badge variant="{{ $review->is_approved ? 'emerald' : 'amber' }}" class="shadow-sm">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </x-badge>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <form method="POST" action="{{ route('admin.reviews.update', $review) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_approved" value="{{ $review->is_approved ? 0 : 1 }}">
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold {{ $review->is_approved ? 'bg-slate-100 text-slate-700 hover:bg-amber-100 hover:text-amber-700' : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' }} rounded-lg transition-colors">
                                            {{ $review->is_approved ? 'Unapprove' : 'Approve' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('Delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center text-slate-500 font-medium">No reviews found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reviews->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $reviews->links() }}</div>
        @endif
    </x-card>
@endsection
