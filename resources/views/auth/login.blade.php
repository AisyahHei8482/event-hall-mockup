@extends('layouts.app')

@section('title', 'Login - Savanna Hill Resort')

@section('content')
    <section class="max-w-md mx-auto px-4 py-20">
        <h1 class="text-3xl font-bold text-forest-900 text-center">Welcome Back</h1>

        @if ($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <label class="flex items-center gap-2 text-sm text-forest-700">
                <input type="checkbox" name="remember" class="rounded border-forest-300"> Remember me
            </label>
            <button type="submit" class="w-full bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-3 rounded-full transition">
                Login
            </button>
        </form>

        <p class="text-center text-sm text-forest-600 mt-6">
            Don't have an account? <a href="{{ route('register') }}" class="text-forest-800 font-semibold hover:underline">Register</a>
        </p>
    </section>
@endsection
