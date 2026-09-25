<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Facility $facility): RedirectResponse
    {
        $validated = $request->validate([
            'guest_name' => [auth()->check() ? 'nullable' : 'required', 'string', 'max:255'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        $facility->reviews()->create([
            'user_id' => auth()->id(),
            'guest_name' => auth()->user()->name ?? $validated['guest_name'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'is_approved' => false,
        ]);

        return back()->with('success', 'Thank you for your review! It will appear once approved.');
    }
}
