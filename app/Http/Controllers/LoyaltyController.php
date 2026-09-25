<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class LoyaltyController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $tiers = collect(\App\Models\User::TIERS)
            ->map(fn ($threshold, $name) => ['name' => $name, 'threshold' => $threshold])
            ->values();

        $nextTier = $tiers->first(fn ($tier) => $tier['threshold'] > $user->loyalty_points);

        return view('loyalty.index', compact('user', 'tiers', 'nextTier'));
    }
}
