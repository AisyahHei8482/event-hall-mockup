@extends('layouts.admin')

@section('title', 'OTA Sync - Admin')
@section('page_title', 'OTA Channel Sync')

@section('content')
    <div class="bg-sand-50 border border-sand-200 text-forest-800 rounded-xl px-5 py-4 text-sm mb-8">
        <i class="fa-solid fa-circle-info mr-2 text-sand-600"></i>
        Real OTA integrations (Booking.com, Agoda, Airbnb) require production OAuth credentials and a live domain,
        so they can't be connected from local development. Each facility below exposes a standard iCal feed that
        any channel manager or calendar app can subscribe to once the site is deployed.
    </div>

    <div class="bg-white rounded-2xl border border-forest-100 overflow-hidden mb-10">
        <table class="w-full text-sm">
            <thead class="bg-forest-50 text-forest-600 text-left">
                <tr>
                    <th class="px-5 py-3">Channel</th>
                    <th class="px-5 py-3">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($channels as $channel)
                    <tr class="border-t border-forest-50">
                        <td class="px-5 py-3 font-medium">{{ $channel['name'] }}</td>
                        <td class="px-5 py-3"><span class="text-xs font-semibold bg-forest-50 text-forest-500 px-3 py-1 rounded-full">{{ $channel['status'] }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-lg font-bold text-forest-900 mb-4">Facility iCal Export URLs</h2>
    <div class="bg-white rounded-2xl border border-forest-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-forest-50 text-forest-600 text-left">
                <tr>
                    <th class="px-5 py-3">Facility</th>
                    <th class="px-5 py-3">Calendar URL</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($facilities as $facility)
                    <tr class="border-t border-forest-50">
                        <td class="px-5 py-3 font-medium">{{ $facility->name }}</td>
                        <td class="px-5 py-3">
                            <code class="text-xs bg-forest-50 px-2 py-1 rounded">{{ route('facilities.calendar', $facility) }}</code>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
