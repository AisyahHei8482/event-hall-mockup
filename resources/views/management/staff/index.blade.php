@extends('layouts.management')
@section('title', 'Staff & Roles')
@section('page_title', 'Staff & Roles')
@section('page_subtitle', 'Manage portal users and access levels')

@section('content')

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm font-bold flex items-center gap-3 shadow-sm">
    <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-bold flex items-center gap-3 shadow-sm">
    <i class="fa-solid fa-circle-exclamation text-red-500"></i> {{ session('error') }}
</div>
@endif

{{-- Role Matrix Info --}}
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-red-50 border border-red-100 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-7 h-7 rounded-lg bg-red-500 flex items-center justify-center"><i class="fa-solid fa-crown text-white text-xs"></i></span>
            <span class="text-xs font-bold uppercase tracking-widest text-red-700">Admin</span>
        </div>
        <p class="text-xs text-red-600 font-medium leading-relaxed">Full access — all halls, quotations, bookings, staff management, reports, franchises.</p>
    </div>
    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-7 h-7 rounded-lg bg-amber-500 flex items-center justify-center"><i class="fa-solid fa-user-tie text-white text-xs"></i></span>
            <span class="text-xs font-bold uppercase tracking-widest text-amber-700">Manager</span>
        </div>
        <p class="text-xs text-amber-600 font-medium leading-relaxed">Manage quotations, bookings, halls, view reports. Cannot manage staff or delete franchises.</p>
    </div>
    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <span class="w-7 h-7 rounded-lg bg-slate-400 flex items-center justify-center"><i class="fa-solid fa-user text-white text-xs"></i></span>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-500">Staff</span>
        </div>
        <p class="text-xs text-slate-500 font-medium leading-relaxed">View and update assigned bookings and quotations. Read-only on halls and reports.</p>
    </div>
</div>

<div class="flex justify-end mb-6">
    <a href="{{ route('management.staff.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-brand-500/20 transition-colors uppercase tracking-widest">
        <i class="fa-solid fa-user-plus"></i> Add Staff
    </a>
</div>

<x-card class="overflow-hidden border-slate-100 shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Name</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Email</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Role</th>
                    <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Joined</th>
                    <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($staff as $member)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-white text-xs font-bold shadow-sm
                                @if($member->role === 'admin') bg-red-500
                                @elseif($member->role === 'manager') bg-amber-500
                                @else bg-slate-400 @endif">
                                {{ strtoupper(substr($member->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-bold text-slate-900">{{ $member->name }}</div>
                                @if($member->id === auth()->id())
                                <div class="text-xs text-brand-600 font-bold">You</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">{{ $member->email }}</td>
                    <td class="px-6 py-4">
                        @php
                            $roleColor = match($member->role) {
                                'admin'   => 'bg-red-50 text-red-700 border-red-100',
                                'manager' => 'bg-amber-50 text-amber-700 border-amber-100',
                                default   => 'bg-slate-100 text-slate-600 border-slate-200',
                            };
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-bold uppercase tracking-widest rounded-lg border {{ $roleColor }}">
                            {{ ucfirst($member->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-xs font-medium text-slate-500">{{ $member->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('management.staff.edit', $member) }}"
                               class="inline-flex items-center px-3 py-1.5 text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg transition-colors">
                                Edit
                            </a>
                            @if($member->id !== auth()->id())
                            <form method="POST" action="{{ route('management.staff.destroy', $member) }}"
                                  onsubmit="return confirm('Remove {{ $member->name }} from the system?')">
                                @csrf @method('DELETE')
                                <button class="inline-flex items-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">
                                    Remove
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-6 py-16 text-center text-slate-500 font-medium">No staff members found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($staff->hasPages())
    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $staff->links() }}</div>
    @endif
</x-card>
@endsection
