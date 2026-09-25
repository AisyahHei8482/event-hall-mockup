<?php

namespace App\Http\Controllers;

use App\Models\EventHall;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventHallDemoController extends Controller
{
    public function index(): View
    {
        // Standalone event hall landing page
        // Find White Hall or fallback to first active
        $hall = EventHall::with(['images'])->where('name', 'like', '%White Hall%')->first() 
                ?? EventHall::where('is_active', true)->first();
                
        $halls = EventHall::where('is_active', true)->get();

        return view('event-halls.home', compact('hall', 'halls'));
    }
    
    public function mockPayment(Request $request)
    {
        // Mock payment interface
        return view('event-halls.payment-mock', ['booking_id' => $request->input('booking', 1234)]);
    }
    
    public function processMockPayment(Request $request)
    {
        $status = $request->input('status', 'success');
        
        if ($status === 'success') {
            return redirect()->route('event-halls.index')->with('success', 'Demo Payment Successful! Booking Confirmed.');
        }
        
        return redirect()->route('event-halls.index')->with('error', 'Demo Payment Failed.');
    }
    
    public function login()
    {
        return view('event-halls.login');
    }
}
