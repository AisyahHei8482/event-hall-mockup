<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Franchise;
use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('management.reports.index');
    }

    public function bookings(Request $request): View
    {
        $query = Booking::with(['eventHall.franchise'])
            ->where('booking_type', 'event_hall');

        $this->applyFilters($query, $request);

        $bookings = $query->latest('check_in')->paginate(50)->withQueryString();

        $summary = [
            'total'        => $bookings->total(),
            'confirmed'    => $query->clone()->where('status', 'confirmed')->count(),
            'cancelled'    => $query->clone()->where('status', 'cancelled')->count(),
            'revenue'      => $query->clone()->where('payment_status', 'paid')->sum('total_price'),
            'outstanding'  => $query->clone()->where('payment_status', 'unpaid')->sum('total_price'),
        ];

        $byStatus = Booking::where('booking_type', 'event_hall')
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $franchises = Franchise::all();

        return view('management.reports.bookings', compact('bookings', 'summary', 'byStatus', 'franchises'));
    }

    public function revenue(Request $request): View
    {
        $fromDate = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $toDate   = $request->get('to', now()->format('Y-m-d'));

        $byFranchise = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'paid')
            ->whereBetween('check_in', [$fromDate, $toDate])
            ->with('eventHall.franchise')
            ->get()
            ->groupBy('eventHall.franchise.name')
            ->map(fn ($b) => $b->sum('total_price'));

        $byHall = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'paid')
            ->whereBetween('check_in', [$fromDate, $toDate])
            ->selectRaw('event_hall_id, SUM(total_price) as revenue, COUNT(*) as bookings')
            ->groupBy('event_hall_id')
            ->with('eventHall')
            ->get();

        $byEventType = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'paid')
            ->whereBetween('check_in', [$fromDate, $toDate])
            ->selectRaw('event_type, SUM(total_price) as revenue, COUNT(*) as bookings')
            ->groupBy('event_type')
            ->get();

        $byMonth = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'paid')
            ->where('check_in', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(check_in, '%Y-%m') as month, SUM(total_price) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $outstanding = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'unpaid')
            ->whereIn('status', ['pending', 'confirmed'])
            ->sum('total_price');

        return view('management.reports.revenue', compact(
            'byFranchise', 'byHall', 'byEventType', 'byMonth', 'outstanding', 'fromDate', 'toDate'
        ));
    }

    public function quotations(Request $request): View
    {
        $fromDate = $request->get('from', now()->startOfMonth()->format('Y-m-d'));
        $toDate   = $request->get('to', now()->format('Y-m-d'));

        $stats = [
            'generated'   => Quotation::whereBetween('created_at', [$fromDate, $toDate])->count(),
            'accepted'    => Quotation::where('status', 'accepted')->whereBetween('created_at', [$fromDate, $toDate])->count(),
            'rejected'    => Quotation::where('status', 'rejected')->whereBetween('created_at', [$fromDate, $toDate])->count(),
            'expired'     => Quotation::where('status', 'expired')->whereBetween('created_at', [$fromDate, $toDate])->count(),
            'converted'   => Quotation::where('status', 'converted')->whereBetween('created_at', [$fromDate, $toDate])->count(),
        ];

        $total     = $stats['generated'];
        $converted = $stats['converted'];
        $stats['conversion_rate'] = $total > 0 ? round(($converted / $total) * 100, 1) : 0;

        $recent = Quotation::with(['eventHall.franchise'])
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->latest()
            ->paginate(30);

        return view('management.reports.quotations', compact('stats', 'recent', 'fromDate', 'toDate'));
    }

    public function operations(Request $request): View
    {
        $date = $request->get('date', today()->format('Y-m-d'));

        $upcomingEvents = Booking::with(['eventHall.franchise', 'eventSchedules'])
            ->where('booking_type', 'event_hall')
            ->whereIn('status', ['confirmed', 'pending'])
            ->where('check_in', '>=', $date)
            ->orderBy('check_in')
            ->orderBy('start_time')
            ->take(50)
            ->get();

        return view('management.reports.operations', compact('upcomingEvents', 'date'));
    }

    public function exportBookings(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $bookings = Booking::with(['eventHall.franchise'])
            ->where('booking_type', 'event_hall');

        $this->applyFilters($bookings, $request);
        $data = $bookings->get();

        return response()->streamDownload(function () use ($data) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Booking #', 'Guest', 'Email', 'Hall', 'Franchise', 'Date', 'Time', 'Guests', 'Status', 'Payment', 'Total']);
            foreach ($data as $b) {
                fputcsv($handle, [
                    $b->booking_number,
                    $b->guest_name,
                    $b->guest_email,
                    $b->eventHall->name ?? '',
                    $b->eventHall->franchise->name ?? '',
                    $b->check_in->format('Y-m-d'),
                    $b->start_time.' – '.$b->end_time,
                    $b->guests,
                    $b->status,
                    $b->payment_status,
                    $b->total_price,
                ]);
            }
            fclose($handle);
        }, 'bookings-export-'.now()->format('Ymd').'.csv');
    }

    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('franchise_id')) {
            $query->whereHas('eventHall', fn ($q) => $q->where('franchise_id', $request->franchise_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('from')) {
            $query->where('check_in', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('check_in', '<=', $request->to);
        }
    }
}
