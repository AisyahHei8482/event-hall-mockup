<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        [$from, $to] = $this->resolveRange($request);

        $bookings = Booking::with('facility')
            ->whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        $totalRevenue = $bookings->sum('total_price');
        $totalDiscount = $bookings->sum('discount_amount');
        $totalAddons = $bookings->sum('addons_total');
        $bookingCount = $bookings->count();

        $revenueByFacility = $bookings->groupBy(fn ($b) => $b->facility->name ?? 'Unknown')
            ->map(fn ($group) => $group->sum('total_price'))
            ->sortDesc();

        return view('admin.finance.index', compact(
            'bookings', 'totalRevenue', 'totalDiscount', 'totalAddons', 'bookingCount', 'revenueByFacility', 'from', 'to'
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        [$from, $to] = $this->resolveRange($request);

        $bookings = Booking::with('facility')
            ->whereBetween('created_at', [$from, $to])
            ->where('payment_status', 'paid')
            ->latest()
            ->get();

        return ResponseFacade::streamDownload(function () use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Booking Number', 'Facility', 'Guest', 'Check-in', 'Subtotal', 'Add-ons', 'Discount', 'Total', 'Paid At']);
            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    $booking->booking_number,
                    $booking->facility->name ?? '-',
                    $booking->guest_name,
                    $booking->check_in->format('Y-m-d'),
                    $booking->subtotal,
                    $booking->addons_total,
                    $booking->discount_amount,
                    $booking->total_price,
                    $booking->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        }, 'finance-report-'.now()->format('Ymd').'.csv');
    }

    private function resolveRange(Request $request): array
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now()->endOfDay();

        return [$from->startOfDay(), $to->endOfDay()];
    }
}
