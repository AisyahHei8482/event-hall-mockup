<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin - Savanna Hill Resort')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { 
                extend: { 
                    colors: { 
                        forest: { 50:'#f2f7f2',100:'#dfeade',300:'#a3cba0',500:'#4c8c48',600:'#3c7038',700:'#2f5a2c',800:'#274a25',900:'#1f3b1e' } 
                    },
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
                } 
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <style type="text/tailwindcss">
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200; }
        .sidebar-active { @apply bg-forest-600 text-white shadow-md shadow-forest-500/20; }
        .sidebar-inactive { @apply text-slate-400 hover:bg-white/10 hover:text-white; }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 antialiased font-sans flex h-screen overflow-hidden" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

    <!-- Mobile Sidebar Backdrop -->
    <div x-show="sidebarOpen" x-transition.opacity 
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"
         @click="sidebarOpen = false" x-cloak></div>

    <!-- Sidebar -->
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
           class="fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-2xl lg:shadow-none">
        
        <div class="px-6 py-6 text-white font-bold text-xl flex items-center gap-3 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-forest-400 to-forest-600 flex items-center justify-center shadow-lg shadow-forest-500/30">
                <i class="fa-solid fa-tree text-white text-sm"></i>
            </div>
            <span>Savanna Hill</span>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
            </a>
            
            <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Resort Operations</div>
            <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-calendar-check w-5 text-center"></i> Bookings
            </a>
            <a href="{{ route('admin.facilities.index') }}" class="sidebar-link {{ request()->routeIs('admin.facilities.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-umbrella-beach w-5 text-center"></i> Facilities
            </a>
            
            @if (auth()->user()->isAdmin())
                <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Marketing</div>
                <a href="{{ route('admin.packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.packages.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-gift w-5 text-center"></i> Packages
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="sidebar-link {{ request()->routeIs('admin.promotions.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-tags w-5 text-center"></i> Promotions
                </a>
                
                <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Content Management</div>
                <a href="{{ route('admin.gallery.index') }}" class="sidebar-link {{ request()->routeIs('admin.gallery.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-images w-5 text-center"></i> Gallery
                </a>
                <a href="{{ route('admin.blog.index') }}" class="sidebar-link {{ request()->routeIs('admin.blog.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-newspaper w-5 text-center"></i> Blog
                </a>
                
                <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Settings</div>
                <a href="{{ route('admin.addons.index') }}" class="sidebar-link {{ request()->routeIs('admin.addons.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-puzzle-piece w-5 text-center"></i> Add-ons
                </a>
                <a href="{{ route('admin.seasonal-rates.index') }}" class="sidebar-link {{ request()->routeIs('admin.seasonal-rates.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-calendar-days w-5 text-center"></i> Seasonal Rates
                </a>
                <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-gear w-5 text-center"></i> Settings
                </a>
            @endif
            
            <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Feedback</div>
            <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-star w-5 text-center"></i> Reviews
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="sidebar-link {{ request()->routeIs('admin.inquiries.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-envelope w-5 text-center"></i> Inquiries
            </a>
        </nav>
        
        <!-- User Profile Footer -->
        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-forest-400">
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-slate-500 truncate">{{ auth()->user()->role }}</div>
                </div>
            </div>
            <div class="mt-4 flex gap-2">

                <form method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs text-slate-400 hover:text-white hover:bg-white/5 rounded-xl transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Sign Out
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col min-h-screen overflow-hidden">
        
        <!-- Top Header -->
        <header class="bg-white/80 backdrop-blur-md border-b border-slate-200 sticky top-0 z-30">
            <div class="flex items-center justify-between px-6 py-4">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="lg:hidden text-slate-500 hover:text-slate-700">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-slate-800">@yield('title')</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="text-sm font-medium text-forest-600 hover:text-forest-700 hidden sm:flex items-center gap-2 bg-forest-50 px-3 py-1.5 rounded-full transition-colors">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> View Site
                    </a>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">
                @if (session('success'))
                    <x-alert variant="success" class="mb-6 animate-fade-in-down" title="Success">
                        {{ session('success') }}
                    </x-alert>
                @endif
                @if (session('error'))
                    <x-alert variant="danger" class="mb-6 animate-fade-in-down" title="Error">
                        {{ session('error') }}
                    </x-alert>
                @endif

                @yield('content')
            </div>
        </div>
        
    </main>

    @stack('scripts')
</body>
</html>
