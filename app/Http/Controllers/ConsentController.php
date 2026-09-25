<?php

namespace App\Http\Controllers;

use App\Models\Consent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConsentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', 'in:cookies,marketing'],
        ]);

        Consent::create([
            'user_id' => auth()->id(),
            'email' => auth()->user()?->email,
            'type' => $validated['type'],
            'ip_address' => $request->ip(),
            'consented_at' => now(),
        ]);

        return response()->json(['status' => 'recorded']);
    }
}
