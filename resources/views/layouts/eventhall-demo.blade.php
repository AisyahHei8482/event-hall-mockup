<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Event Hall Booking System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#fff7ed', 100: '#ffedd5', 500: '#f97316', 600: '#ea580c', 900: '#7c2d12' },
                        amber: { 50: '#fffbeb', 100: '#fef3c7', 200: '#fde68a', 300: '#fcd34d', 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#b45309', 800: '#92400e', 900: '#78350f' },
                        forest: { 50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0', 300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b', 600: '#475569', 700: '#334155', 800: '#1e293b', 900: '#0f172a' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white flex flex-col min-h-screen">

    @if(config('app.event_hall_context'))
    <div class="bg-slate-900 text-white text-xs font-bold text-center py-2 tracking-widest uppercase flex items-center justify-center gap-2">
        <span class="w-2 h-2 rounded-full bg-brand-500 animate-pulse"></span> DEMO MODE: Sample data only • No real transactions
    </div>
    @endif

    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm" x-data="{ mobileMenu: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-orange-400 flex items-center justify-center shadow-lg shadow-brand-500/30">
                        <i class="fa-solid fa-building text-white text-lg"></i>
                    </div>
                    <div>
                        <a href="{{ route('home') }}" class="font-black text-xl text-slate-900 tracking-tight leading-none block">Savanna Hill</a>
                        <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Event Halls</span>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-sm font-bold text-slate-900 hover:text-brand-600 transition-colors">Home</a>
                    <a href="{{ route('event-halls.index') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition-colors">Venues</a>
                    
                    @auth
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager' || auth()->user()->role === 'staff')
                            <a href="{{ route('management.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition-colors">Management Portal</a>
                        @else
                            <a href="{{ route('customer.dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-brand-600 transition-colors">My Bookings</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-800 transition-colors">Log Out</button>
                        </form>
                    @else
                        <a href="{{ route('event-halls.demo.login') }}" class="text-sm font-bold text-slate-900 hover:text-brand-600 transition-colors">Sign In</a>
                    @endauth
                </div>

                <div class="md:hidden flex items-center">
                    <button @click="mobileMenu = !mobileMenu" class="text-slate-600 hover:text-slate-900 focus:outline-none">
                        <i class="fa-solid fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" class="md:hidden border-t border-slate-100 bg-white" style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ route('home') }}" class="block px-3 py-2 text-base font-bold text-slate-900 rounded-lg hover:bg-slate-50">Home</a>
                <a href="{{ route('event-halls.index') }}" class="block px-3 py-2 text-base font-semibold text-slate-600 rounded-lg hover:bg-slate-50">Venues</a>
                @auth
                     <form method="POST" action="{{ route('logout') }}" class="block mt-4">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-base font-bold text-red-600 bg-red-50 rounded-lg">Log Out</button>
                    </form>
                @else
                    <a href="{{ route('event-halls.demo.login') }}" class="block px-3 py-2 mt-4 text-base font-bold text-center text-slate-900 bg-slate-100 rounded-xl">Sign In</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-slate-400 py-12 mt-auto border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-brand-500 flex items-center justify-center">
                        <i class="fa-solid fa-building text-white text-sm"></i>
                    </div>
                    <span class="font-black text-xl text-white tracking-tight leading-none">Savanna Hill</span>
                </div>
                <p class="text-sm font-medium leading-relaxed max-w-sm mb-6">Premium event venues for weddings, corporate events, and grand celebrations in Johor Bahru.</p>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center hover:bg-brand-500 hover:text-white transition-colors"><i class="fa-brands fa-instagram"></i></a>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Explore</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li><a href="{{ route('event-halls.index') }}" class="hover:text-brand-400 transition-colors">Our Venues</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Packages</a></li>
                    <li><a href="#" class="hover:text-brand-400 transition-colors">Gallery</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Contact</h4>
                <ul class="space-y-3 text-sm font-medium">
                    <li class="flex items-start gap-3"><i class="fa-solid fa-location-dot mt-1 text-brand-500"></i> 1, Jalan Kempas Lama 2/6, Johor Bahru</li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-brand-500"></i> +60 12 345 6789</li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-brand-500"></i> events@savannahill.local</li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-slate-800 text-xs font-medium text-center">
            &copy; {{ date('Y') }} Savanna Hill Event Hall. All rights reserved. @if(config('app.event_hall_context')) (Demo Environment) @endif
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
