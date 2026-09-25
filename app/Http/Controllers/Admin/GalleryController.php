<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $images = GalleryImage::orderBy('sort_order')->latest()->paginate(20);

        return view('admin.gallery.index', compact('images'));
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['image_path'] = $request->file('image')->store('gallery', 'public');

        GalleryImage::create($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Image uploaded successfully.');
    }

    public function edit(GalleryImage $gallery): View
    {
        return view('admin.gallery.edit', ['image' => $gallery]);
    }

    public function update(Request $request, GalleryImage $gallery): RedirectResponse
    {
        $validated = $this->validated($request, forFile: false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($gallery->image_path);
            $validated['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($validated);

        return redirect()->route('admin.gallery.index')->with('success', 'Image updated successfully.');
    }

    public function destroy(GalleryImage $gallery): RedirectResponse
    {
        Storage::disk('public')->delete($gallery->image_path);
        $gallery->delete();

        return back()->with('success', 'Image deleted.');
    }

    private function validated(Request $request, bool $forFile = true): array
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'in:leisure,wedding,event,dining'],
            'description' => ['nullable', 'string', 'max:255'],
            'image' => [$forFile ? 'required' : 'nullable', 'image', 'max:4096'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        unset($validated['image']);

        return $validated;
    }
}
