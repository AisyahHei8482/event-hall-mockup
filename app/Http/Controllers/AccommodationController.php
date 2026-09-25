<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\View\View;

class AccommodationController extends Controller
{
    public const TYPES = [
        'hotel-rooms' => 'Hotel Rooms',
        'bungalows' => 'Bungalows',
        'trainer-rooms' => 'Trainer Rooms',
        'dormitories' => 'Dormitories',
        'dallas-suites-hostel' => 'Dallas Suites Hostel',
    ];

    public function index(): View
    {
        $accommodations = Facility::where('is_active', true)
            ->where('type', 'accommodation')
            ->orderBy('sort_order')
            ->get()
            ->groupBy('accommodation_type');

        return view('accommodation.index', ['types' => self::TYPES, 'accommodations' => $accommodations]);
    }

    public function show(string $type): View
    {
        abort_unless(array_key_exists($type, self::TYPES), 404);

        $facilities = Facility::where('is_active', true)
            ->where('type', 'accommodation')
            ->where('accommodation_type', $type)
            ->orderBy('sort_order')
            ->paginate(9);

        return view('accommodation.show', [
            'type' => $type,
            'label' => self::TYPES[$type],
            'facilities' => $facilities,
        ]);
    }
}
