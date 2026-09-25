@extends('layouts.admin')

@section('title', 'Activity Log - Admin')
@section('page_title', 'Activity Log')

@section('content')
    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">When</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">User</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Action</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4 font-medium text-slate-500 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <i class="fa-regular fa-clock text-slate-300"></i>
                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-6 py-4"><span class="text-xs font-mono font-bold bg-slate-100 text-slate-600 px-2 py-1 rounded shadow-sm">{{ $log->action }}</span></td>
                            <td class="px-6 py-4 text-slate-900 font-medium">{{ $log->description }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center text-slate-500 font-medium">No activity recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $logs->links() }}</div>
        @endif
    </x-card>
@endsection
