<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AddonController extends Controller
{
    public function index(): View
    {
        $addons = Addon::with('facility')->orderBy('name')->paginate(15);

        return view('admin.addons.index', compact('addons'));
    }

    public function create(): View
    {
        $facilities = Facility::orderBy('name')->get();

        return view('admin.addons.create', compact('facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        Addon::create($this->validated($request));

        return redirect()->route('admin.addons.index')->with('success', 'Add-on created successfully.');
    }

    public function edit(Addon $addon): View
    {
        $facilities = Facility::orderBy('name')->get();

        return view('admin.addons.edit', compact('addon', 'facilities'));
    }

    public function update(Request $request, Addon $addon): RedirectResponse
    {
        $addon->update($this->validated($request));

        return redirect()->route('admin.addons.index')->with('success', 'Add-on updated successfully.');
    }

    public function destroy(Addon $addon): RedirectResponse
    {
        $addon->delete();

        return back()->with('success', 'Add-on deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'facility_id' => ['nullable', 'exists:facilities,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
