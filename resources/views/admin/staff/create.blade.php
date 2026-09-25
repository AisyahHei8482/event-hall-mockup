@extends('layouts.admin')

@section('title', 'Add Staff - Admin')
@section('page_title', 'Add Staff Member')

@section('content')
    <div class="bg-white rounded-2xl border border-forest-100 p-6 max-w-lg">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.staff.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-forest-800 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-forest-800 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-forest-800 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-forest-800 mb-1">Role</label>
                <select name="role" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
                Create Account
            </button>
        </form>
    </div>
@endsection
