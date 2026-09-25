<?php

namespace App\Http\Controllers;

use App\Models\EventHall;
use App\Models\Franchise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventHallController extends Controller
{
    public function index(Request $request): View
    {
        $franchises = Franchise::where('status', 'active')
            ->with('activeHalls')
            ->get();

        $halls = EventHall::with('franchise')
            ->where('is_active', true)
            ->when($request->filled('franchise'), fn ($q) => $q->where('franchise_id', $request->franchise))
            ->when($request->filled('type'), fn ($q) => $q->where('hall_type', $request->type))
            ->when($request->filled('capacity'), fn ($q) => $q->where('capacity', '>=', $request->capacity))
            ->orderBy('sort_order')
            ->paginate(12);

        $hallTypes = EventHall::where('is_active', true)
            ->whereNotNull('hall_type')
            ->distinct()
            ->pluck('hall_type');

        return view('event-halls.index', compact('halls', 'franchises', 'hallTypes'));
    }

    public function show(EventHall $hall): View
    {
        abort_unless($hall->is_active, 404);
        $hall->load(['franchise', 'images', 'pricingRules' => fn ($q) => $q->active(), 'addons']);

        return view('event-halls.show', compact('hall'));
    }

    public function availability(EventHall $hall, Request $request): JsonResponse
    {
        $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date = \Carbon\Carbon::parse($request->date);

        if ($hall->isBlockedOut($date)) {
            return response()->json(['available' => false, 'reason' => 'blocked', 'slots' => []]);
        }

        $slots = $hall->availableSlotsForDate($date);

        return response()->json([
            'available' => $slots->isNotEmpty(),
            'slots'     => $slots->map(fn ($slot) => [
                'id'           => $slot->id,
                'label'        => $slot->label ?? $slot->start_time.' – '.$slot->end_time,
                'start_time'   => $slot->start_time,
                'end_time'     => $slot->end_time,
                'duration_hrs' => $slot->duration_hours,
            ]),
        ]);
    }

    public function packages(EventHall $hall): View
    {
        $hall->load(['pricingRules' => fn ($q) => $q->active(), 'addons']);

        return view('event-halls.packages', compact('hall'));
    }
}
