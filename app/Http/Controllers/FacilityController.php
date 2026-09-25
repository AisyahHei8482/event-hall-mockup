<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function index(Request $request): View
    {
        $facilities = Facility::where('is_active', true)
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->string('type')))
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->string('q').'%'))
            ->orderBy('sort_order')
            ->paginate(9)
            ->withQueryString();

        return view('facilities.index', compact('facilities'));
    }

    public function show(Facility $facility): View
    {
        abort_if(! $facility->is_active, 404);

        $facility->load(['images', 'approvedReviews.user']);

        $related = Facility::where('is_active', true)
            ->where('id', '!=', $facility->id)
            ->where('type', $facility->type)
            ->take(3)
            ->get();

        return view('facilities.show', compact('facility', 'related'));
    }
}
