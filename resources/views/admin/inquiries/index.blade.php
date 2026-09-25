@extends('layouts.admin')

@section('title', 'Inquiries - Admin')
@section('page_title', 'Inquiries')

@section('content')
    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Name</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Contact</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Type</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Message</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($inquiries as $inquiry)
                        <tr class="hover:bg-forest-50/30 transition-colors group align-top">
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $inquiry->name }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-900">{{ $inquiry->email }}</div>
                                <div class="text-xs font-medium text-slate-500 mt-1">{{ $inquiry->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="capitalize font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded-md">{{ $inquiry->type }}</span>
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                <p class="text-slate-600 line-clamp-2" title="{{ $inquiry->message }}">{{ $inquiry->message }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}">
                                    @csrf
                                    @method('PATCH')
                                    <x-select name="status" onchange="this.form.submit()" class="bg-slate-50 py-1.5 px-3 text-xs w-auto">
                                        @foreach (['new', 'in_progress', 'resolved'] as $status)
                                            <option value="{{ $status }}" @selected($inquiry->status === $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                                        @endforeach
                                    </x-select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-16 text-center text-slate-500 font-medium">No inquiries found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inquiries->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $inquiries->links() }}</div>
        @endif
    </x-card>
@endsection
