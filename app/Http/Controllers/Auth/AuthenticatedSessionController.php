<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        $user = $request->user();

        if ($user->hasEnabledTwoFactorAuthentication()) {
            Auth::logout();

            $request->session()->put('login.2fa.user_id', $user->id);
            $request->session()->put('login.2fa.remember', $request->boolean('remember'));

            return redirect()->route('two-factor.login');
        }

        $request->session()->regenerate();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        } elseif (in_array($user->role, ['staff', 'manager'])) {
            return redirect()->intended(route('management.dashboard'));
        }

        return redirect()->intended(\Illuminate\Support\Facades\Route::has('customer.dashboard') ? route('customer.dashboard') : route('home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
