<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Savanna Hill Resort')</title>
    <meta name="description" content="@yield('meta_description', 'Truly Different Within a Traditional Neighbourhood - Savanna Hill Resort, Ulu Tiram, Johor Bahru.')">
    <link rel="canonical" href="{{ url()->current() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Savanna Hill Resort">
    <meta property="og:title" content="@yield('title', 'Savanna Hill Resort')">
    <meta property="og:description" content="@yield('meta_description', 'Truly Different Within a Traditional Neighbourhood - Savanna Hill Resort, Ulu Tiram, Johor Bahru.')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="@yield('meta_image', asset('images/og-default.jpg'))">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', 'Savanna Hill Resort')">
    <meta name="twitter:description" content="@yield('meta_description', 'Truly Different Within a Traditional Neighbourhood - Savanna Hill Resort, Ulu Tiram, Johor Bahru.')">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: { 50:'#f2f7f2',100:'#dfeade',300:'#a3cba0',500:'#4c8c48',600:'#3c7038',700:'#2f5a2c',800:'#274a25',900:'#1f3b1e' },
                        sand: { 50:'#faf7f0',100:'#f2ead6',400:'#d8bd82' },
                    },
                    fontFamily: { sans: ['Poppins','ui-sans-serif','system-ui'] },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        html { scroll-behavior: smooth; }
    </style>
    @stack('styles')
</head>
<body class="font-sans text-forest-900 bg-sand-50/30 antialiased selection:bg-forest-200 selection:text-forest-900">

    @include('partials.nav')

    <main class="min-h-[70vh]">
        @if (session('success'))
            <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                <x-alert variant="success" title="Success" class="animate-fade-in-down shadow-md">
                    {{ session('success') }}
                </x-alert>
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-7xl mx-auto mt-6 px-4 sm:px-6 lg:px-8">
                <x-alert variant="danger" title="Error" class="animate-fade-in-down shadow-md">
                    {{ session('error') }}
                </x-alert>
            </div>
        @endif

        @yield('content')
    </main>

    @include('partials.footer')

    <div x-data="{ open: false }" class="fixed bottom-5 right-5 z-50 flex flex-col items-end gap-3">
        <div x-show="open" x-cloak x-transition
             class="w-80 max-w-[85vw] bg-white rounded-2xl shadow-xl border border-forest-100 overflow-hidden">
            <div class="bg-forest-700 text-white px-4 py-3 flex items-center justify-between">
                <p class="font-semibold text-sm">{{ __('How can we help?') }}</p>
                <button @click="open = false" class="text-white/80 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <div class="p-4 space-y-2 text-sm max-h-72 overflow-y-auto" x-data="{
                faqs: [
                    { q: 'What time is check-in / check-out?', a: 'Check-in is from 2:00 PM and check-out is by 12:00 PM.' },
                    { q: 'Do you allow day visitors?', a: 'Yes, several facilities offer day-use packages — see the Packages page.' },
                    { q: 'How do I cancel a booking?', a: 'Log in and visit My Bookings to cancel a pending reservation.' },
                    { q: 'Is parking available?', a: 'Yes, free on-site parking is available for all guests.' },
                ],
                asked: [],
            }">
                <template x-for="(faq, i) in faqs" :key="i">
                    <div>
                        <button @click="asked.includes(i) ? asked = asked.filter(x => x !== i) : asked.push(i)"
                                class="w-full text-left font-medium text-forest-800 hover:text-forest-600 py-1.5">
                            <i class="fa-solid fa-circle-question text-forest-400 mr-1"></i> <span x-text="faq.q"></span>
                        </button>
                        <p x-show="asked.includes(i)" x-cloak x-text="faq.a" class="text-forest-600 pl-5 pb-2"></p>
                    </div>
                </template>
                <p class="text-xs text-forest-400 pt-2 border-t border-forest-50">Need more help? Chat with us on WhatsApp below.</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button @click="open = !open" class="w-12 h-12 rounded-full bg-forest-700 hover:bg-forest-800 text-white shadow-lg flex items-center justify-center transition">
                <i class="fa-solid fa-comment-dots text-lg"></i>
            </button>
            <a href="https://wa.me/60123456789" target="_blank" rel="noopener"
               title="{{ __('Chat with us on WhatsApp') }}"
               class="w-12 h-12 rounded-full bg-[#25D366] hover:bg-[#1ebe57] text-white shadow-lg flex items-center justify-center transition">
                <i class="fa-brands fa-whatsapp text-2xl"></i>
            </a>
        </div>
    </div>

    @include('partials.cookie-consent')
    @stack('scripts')
</body>
</html>
