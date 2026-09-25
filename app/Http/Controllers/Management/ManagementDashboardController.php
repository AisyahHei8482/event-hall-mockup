<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventHall;
use App\Models\Franchise;
use App\Models\Quotation;
use Illuminate\View\View;

class ManagementDashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_bookings'       => Booking::where('booking_type', 'event_hall')->count(),
            'today_bookings'       => Booking::where('booking_type', 'event_hall')->whereDate('check_in', today())->count(),
            'upcoming_events'      => Booking::where('booking_type', 'event_hall')
                ->whereIn('status', ['pending', 'confirmed'])
                ->where('check_in', '>=', today())
                ->count(),
            'pending_quotations'   => Quotation::where('status', 'generated')->count(),
            'confirmed_quotations' => Quotation::whereIn('status', ['accepted', 'converted'])->count(),
            'event_hall_revenue'   => Booking::where('booking_type', 'event_hall')
                ->where('payment_status', 'paid')
                ->sum('total_price'),
            'outstanding_payments' => Booking::where('booking_type', 'event_hall')
                ->where('payment_status', 'unpaid')
                ->whereIn('status', ['confirmed', 'pending'])
                ->sum('total_price'),
            'available_halls'      => EventHall::where('is_active', true)->count(),
            'active_franchises'    => Franchise::where('status', 'active')->count(),
            'cancellations'        => Booking::where('booking_type', 'event_hall')
                ->where('status', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        $upcomingEvents = Booking::with(['eventHall.franchise'])
            ->where('booking_type', 'event_hall')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '>=', today())
            ->orderBy('check_in')
            ->orderBy('start_time')
            ->take(10)
            ->get();

        $recentQuotations = Quotation::with(['eventHall.franchise'])
            ->latest()
            ->take(8)
            ->get();

        $monthlyRevenue = Booking::where('booking_type', 'event_hall')
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $revenueLabels = [];
        $revenueData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $key             = now()->subMonths($i)->format('Y-m');
            $revenueLabels[] = now()->subMonths($i)->format('M Y');
            $revenueData[]   = (float) ($monthlyRevenue[$key] ?? 0);
        }

        $franchises = Franchise::withCount(['eventHalls', 'bookings'])->get();

        return view('management.dashboard', compact(
            'stats', 'upcomingEvents', 'recentQuotations',
            'revenueLabels', 'revenueData', 'franchises'
        ));
    }
}
