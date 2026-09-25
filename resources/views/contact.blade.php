@extends('layouts.app')

@section('title', 'Contact & Inquiries - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Contact & Inquiries</h1>
            <p class="mt-3 text-forest-200 max-w-2xl">Have a question about bookings, corporate events, or general enquiries? Reach out and our team will respond promptly.</p>
        </div>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-forest-800 mb-1">Inquiry Type</label>
                    <select name="type" class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">
                        <option value="general" @selected(old('type') === 'general')>General Inquiry</option>
                        <option value="booking" @selected(old('type') === 'booking')>Booking</option>
                        <option value="corporate" @selected(old('type') === 'corporate')>Corporate Event</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-forest-800 mb-1">Message</label>
                <textarea name="message" rows="5" required
                          class="w-full border border-forest-200 rounded-lg px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-forest-400">{{ old('message') }}</textarea>
            </div>

            <button type="submit" class="bg-forest-600 hover:bg-forest-700 text-white font-semibold px-8 py-3 rounded-full transition">
                Send Message
            </button>
        </form>
    </section>
@endsection
