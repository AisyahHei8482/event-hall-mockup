<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->value() ?: null;

        $images = GalleryImage::where('is_active', true)
            ->when($category, fn ($q) => $q->where('category', $category))
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $categories = ['leisure' => 'Leisure', 'wedding' => 'Wedding', 'event' => 'Event', 'dining' => 'Dining'];

        return view('gallery.index', compact('images', 'categories', 'category'));
    }
}
