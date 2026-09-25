@extends('layouts.app')

@section('title', 'Register - Savanna Hill Resort')

@section('content')
    <section class="max-w-md mx-auto px-4 py-20">
        <h1 class="text-3xl font-bold text-forest-900 text-center">Create an Account</h1>

        @if ($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <label class="flex items-start gap-2 text-sm text-forest-700">
                <input type="checkbox" name="privacy_consent" value="1" required class="mt-1 rounded border-forest-300">
                <span>I agree to the <a href="{{ route('privacy-policy') }}" target="_blank" class="text-forest-800 font-semibold hover:underline">Privacy Policy</a> and consent to my data being processed as described.</span>
            </label>
            <button type="submit" class="w-full bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-3 rounded-full transition">
                Register
            </button>
        </form>

        <p class="text-center text-sm text-forest-600 mt-6">
            Already have an account? <a href="{{ route('login') }}" class="text-forest-800 font-semibold hover:underline">Login</a>
        </p>
    </section>
@endsection
