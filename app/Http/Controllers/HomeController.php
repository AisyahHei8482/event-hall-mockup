<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function setLocale(string $locale): RedirectResponse
    {
        if (in_array($locale, ['en', 'ms'], true)) {
            session(['locale' => $locale]);
        }

        return back();
    }

    public function index(): View
    {
        $featuredFacilities = Facility::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $promotions = Promotion::where('is_active', true)
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->take(3)
            ->get();

        $accommodationTypes = AccommodationController::TYPES;
        $experienceTypes = ExperienceController::TYPES;

        return view('home', compact('featuredFacilities', 'promotions', 'accommodationTypes', 'experienceTypes'));
    }
}
