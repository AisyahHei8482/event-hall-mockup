@extends('layouts.eventhall-demo')
@section('title', 'Mock Payment Gateway | Demo Mode')

@section('content')
<div class="max-w-md mx-auto py-20 px-4">
    
    <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl overflow-hidden">
        <div class="bg-slate-900 p-6 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="fa-solid fa-building-columns text-6xl"></i>
            </div>
            <div class="relative z-10">
                <h2 class="text-sm font-bold uppercase tracking-widest text-slate-400 mb-2">Secure Payment Simulation</h2>
                <h1 class="text-2xl font-black">Demo Payment Gateway</h1>
            </div>
        </div>
        
        <div class="p-8">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-8 flex gap-3 text-amber-800">
                <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
                <div class="text-sm font-medium">
                    <strong>DEMO MODE ACTIVE.</strong> No real transaction will be processed. Choose an outcome below to test the booking flow.
                </div>
            </div>

            <div class="text-center mb-8">
                <div class="text-sm font-bold text-slate-500 uppercase tracking-widest mb-1">Booking Reference</div>
                <div class="text-xl font-black text-slate-900">{{ request('booking') ?? 'BKG-DEMO-123' }}</div>
            </div>

            <form method="POST" action="{{ route('event-halls.demo.payment.process') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="booking" value="{{ request('booking') }}">
                
                <button type="submit" name="status" value="success" class="w-full flex items-center justify-between px-6 py-4 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-700 font-bold rounded-2xl transition-colors group">
                    <span class="flex items-center gap-3"><i class="fa-solid fa-circle-check text-emerald-500 text-xl group-hover:scale-110 transition-transform"></i> Simulate Success</span>
                    <i class="fa-solid fa-arrow-right opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </button>

                <button type="submit" name="status" value="failed" class="w-full flex items-center justify-between px-6 py-4 bg-red-50 hover:bg-red-100 border border-red-200 text-red-700 font-bold rounded-2xl transition-colors group">
                    <span class="flex items-center gap-3"><i class="fa-solid fa-circle-xmark text-red-500 text-xl group-hover:scale-110 transition-transform"></i> Simulate Failure</span>
                    <i class="fa-solid fa-arrow-right opacity-50 group-hover:opacity-100 group-hover:translate-x-1 transition-all"></i>
                </button>
            </form>
            
            <div class="mt-8 text-center">
                <a href="{{ route('event-halls.index') }}" class="text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors">Cancel & Return to Venues</a>
            </div>
        </div>
    </div>
    
</div>
@endsection
