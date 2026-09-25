<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventHall;
use App\Models\EventSchedule;
use App\Models\EventTask;
use App\Models\StaffAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class BookingManagementController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with(['eventHall.franchise'])
            ->where('booking_type', 'event_hall')
            ->latest('check_in');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('franchise_id')) {
            $query->whereHas('eventHall', fn ($q) => $q->where('franchise_id', $request->franchise_id));
        }
        if ($request->filled('date_from')) {
            $query->where('check_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('check_in', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_number', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_name', 'like', '%'.$request->search.'%')
                    ->orWhere('guest_email', 'like', '%'.$request->search.'%');
            });
        }

        $bookings = $query->paginate(20)->withQueryString();

        return view('management.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking): View
    {
        $booking->load(['eventHall.franchise', 'quotation', 'addons', 'eventSchedules.tasks', 'staffAssignments.user']);
        $staff = \App\Models\User::whereIn('role', ['admin', 'staff'])->get();

        return view('management.bookings.show', compact('booking', 'staff'));
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'status'         => ['nullable', 'in:pending,confirmed,checked_in,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,refunded'],
            'deposit_paid'   => ['nullable', 'numeric', 'min:0'],
            'internal_notes' => ['nullable', 'string'],
            'check_in'       => ['nullable', 'date'],
            'start_time'     => ['nullable', 'date_format:H:i:s'],
            'end_time'       => ['nullable', 'date_format:H:i:s'],
        ]);

        // If rescheduling, check for conflicts
        if (isset($validated['check_in']) || isset($validated['start_time'])) {
            $testBooking = clone $booking;
            $testBooking->check_in   = $validated['check_in'] ?? $booking->check_in;
            $testBooking->start_time = $validated['start_time'] ?? $booking->start_time;
            $testBooking->end_time   = $validated['end_time'] ?? $booking->end_time;

            if ($testBooking->overlapsWithExistingBooking()) {
                return back()->withErrors(['error' => 'The selected time slot conflicts with an existing booking.']);
            }
        }

        $booking->update($validated);

        return back()->with('success', 'Booking updated.');
    }

    public function recordPayment(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'amount'         => ['required', 'numeric', 'min:0.01'],
            'payment_type'   => ['required', 'in:deposit,full'],
        ]);

        if ($validated['payment_type'] === 'deposit') {
            $booking->increment('deposit_paid', $validated['amount']);
        }

        $balance = $booking->total_price - ($booking->deposit_paid ?? 0);
        if ($balance <= 0) {
            $booking->update(['payment_status' => 'paid']);
        }

        return back()->with('success', 'Payment of RM'.number_format($validated['amount'], 2).' recorded.');
    }

    public function invoice(Booking $booking): Response
    {
        $booking->load(['eventHall.franchise', 'addons', 'quotation']);
        $pdf = Pdf::loadView('management.bookings.invoice', compact('booking'));

        return $pdf->download("invoice-{$booking->booking_number}.pdf");
    }

    // --- Event Schedule ---

    public function addSchedule(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'activity_type'  => ['required', 'string', 'max:100'],
            'title'          => ['required', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'scheduled_date' => ['required', 'date'],
            'start_time'     => ['required', 'date_format:H:i'],
            'end_time'       => ['required', 'date_format:H:i', 'after:start_time'],
            'notes'          => ['nullable', 'string'],
            'sort_order'     => ['nullable', 'integer'],
        ]);

        $validated['status']     = 'pending';
        $validated['booking_id'] = $booking->id;
        EventSchedule::create($validated);

        return back()->with('success', 'Schedule item added.');
    }

    public function updateSchedule(Request $request, Booking $booking, EventSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,assigned,in_progress,completed,cancelled'],
            'notes'  => ['nullable', 'string'],
        ]);

        $schedule->update($validated);

        return back()->with('success', 'Schedule updated.');
    }

    // --- Tasks ---

    public function addTask(Request $request, Booking $booking, EventSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'vendor'      => ['nullable', 'string', 'max:255'],
            'due_time'    => ['nullable', 'date_format:H:i'],
            'notes'       => ['nullable', 'string'],
        ]);

        $validated['status'] = $validated['assigned_to'] ? 'assigned' : 'pending';
        
        $schedule->tasks()->create($validated);

        return back()->with('success', 'Task added to schedule.');
    }

    public function updateTask(Request $request, Booking $booking, EventTask $task): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,assigned,in_progress,completed,cancelled'],
            'notes'  => ['nullable', 'string'],
        ]);

        $task->update($validated);

        return back()->with('success', 'Task updated.');
    }

    // --- Staff Assignments ---

    public function assignStaff(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'user_id'    => ['required', 'exists:users,id'],
            'role'       => ['required', 'string', 'max:100'],
            'date'       => ['required', 'date'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time'   => ['nullable', 'date_format:H:i', 'after:start_time'],
            'notes'      => ['nullable', 'string'],
        ]);

        $validated['status'] = 'pending';
        
        $booking->staffAssignments()->create($validated);

        return back()->with('success', 'Staff member assigned.');
    }

    public function updateStaff(Request $request, Booking $booking, StaffAssignment $assignment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
            'notes'  => ['nullable', 'string'],
        ]);

        $assignment->update($validated);

        return back()->with('success', 'Staff assignment updated.');
    }

    public function removeStaff(Request $request, Booking $booking, StaffAssignment $assignment): RedirectResponse
    {
        $assignment->delete();

        return back()->with('success', 'Staff assignment removed.');
    }

    // --- Calendar JSON ---

    public function calendarJson(Request $request): \Illuminate\Http\JsonResponse
    {
        $year  = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);
        $start = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = $start->copy()->endOfMonth();

        $query = Booking::with('eventHall.franchise')
            ->where('booking_type', 'event_hall')
            ->whereBetween('check_in', [$start, $end]);

        if ($request->filled('franchise_id')) {
            $query->whereHas('eventHall', fn ($q) => $q->where('franchise_id', $request->franchise_id));
        }
        if ($request->filled('hall_id')) {
            $query->where('event_hall_id', $request->hall_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->get()->map(fn ($b) => [
            'id'           => $b->id,
            'title'        => $b->guest_name.' — '.($b->eventHall->name ?? ''),
            'start'        => $b->check_in->format('Y-m-d').($b->start_time ? 'T'.$b->start_time : ''),
            'end'          => $b->check_in->format('Y-m-d').($b->end_time ? 'T'.$b->end_time : ''),
            'status'       => $b->status,
            'booking_number'=> $b->booking_number,
            'color'        => match ($b->status) {
                'confirmed'  => '#16a34a',
                'pending'    => '#d97706',
                'cancelled'  => '#dc2626',
                'completed'  => '#6b7280',
                default      => '#2563eb',
            },
            'url'          => route('management.bookings.show', $b),
        ]);

        return response()->json($bookings);
    }
}
