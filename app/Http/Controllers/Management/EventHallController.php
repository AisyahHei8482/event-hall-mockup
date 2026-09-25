<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Addon;
use App\Models\EventHall;
use App\Models\Franchise;
use App\Models\HallBlockout;
use App\Models\HallPricingRule;
use App\Models\HallTimeSlot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventHallController extends Controller
{
    public function index(): View
    {
        $halls = EventHall::with('franchise')
            ->withCount('bookings')
            ->latest()
            ->paginate(15);

        return view('management.halls.index', compact('halls'));
    }

    public function create(): View
    {
        $franchises = Franchise::where('status', 'active')->get();

        return view('management.halls.create', compact('franchises'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'franchise_id'       => ['nullable', 'exists:franchises,id'],
            'name'               => ['required', 'string', 'max:255'],
            'code'               => ['required', 'string', 'max:30', 'unique:event_halls,code'],
            'description'        => ['nullable', 'string'],
            'capacity'           => ['required', 'integer', 'min:1'],
            'floor_area'         => ['nullable', 'numeric', 'min:0'],
            'hall_type'          => ['nullable', 'string', 'max:100'],
            'cover_image'        => ['nullable', 'image', 'max:4096'],
            'min_booking_hours'  => ['required', 'integer', 'min:1'],
            'max_booking_hours'  => ['nullable', 'integer', 'min:1'],
            'buffer_before'      => ['nullable', 'integer', 'min:0'],
            'buffer_after'       => ['nullable', 'integer', 'min:0'],
            'is_active'          => ['boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('halls', 'public');
        }

        $validated['facilities'] = is_string($request->input('facilities')) 
            ? array_map('trim', explode(',', $request->input('facilities'))) 
            : $request->input('facilities', []);
            
        $validated['amenities'] = is_string($request->input('amenities')) 
            ? array_map('trim', explode(',', $request->input('amenities'))) 
            : $request->input('amenities', []);

        EventHall::create($validated);

        return redirect()->route('management.halls.index')
            ->with('success', 'Event hall created.');
    }

    public function show(EventHall $hall): View
    {
        $hall->load(['franchise', 'images', 'pricingRules', 'timeSlots', 'blockouts', 'addons']);

        $upcomingBookings = $hall->bookings()
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '>=', today())
            ->orderBy('check_in')
            ->orderBy('start_time')
            ->take(20)
            ->get();

        return view('management.halls.show', compact('hall', 'upcomingBookings'));
    }

    public function edit(EventHall $hall): View
    {
        $franchises = Franchise::where('status', 'active')->get();

        return view('management.halls.edit', compact('hall', 'franchises'));
    }

    public function update(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'franchise_id'       => ['nullable', 'exists:franchises,id'],
            'name'               => ['required', 'string', 'max:255'],
            'code'               => ['required', 'string', 'max:30', 'unique:event_halls,code,'.$hall->id],
            'description'        => ['nullable', 'string'],
            'capacity'           => ['required', 'integer', 'min:1'],
            'floor_area'         => ['nullable', 'numeric', 'min:0'],
            'hall_type'          => ['nullable', 'string', 'max:100'],
            'cover_image'        => ['nullable', 'image', 'max:4096'],
            'min_booking_hours'  => ['required', 'integer', 'min:1'],
            'max_booking_hours'  => ['nullable', 'integer', 'min:1'],
            'buffer_before'      => ['nullable', 'integer', 'min:0'],
            'buffer_after'       => ['nullable', 'integer', 'min:0'],
            'is_active'          => ['boolean'],
        ]);

        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('halls', 'public');
        }

        $validated['facilities'] = is_string($request->input('facilities')) 
            ? array_map('trim', explode(',', $request->input('facilities'))) 
            : $request->input('facilities', []);
            
        $validated['amenities'] = is_string($request->input('amenities')) 
            ? array_map('trim', explode(',', $request->input('amenities'))) 
            : $request->input('amenities', []);

        $hall->update($validated);

        return redirect()->route('management.halls.show', $hall)
            ->with('success', 'Hall updated.');
    }

    public function destroy(EventHall $hall): RedirectResponse
    {
        $hall->delete();

        return redirect()->route('management.halls.index')
            ->with('success', 'Hall deleted.');
    }

    // --- Pricing Rules ---

    public function storePricingRule(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:100'],
            'rate_type'      => ['required', 'in:hourly,half_day,full_day,custom'],
            'day_type'       => ['required', 'in:all,weekday,weekend,public_holiday'],
            'price'          => ['required', 'numeric', 'min:0'],
            'half_day_hours' => ['nullable', 'numeric', 'min:0.5'],
            'full_day_hours' => ['nullable', 'numeric', 'min:0.5'],
            'season_start'   => ['nullable', 'date'],
            'season_end'     => ['nullable', 'date', 'after_or_equal:season_start'],
            'priority'       => ['nullable', 'integer', 'min:0'],
            'is_active'      => ['boolean'],
        ]);

        $hall->pricingRules()->create($validated);

        return back()->with('success', 'Pricing rule added.');
    }

    public function updatePricingRule(Request $request, EventHall $hall, HallPricingRule $rule): RedirectResponse
    {
        $validated = $request->validate([
            'label'          => ['required', 'string', 'max:100'],
            'rate_type'      => ['required', 'in:hourly,half_day,full_day,custom'],
            'day_type'       => ['required', 'in:all,weekday,weekend,public_holiday'],
            'price'          => ['required', 'numeric', 'min:0'],
            'half_day_hours' => ['nullable', 'numeric', 'min:0.5'],
            'full_day_hours' => ['nullable', 'numeric', 'min:0.5'],
            'season_start'   => ['nullable', 'date'],
            'season_end'     => ['nullable', 'date', 'after_or_equal:season_start'],
            'is_active'      => ['boolean'],
        ]);

        $rule->update($validated);

        return back()->with('success', 'Pricing rule updated.');
    }

    public function destroyPricingRule(EventHall $hall, HallPricingRule $rule): RedirectResponse
    {
        $rule->delete();

        return back()->with('success', 'Pricing rule removed.');
    }

    // --- Time Slots ---

    public function storeTimeSlot(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'label'         => ['nullable', 'string', 'max:100'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i', 'after:start_time'],
            'days_of_week'  => ['nullable', 'array'],
            'days_of_week.*'=> ['integer', 'min:1', 'max:7'],
            'specific_date' => ['nullable', 'date'],
            'is_active'     => ['boolean'],
        ]);

        $hall->timeSlots()->create($validated);

        return back()->with('success', 'Time slot added.');
    }

    public function updateTimeSlot(Request $request, EventHall $hall, HallTimeSlot $slot): RedirectResponse
    {
        $validated = $request->validate([
            'label'         => ['nullable', 'string', 'max:100'],
            'start_time'    => ['required', 'date_format:H:i'],
            'end_time'      => ['required', 'date_format:H:i'],
            'days_of_week'  => ['nullable', 'array'],
            'days_of_week.*'=> ['integer', 'min:1', 'max:7'],
            'specific_date' => ['nullable', 'date'],
            'is_active'     => ['boolean'],
        ]);

        $slot->update($validated);

        return back()->with('success', 'Time slot updated.');
    }

    public function destroyTimeSlot(EventHall $hall, HallTimeSlot $slot): RedirectResponse
    {
        $slot->delete();

        return back()->with('success', 'Time slot removed.');
    }

    // --- Blockouts ---

    public function storeBlockout(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'reason'        => ['nullable', 'string', 'max:255'],
            'blockout_type' => ['required', 'in:full_day,time_range,maintenance'],
            'date_from'     => ['required', 'date'],
            'date_to'       => ['required', 'date', 'after_or_equal:date_from'],
            'time_from'     => ['nullable', 'date_format:H:i'],
            'time_to'       => ['nullable', 'date_format:H:i', 'after:time_from'],
        ]);

        $hall->blockouts()->create($validated);

        return back()->with('success', 'Blockout period added.');
    }

    public function updateBlockout(Request $request, EventHall $hall, HallBlockout $blockout): RedirectResponse
    {
        $validated = $request->validate([
            'reason'        => ['nullable', 'string', 'max:255'],
            'blockout_type' => ['required', 'in:full_day,time_range,maintenance'],
            'date_from'     => ['required', 'date'],
            'date_to'       => ['required', 'date', 'after_or_equal:date_from'],
            'time_from'     => ['nullable', 'date_format:H:i'],
            'time_to'       => ['nullable', 'date_format:H:i'],
        ]);

        $blockout->update($validated);

        return back()->with('success', 'Blockout updated.');
    }

    public function destroyBlockout(EventHall $hall, HallBlockout $blockout): RedirectResponse
    {
        $blockout->delete();

        return back()->with('success', 'Blockout removed.');
    }

    // --- Addons ---

    public function storeAddon(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category'       => ['nullable', 'string', 'max:100'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'unit'           => ['nullable', 'string', 'max:50'],
            'is_quantifiable'=> ['boolean'],
            'max_quantity'   => ['nullable', 'integer', 'min:1'],
            'is_active'      => ['boolean'],
        ]);

        $validated['event_hall_id'] = $hall->id;
        Addon::create($validated);

        return back()->with('success', 'Add-on created.');
    }

    public function updateAddon(Request $request, EventHall $hall, Addon $addon): RedirectResponse
    {
        $validated = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'category'       => ['nullable', 'string', 'max:100'],
            'description'    => ['nullable', 'string'],
            'price'          => ['required', 'numeric', 'min:0'],
            'unit'           => ['nullable', 'string', 'max:50'],
            'is_active'      => ['boolean'],
        ]);

        $addon->update($validated);

        return back()->with('success', 'Add-on updated.');
    }

    public function destroyAddon(EventHall $hall, Addon $addon): RedirectResponse
    {
        $addon->delete();

        return back()->with('success', 'Add-on removed.');
    }

    // --- Amenities & Facilities ---

    public function updateAmenities(Request $request, EventHall $hall): RedirectResponse
    {
        $validated = $request->validate([
            'amenities'  => ['nullable', 'string'],
            'facilities' => ['nullable', 'string'],
        ]);

        $hall->update([
            'amenities'  => $validated['amenities']
                ? array_filter(array_map('trim', explode(',', $validated['amenities'])))
                : [],
            'facilities' => $validated['facilities']
                ? array_filter(array_map('trim', explode(',', $validated['facilities'])))
                : [],
        ]);

        return back()->with('success', 'Amenities & facilities updated.');
    }

    // --- Calendar JSON ---


    public function calendar(EventHall $hall, Request $request): JsonResponse
    {
        $year  = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $start = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $bookings = $hall->bookings()
            ->whereNotIn('status', ['cancelled'])
            ->whereBetween('check_in', [$start, $end])
            ->get(['id', 'check_in', 'start_time', 'end_time', 'status', 'guests', 'guest_name']);

        $blockouts = $hall->blockouts()
            ->where('date_from', '<=', $end)
            ->where('date_to', '>=', $start)
            ->get();

        return response()->json([
            'bookings' => $bookings,
            'blockouts' => $blockouts,
        ]);
    }
}
