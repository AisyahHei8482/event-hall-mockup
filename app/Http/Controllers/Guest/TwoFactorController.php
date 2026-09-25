<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQRCode;

class TwoFactorController extends Controller
{
    public function show(): View
    {
        $user = auth()->user();
        $qrCodeUrl = null;

        if ($user->two_factor_secret && ! $user->two_factor_confirmed_at) {
            $google2fa = new Google2FAQRCode;
            $qrCodeUrl = $google2fa->getQRCodeInline(
                config('app.name'),
                $user->email,
                decrypt($user->two_factor_secret)
            );
        }

        return view('guest.two-factor.show', compact('user', 'qrCodeUrl'));
    }

    public function store(): RedirectResponse
    {
        $google2fa = new Google2FA;

        auth()->user()->forceFill([
            'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('guest.two-factor.show');
    }

    public function confirm(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $user = auth()->user();
        $google2fa = new Google2FA;

        if (! $user->two_factor_secret || ! $google2fa->verifyKey(decrypt($user->two_factor_secret), $request->string('code'))) {
            return back()->withErrors(['code' => 'The verification code is invalid.']);
        }

        $recoveryCodes = collect(range(1, 8))->map(fn () => Str::random(10).'-'.Str::random(10))->all();

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
        ])->save();

        return redirect()->route('guest.two-factor.show')->with('recoveryCodes', $recoveryCodes)->with('success', 'Two-factor authentication enabled.');
    }

    public function destroy(): RedirectResponse
    {
        auth()->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return redirect()->route('guest.two-factor.show')->with('success', 'Two-factor authentication disabled.');
    }
}
