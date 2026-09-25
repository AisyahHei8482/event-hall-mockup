@extends('layouts.admin')

@section('title', 'Staff - Admin')
@section('page_title', 'Staff & Admin Users')

@section('content')
    <div class="flex justify-end mb-8">
        <a href="{{ route('admin.staff.create') }}" class="inline-block">
            <x-button variant="primary" class="shadow-lg shadow-emerald-500/20 px-6 py-2.5">
                <i class="fa-solid fa-user-plus mr-2"></i> Add Staff
            </x-button>
        </a>
    </div>

    @if ($errors->any())
        <x-alert variant="danger" class="mb-8">
            <ul class="list-disc list-inside text-sm font-medium">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </x-alert>
    @endif

    <x-card class="overflow-hidden border-slate-100 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Name</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Email</th>
                        <th class="text-left px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Role</th>
                        <th class="text-right px-6 py-4 font-bold text-slate-900 uppercase tracking-wide text-xs">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($staff as $member)
                        <tr class="hover:bg-forest-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs uppercase shadow-sm border border-slate-200 shrink-0">
                                        {{ substr($member->name, 0, 2) }}
                                    </div>
                                    <div class="font-bold text-slate-900">
                                        {{ $member->name }}
                                        @if ($member->id === auth()->id())
                                            <span class="ml-2 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-100 uppercase tracking-wider">You</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600">{{ $member->email }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.staff.update', $member) }}" class="inline-flex items-center gap-2 m-0">
                                    @csrf
                                    @method('PATCH')
                                    <x-select name="role" onchange="this.form.submit()" class="bg-slate-50 py-1.5 px-3 text-xs w-32 border-slate-200 shadow-sm">
                                        <option value="staff" @selected($member->role === 'staff')>Staff</option>
                                        <option value="admin" @selected($member->role === 'admin')>Admin</option>
                                    </x-select>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @unless ($member->id === auth()->id())
                                        <form method="POST" action="{{ route('admin.staff.destroy', $member) }}" onsubmit="return confirm('Remove this staff member?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-bold bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors">Remove</button>
                                        </form>
                                    @endunless
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-6 py-16 text-center text-slate-500 font-medium">No staff members found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($staff->hasPages())
        <div class="px-6 py-5 border-t border-slate-100 bg-slate-50">{{ $staff->links() }}</div>
        @endif
    </x-card>
@endsection
