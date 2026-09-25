<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
use App\Models\Inquiry;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'revenue' => Booking::where('payment_status', 'paid')->sum('total_price'),
            'facilities' => Facility::count(),
            'new_inquiries' => Inquiry::where('status', 'new')->count(),
        ];

        $upcomingBookings = Booking::with('facility')
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('check_in', '>=', now()->subDay())
            ->orderBy('check_in')
            ->take(10)
            ->get();

        $recentInquiries = Inquiry::latest()->take(5)->get();

        $monthlyRevenue = Booking::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(5)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_price) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        $revenueLabels = [];
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $revenueLabels[] = now()->subMonths($i)->format('M Y');
            $revenueData[] = (float) ($monthlyRevenue[$key] ?? 0);
        }

        return view('admin.dashboard', compact('stats', 'upcomingBookings', 'recentInquiries', 'revenueLabels', 'revenueData'));
    }
}
