<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FranchiseController extends Controller
{
    public function index(): View
    {
        $franchises = Franchise::withCount(['eventHalls', 'bookings'])
            ->latest()
            ->paginate(15);

        return view('management.franchises.index', compact('franchises'));
    }

    public function create(): View
    {
        return view('management.franchises.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['required', 'string', 'max:20', 'unique:franchises,code'],
            'company_name'   => ['nullable', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:1000'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:255'],
            'logo'           => ['nullable', 'image', 'max:2048'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('franchises', 'public');
        }

        Franchise::create($validated);

        return redirect()->route('management.franchises.index')
            ->with('success', 'Franchise created successfully.');
    }

    public function show(Franchise $franchise): View
    {
        $franchise->load(['eventHalls', 'users']);

        $stats = [
            'total_bookings'  => $franchise->bookings()->count(),
            'total_revenue'   => $franchise->bookings()->where('payment_status', 'paid')->sum('total_price'),
            'total_quotes'    => $franchise->quotations()->count(),
            'active_halls'    => $franchise->activeHalls()->count(),
        ];

        $recentBookings = $franchise->bookings()
            ->with('eventHall')
            ->latest('check_in')
            ->take(10)
            ->get();

        return view('management.franchises.show', compact('franchise', 'stats', 'recentBookings'));
    }

    public function edit(Franchise $franchise): View
    {
        return view('management.franchises.edit', compact('franchise'));
    }

    public function update(Request $request, Franchise $franchise): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'code'           => ['required', 'string', 'max:20', 'unique:franchises,code,'.$franchise->id],
            'company_name'   => ['nullable', 'string', 'max:255'],
            'address'        => ['nullable', 'string', 'max:1000'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'email'          => ['nullable', 'email', 'max:255'],
            'logo'           => ['nullable', 'image', 'max:2048'],
            'status'         => ['required', 'in:active,inactive'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('franchises', 'public');
        }

        $franchise->update($validated);

        return redirect()->route('management.franchises.index')
            ->with('success', 'Franchise updated.');
    }

    public function destroy(Franchise $franchise): RedirectResponse
    {
        $franchise->forceDelete();

        return redirect()->route('management.franchises.index')
            ->with('success', 'Franchise deleted.');
    }
}
