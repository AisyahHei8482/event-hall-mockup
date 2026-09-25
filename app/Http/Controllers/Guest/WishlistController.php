<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $facilities = auth()->user()->wishlistedFacilities()->paginate(9);

        return view('guest.wishlist.index', compact('facilities'));
    }

    public function store(Facility $facility): RedirectResponse
    {
        auth()->user()->wishlists()->firstOrCreate(['facility_id' => $facility->id]);

        return back()->with('success', 'Added to your wishlist.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        auth()->user()->wishlists()->where('facility_id', $facility->id)->delete();

        return back()->with('success', 'Removed from your wishlist.');
    }
}
