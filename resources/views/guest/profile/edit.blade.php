@extends('layouts.app')

@section('title', 'My Account - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">My Account</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Manage your personal details and password.</p>
        </div>
    </section>

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-10">
        @if (session('success'))
            <div class="bg-forest-100 border border-forest-300 text-forest-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-forest-100 rounded-2xl p-6">
            <h2 class="font-bold text-lg text-forest-900 mb-6">Profile Details</h2>
            <form method="POST" action="{{ route('guest.profile.update') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
                    Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white border border-forest-100 rounded-2xl p-6 flex items-center justify-between">
            <div>
                <h2 class="font-bold text-lg text-forest-900">Two-Factor Authentication</h2>
                <p class="text-sm text-forest-600 mt-1">
                    @if ($user->hasEnabledTwoFactorAuthentication())
                        Currently <span class="text-forest-700 font-semibold">enabled</span> on your account.
                    @else
                        Add an extra layer of security to your account.
                    @endif
                </p>
            </div>
            <a href="{{ route('guest.two-factor.show') }}" class="shrink-0 bg-forest-600 hover:bg-forest-700 text-white font-semibold px-5 py-2.5 rounded-full transition">
                Manage
            </a>
        </div>

        <div class="bg-white border border-forest-100 rounded-2xl p-6">
            <h2 class="font-bold text-lg text-forest-900 mb-6">Change Password</h2>
            <form method="POST" action="{{ route('guest.profile.password') }}" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">New Password</label>
                    <input type="password" name="password"
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
                    Update Password
                </button>
            </form>
        </div>
    </section>
@endsection
