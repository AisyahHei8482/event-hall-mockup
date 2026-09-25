<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Customer's bookings (both resort and event hall)
        $bookings = $user->bookings()
            ->with(['eventHall', 'facility'])
            ->latest('check_in')
            ->take(5)
            ->get();

        // Customer's quotations
        $quotations = \App\Models\Quotation::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('bookings', 'quotations'));
    }
}
