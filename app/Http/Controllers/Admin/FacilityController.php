<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::orderBy('sort_order')->paginate(15);

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create(): View
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('facilities', 'public');
        }

        $validated['slug'] = Str::slug($validated['name']).'-'.Str::random(6);

        $facility = Facility::create($validated);

        ActivityLog::record('facility.created', $facility, "Created facility {$facility->name}");

        return redirect()->route('admin.facilities.index')->with('success', 'Facility created successfully.');
    }

    public function edit(Facility $facility): View
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('cover_image')) {
            if ($facility->cover_image) {
                Storage::disk('public')->delete($facility->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('facilities', 'public');
        }

        $facility->update($validated);

        ActivityLog::record('facility.updated', $facility, "Updated facility {$facility->name}");

        return redirect()->route('admin.facilities.index')->with('success', 'Facility updated successfully.');
    }

    public function destroy(Facility $facility): RedirectResponse
    {
        if ($facility->cover_image) {
            Storage::disk('public')->delete($facility->cover_image);
        }

        ActivityLog::record('facility.deleted', null, "Deleted facility {$facility->name}");

        $facility->delete();

        return back()->with('success', 'Facility deleted.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:facility,accommodation,activity,dining,experience'],
            'accommodation_type' => ['nullable', 'required_if:type,accommodation', 'in:hotel-rooms,bungalows,trainer-rooms,dormitories,dallas-suites-hostel'],
            'experience_type' => ['nullable', 'required_if:type,experience', 'in:leisure,wedding,meeting-and-event,dining'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'price_unit' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'cover_image' => ['nullable', 'image', 'max:4096'],
            'amenities' => ['nullable', 'string'],
            'virtual_tour_url' => ['nullable', 'url', 'max:500'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['amenities'] = $validated['amenities'] ?? null
            ? array_map('trim', explode(',', $validated['amenities']))
            : [];

        unset($validated['cover_image']);

        return $validated;
    }
}
