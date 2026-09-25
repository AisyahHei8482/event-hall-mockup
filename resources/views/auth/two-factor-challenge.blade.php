@extends('layouts.app')

@section('title', 'Two-Factor Verification - Savanna Hill Resort')

@section('content')
    <section class="max-w-md mx-auto px-4 py-20" x-data="{ useRecovery: false }">
        <h1 class="text-3xl font-bold text-forest-900 text-center">Two-Factor Verification</h1>
        <p class="text-center text-sm text-forest-600 mt-3">Enter the 6-digit code from your authenticator app.</p>

        @if ($errors->any())
            <div class="mt-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('two-factor.login.store') }}" class="mt-8 space-y-5">
            @csrf
            <div x-show="!useRecovery">
                <label class="block text-sm font-medium text-forest-800 mb-1">Authentication Code</label>
                <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" autofocus
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <div x-show="useRecovery" x-cloak>
                <label class="block text-sm font-medium text-forest-800 mb-1">Recovery Code</label>
                <input type="text" name="recovery_code"
                       class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
            </div>
            <button type="submit" class="w-full bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-3 rounded-full transition">
                Verify
            </button>
        </form>

        <button @click="useRecovery = !useRecovery" class="block mx-auto text-center text-sm text-forest-500 mt-6 hover:underline">
            <span x-show="!useRecovery">Use a recovery code instead</span>
            <span x-show="useRecovery" x-cloak>Use an authentication code instead</span>
        </button>
    </section>
@endsection
