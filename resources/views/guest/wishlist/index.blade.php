@extends('layouts.app')

@section('title', 'My Wishlist - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">My Wishlist</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Facilities and experiences you've saved for later.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if (session('success'))
            <div class="mb-6 bg-forest-100 border border-forest-300 text-forest-800 px-4 py-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if ($facilities->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($facilities as $facility)
                    @include('facilities.partials.card', ['facility' => $facility])
                @endforeach
            </div>
            <div class="mt-6">{{ $facilities->links() }}</div>
        @else
            <p class="text-forest-500">Your wishlist is empty. <a href="{{ route('facilities.index') }}" class="text-forest-600 underline">Browse facilities</a> to add some.</p>
        @endif
    </section>
@endsection
