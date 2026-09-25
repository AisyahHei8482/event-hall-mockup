@extends('layouts.app')

@section('title', $label.' - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('accommodation.index') }}" class="text-forest-300 text-sm hover:underline"><i class="fa-solid fa-arrow-left mr-1"></i> All Accommodation</a>
            <h1 class="text-4xl font-bold mt-3">{{ $label }}</h1>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        @if ($facilities->isEmpty())
            <p class="text-forest-500">No {{ strtolower($label) }} available right now. Please check back soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($facilities as $facility)
                    @include('facilities.partials.card', ['facility' => $facility])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $facilities->links() }}
            </div>
        @endif
    </section>
@endsection
