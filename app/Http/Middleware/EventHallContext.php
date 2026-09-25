<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EventHallContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $demoMode = env('EVENT_HALL_DEMO_MODE', false);
        $demoDomain = env('EVENT_HALL_DOMAIN');
        
        $isDemoDomain = $demoDomain && $request->getHost() === $demoDomain;
        
        if ($demoMode && ($isDemoDomain || $request->is('event-halls*'))) {
            // Set global config or context variable
            config(['app.event_hall_context' => true]);
            
            // Share demo mode flag with all views
            view()->share('isEventHallDemo', true);
        } else {
            view()->share('isEventHallDemo', false);
        }

        return $next($request);
    }
}
