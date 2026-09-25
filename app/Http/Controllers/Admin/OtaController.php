<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\View\View;

class OtaController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::where('is_active', true)->orderBy('sort_order')->get();

        $channels = [
            ['name' => 'Booking.com', 'status' => 'Not Connected'],
            ['name' => 'Agoda', 'status' => 'Not Connected'],
            ['name' => 'Airbnb', 'status' => 'Not Connected'],
            ['name' => 'Google Calendar', 'status' => 'Not Connected'],
        ];

        return view('admin.ota.index', compact('facilities', 'channels'));
    }
}
