<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public const TYPES = [
        'leisure' => ['label' => 'Leisure', 'tagline' => 'Truly Exhilarating'],
        'wedding' => ['label' => 'Wedding', 'tagline' => 'Truly Inspiring'],
        'meeting-and-event' => ['label' => 'Meetings & Event', 'tagline' => 'Truly Memorable'],
        'dining' => ['label' => 'Dining', 'tagline' => 'Truly Enjoyable'],
    ];

    public function index(): View
    {
        $experiences = Facility::where('is_active', true)
            ->where('type', 'experience')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('experience_type');

        return view('experience.index', ['types' => self::TYPES, 'experiences' => $experiences]);
    }

    public function show(string $type): View
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        $facilities = Facility::where('is_active', true)
            ->where('type', 'experience')
            ->where('experience_type', $type)
            ->orderBy('sort_order')
            ->paginate(9);

        return view('experience.show', [
            'type' => $type,
            'meta' => self::TYPES[$type],
            'facilities' => $facilities,
        ]);
    }
}
