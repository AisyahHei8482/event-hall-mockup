<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\SeasonalRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeasonalRateController extends Controller
{
    public function index(): View
    {
        $seasonalRates = SeasonalRate::with('facility')->orderBy('starts_on', 'desc')->paginate(15);

        return view('admin.seasonal-rates.index', compact('seasonalRates'));
    }

    public function create(): View
    {
        $facilities = Facility::orderBy('name')->get();

        return view('admin.seasonal-rates.create', compact('facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        SeasonalRate::create($this->validated($request));

        return redirect()->route('admin.seasonal-rates.index')->with('success', 'Seasonal rate created successfully.');
    }

    public function edit(SeasonalRate $seasonalRate): View
    {
        $facilities = Facility::orderBy('name')->get();

        return view('admin.seasonal-rates.edit', compact('seasonalRate', 'facilities'));
    }

    public function update(Request $request, SeasonalRate $seasonalRate): RedirectResponse
    {
        $seasonalRate->update($this->validated($request));

        return redirect()->route('admin.seasonal-rates.index')->with('success', 'Seasonal rate updated successfully.');
    }

    public function destroy(SeasonalRate $seasonalRate): RedirectResponse
    {
        $seasonalRate->delete();

        return back()->with('success', 'Seasonal rate deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'label' => ['required', 'string', 'max:255'],
            'starts_on' => ['required', 'date'],
            'ends_on' => ['required', 'date', 'after_or_equal:starts_on'],
            'price_override' => ['nullable', 'numeric', 'min:0'],
            'price_multiplier' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
