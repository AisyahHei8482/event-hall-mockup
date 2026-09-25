<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public const KEYS = [
        'resort_email', 'resort_phone', 'resort_address',
        'facebook_url', 'instagram_url', 'whatsapp_number',
        'maintenance_mode',
    ];

    public function edit(): View
    {
        $settings = collect(self::KEYS)->mapWithKeys(fn ($key) => [$key => Setting::get($key)]);

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'resort_email' => ['nullable', 'email', 'max:255'],
            'resort_phone' => ['nullable', 'string', 'max:30'],
            'resort_address' => ['nullable', 'string', 'max:500'],
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'whatsapp_number' => ['nullable', 'string', 'max:30'],
            'maintenance_mode' => ['nullable', 'boolean'],
        ]);

        $validated['maintenance_mode'] = $request->boolean('maintenance_mode') ? '1' : '0';

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        ActivityLog::record('settings.updated', null, 'Updated site settings');

        return back()->with('success', 'Settings updated successfully.');
    }
}
