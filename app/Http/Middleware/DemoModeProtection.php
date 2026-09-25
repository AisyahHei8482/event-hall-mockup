<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DemoModeProtection
{
    public function handle(Request $request, Closure $next): Response
    {
        if (env('EVENT_HALL_DEMO_MODE', false)) {
            // Block sensitive actions or modify requests if needed
            // e.g., redirect payment post to mock payment
            
            if ($request->isMethod('post') && $request->routeIs('customer.booking.pay')) {
                 return redirect()->route('event-halls.demo.payment', ['booking' => $request->route('booking')]);
            }
        }

        return $next($request);
    }
}
