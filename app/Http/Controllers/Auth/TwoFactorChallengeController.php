<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorChallengeController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login.2fa.user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function store(Request $request): RedirectResponse
    {
        $userId = $request->session()->get('login.2fa.user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'code' => ['nullable', 'string'],
            'recovery_code' => ['nullable', 'string'],
        ]);

        $verified = false;

        if ($request->filled('code')) {
            $google2fa = new Google2FA;
            $verified = $google2fa->verifyKey(decrypt($user->two_factor_secret), $request->string('code'));
        } elseif ($request->filled('recovery_code')) {
            $codes = json_decode(decrypt($user->two_factor_recovery_codes), true) ?? [];
            if (in_array($request->string('recovery_code')->value(), $codes, true)) {
                $verified = true;
                $codes = array_values(array_diff($codes, [$request->string('recovery_code')->value()]));
                $user->forceFill(['two_factor_recovery_codes' => encrypt(json_encode($codes))])->save();
            }
        }

        if (! $verified) {
            throw ValidationException::withMessages([
                'code' => 'The provided code was invalid.',
            ]);
        }

        $remember = $request->session()->get('login.2fa.remember', false);
        $request->session()->forget(['login.2fa.user_id', 'login.2fa.remember']);

        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }
}
