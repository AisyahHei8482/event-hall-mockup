@extends('layouts.app')

@section('title', 'Packages & Promotions - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Packages & Promotions</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Bundled experiences combining our facilities, activities and dining at special rates.</p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if ($packages->isEmpty())
            <p class="text-forest-500">No packages available right now. Please check back soon.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($packages as $package)
                    @include('packages.partials.card', ['package' => $package])
                @endforeach
            </div>
            <div class="mt-10">
                {{ $packages->links() }}
            </div>
        @endif
    </section>
@endsection
