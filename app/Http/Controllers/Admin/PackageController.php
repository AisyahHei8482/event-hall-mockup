<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::latest()->paginate(15);

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();

        return view('admin.packages.create', compact('facilities'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('packages', 'public');
        }

        $validated['slug'] = Str::slug($validated['title']).'-'.Str::random(6);

        $package = Package::create($validated);
        $package->facilities()->sync($request->input('facility_ids', []));

        return redirect()->route('admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package): View
    {
        $facilities = Facility::where('is_active', true)->orderBy('name')->get();
        $package->load('facilities');

        return view('admin.packages.edit', compact('package', 'facilities'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            if ($package->cover_image) {
                Storage::disk('public')->delete($package->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('packages', 'public');
        }

        $package->update($validated);
        $package->facilities()->sync($request->input('facility_ids', []));

        return redirect()->route('admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package): RedirectResponse
    {
        if ($package->cover_image) {
            Storage::disk('public')->delete($package->cover_image);
        }

        $package->delete();

        return back()->with('success', 'Package deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'original_price' => ['nullable', 'numeric', 'min:0'],
            'badge' => ['nullable', 'string', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['cover_image']);

        return $validated;
    }
}
