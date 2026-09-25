@extends('layouts.app')

@section('title', 'Two-Factor Authentication - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Two-Factor Authentication</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Add an extra layer of security to your account.</p>
        </div>
    </section>

    <section class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
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

        @if (session('recoveryCodes'))
            <div class="bg-sand-50 border border-sand-200 rounded-2xl p-6">
                <h2 class="font-bold text-forest-900 mb-2">Save your recovery codes</h2>
                <p class="text-sm text-forest-600 mb-4">Store these somewhere safe. Each code can be used once if you lose access to your authenticator app.</p>
                <div class="grid grid-cols-2 gap-2 font-mono text-sm bg-white rounded-lg p-4">
                    @foreach (session('recoveryCodes') as $code)
                        <span>{{ $code }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="bg-white border border-forest-100 rounded-2xl p-6">
            @if ($user->hasEnabledTwoFactorAuthentication())
                <div class="flex items-center gap-2 text-forest-700 font-semibold mb-4">
                    <i class="fa-solid fa-shield-halved"></i> Two-factor authentication is enabled
                </div>
                <form method="POST" action="{{ route('guest.two-factor.destroy') }}" onsubmit="return confirm('Disable two-factor authentication?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                        Disable Two-Factor Authentication
                    </button>
                </form>
            @elseif ($qrCodeUrl)
                <h2 class="font-bold text-lg text-forest-900 mb-4">Scan this QR code</h2>
                <p class="text-sm text-forest-600 mb-4">Scan with Google Authenticator, Authy, or a similar app, then enter the 6-digit code below.</p>
                <div class="flex justify-center bg-forest-50 rounded-xl p-6 mb-4">
                    {!! $qrCodeUrl !!}
                </div>
                <form method="POST" action="{{ route('guest.two-factor.confirm') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-forest-800 mb-1">Verification Code</label>
                        <input type="text" name="code" inputmode="numeric" autocomplete="one-time-code" required
                               class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                    </div>
                    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                        Confirm & Enable
                    </button>
                </form>
            @else
                <p class="text-sm text-forest-600 mb-4">Two-factor authentication is currently disabled. Enabling it requires an authenticator code each time you log in.</p>
                <form method="POST" action="{{ route('guest.two-factor.store') }}">
                    @csrf
                    <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-6 py-2.5 rounded-full transition">
                        Enable Two-Factor Authentication
                    </button>
                </form>
            @endif
        </div>

        <a href="{{ route('guest.profile.edit') }}" class="block text-center text-sm text-forest-500 hover:underline">Back to My Account</a>
    </section>
@endsection
