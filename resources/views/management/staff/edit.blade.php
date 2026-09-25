@extends('layouts.management')
@section('title', 'Edit ' . $staff->name)
@section('page_title', 'Edit Staff — ' . $staff->name)

@section('content')
<div class="max-w-xl">
    @if($errors->any())
    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-bold flex items-start gap-3 shadow-sm">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
        <ul class="list-disc list-inside space-y-0.5">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
    @endif

    <form method="POST" action="{{ route('management.staff.update', $staff) }}" class="space-y-6">
        @csrf @method('PATCH')
        <x-card class="p-6 border-slate-100 shadow-sm space-y-5">
            <h2 class="text-sm font-bold text-slate-900 uppercase tracking-widest border-b border-slate-100 pb-3 mb-5">Account Details</h2>

            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Full Name <span class="text-red-500">*</span></label>
                <x-input type="text" name="name" value="{{ old('name', $staff->name) }}" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Email <span class="text-red-500">*</span></label>
                <x-input type="email" name="email" value="{{ old('email', $staff->email) }}" required />
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Role <span class="text-red-500">*</span></label>
                <x-select name="role" required>
                    <option value="staff" @selected(old('role', $staff->role) === 'staff')>Staff</option>
                    <option value="manager" @selected(old('role', $staff->role) === 'manager')>Manager</option>
                    <option value="admin" @selected(old('role', $staff->role) === 'admin')>Admin</option>
                </x-select>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <p class="text-xs text-slate-400 font-medium mb-4">Leave password fields empty to keep existing password.</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">New Password</label>
                        <x-input type="password" name="password" placeholder="Min. 8 characters" />
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Confirm New Password</label>
                        <x-input type="password" name="password_confirmation" />
                    </div>
                </div>
            </div>
        </x-card>

        <div class="flex items-center gap-4 pt-2">
            <x-button type="submit" variant="primary" class="px-8 shadow-lg shadow-brand-500/20">Save Changes</x-button>
            <a href="{{ route('management.staff.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-900 transition-colors">Cancel</a>
        </div>
    </form>
</div>
@endsection
