<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Package;
use App\Models\Promotion;
use Illuminate\View\View;

class ExclusiveOfferController extends Controller
{
    public function index(): View
    {
        $offers = Promotion::where('is_active', true)
            ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->latest()
            ->get();

        $packages = Package::where('is_active', true)->latest()->get();

        $accommodations = Facility::where('is_active', true)
            ->where('type', 'accommodation')
            ->orderBy('sort_order')
            ->get();

        return view('exclusive-offers.index', compact('offers', 'packages', 'accommodations'));
    }
}
