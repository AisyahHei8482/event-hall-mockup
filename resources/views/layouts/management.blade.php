<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Management — Savanna Hill Resort')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:'#fdf4ee',100:'#fce5d2',200:'#f9c9a0',300:'#f5a762',
                            400:'#f0832b',500:'#e86c18',600:'#c9530f',700:'#a73f0e',
                            800:'#883413',900:'#6e2b12'
                        },
                        forest: {
                            50:'#fdf4ee',100:'#fce5d2',200:'#f9c9a0',300:'#f5a762',
                            400:'#f0832b',500:'#e86c18',600:'#c9530f',700:'#a73f0e',
                            800:'#883413',900:'#6e2b12'
                        },
                        mgmt: {
                            50:'#f8f8f8',100:'#f0f0f0',800:'#1a1a2e',900:'#0f0f1a'
                        }
                    },
                    fontFamily: { sans: ['Inter','ui-sans-serif','system-ui'] },
                }
            }
        };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style type="text/tailwindcss">
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium transition-all duration-200; }
        .sidebar-active { @apply bg-brand-500 text-white shadow-md shadow-brand-500/20; }
        .sidebar-inactive { @apply text-slate-400 hover:bg-white/5 hover:text-white; }
        [x-cloak] { display: none !important; }
        
        @media (max-width: 768px) {
            .mobile-card-table, .mobile-card-table tbody, .mobile-card-table tr, .mobile-card-table td {
                display: block;
                width: 100%;
            }
            .mobile-card-table thead { display: none; }
            .mobile-card-table tr {
                margin-bottom: 0.75rem;
                border: 1px solid #e2e8f0;
                border-radius: 0.75rem;
                overflow: hidden;
                background-color: white;
                box-shadow: 0 1px 2px rgba(0,0,0,0.05);
            }
            .mobile-card-table td {
                display: flex !important;
                justify-content: space-between;
                align-items: flex-start;
                text-align: right;
                padding: 0.6rem 0.75rem !important;
                border-bottom: 1px solid #f8fafc;
                min-width: 0 !important;
                gap: 0.5rem;
            }
            .mobile-card-table td:last-child { border-bottom: none; }
            .mobile-card-table td::before {
                content: attr(data-label);
                font-weight: 700;
                font-size: 0.65rem;
                text-transform: uppercase;
                letter-spacing: 0.05em;
                color: #64748b;
                text-align: left;
                flex-shrink: 0;
                width: 35%;
                margin-top: 0.1rem;
            }
            .mobile-card-table td .td-content { 
                flex-grow: 1; 
                text-align: right; 
                display: flex;
                flex-direction: column;
                align-items: flex-end;
            }
            /* Force hidden columns to appear in card view */
            .mobile-card-table .hidden { display: flex !important; }
        }
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
           class="fixed inset-y-0 left-0 z-50 w-72 bg-mgmt-900 text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex flex-col shadow-2xl lg:shadow-none">
        
        <div class="px-6 py-6 text-white font-bold text-xl flex items-center gap-3 border-b border-mgmt-800">
            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-400 to-brand-600 flex items-center justify-center shadow-lg shadow-brand-500/30">
                <i class="fa-solid fa-building text-white text-sm"></i>
            </div>
            <span>Event Halls</span>
            <button @click="sidebarOpen = false" class="ml-auto lg:hidden text-slate-400 hover:text-white">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto custom-scrollbar">
            <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-4">Overview</div>
            <a href="{{ route('management.dashboard') }}" class="sidebar-link {{ request()->routeIs('management.dashboard') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i> Dashboard
            </a>

            <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Operations</div>

            <a href="{{ route('management.bookings.index') }}" class="sidebar-link {{ request()->routeIs('management.bookings.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-file-invoice w-5 text-center"></i> Bookings
            </a>
            <a href="{{ route('management.quotations.index') }}" class="sidebar-link {{ request()->routeIs('management.quotations.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                <i class="fa-solid fa-file-signature w-5 text-center"></i> Quotations
            </a>

            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Configuration</div>
                <a href="{{ route('management.franchises.index') }}" class="sidebar-link {{ request()->routeIs('management.franchises.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-sitemap w-5 text-center"></i> Franchises
                </a>
                <a href="{{ route('management.halls.index') }}" class="sidebar-link {{ request()->routeIs('management.halls.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-door-open w-5 text-center"></i> Event Halls
                </a>
            @endif
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('management.staff.index') }}" class="sidebar-link {{ request()->routeIs('management.staff.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-users w-5 text-center"></i> Staff & Roles
                </a>

                <div class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2 mt-6">Analytics</div>
                <a href="{{ route('management.reports.index') }}" class="sidebar-link {{ request()->routeIs('management.reports.*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i> Reports
                </a>
            @endif
        </nav>
        
        <!-- User Profile Footer -->
        <div class="p-4 border-t border-mgmt-800">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-mgmt-800 flex items-center justify-center text-brand-400">
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
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Logout
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
                    <a href="{{ route('event-halls.index') }}" target="_blank" class="text-sm font-medium text-brand-600 hover:text-brand-700 hidden sm:flex items-center gap-2 bg-brand-50 px-3 py-1.5 rounded-full transition-colors">
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function transformTables() {
                if (window.innerWidth <= 768) {
                    document.querySelectorAll('table').forEach(table => {
                        // Skip if it is already transformed or is a calendar/layout table
                        if (table.classList.contains('mobile-card-table') || table.closest('.fc')) return;
                        
                        table.classList.add('mobile-card-table');
                        const headers = Array.from(table.querySelectorAll('thead th')).map(th => th.innerText.trim());
                        
                        table.querySelectorAll('tbody tr').forEach(tr => {
                            tr.querySelectorAll('td').forEach((td, index) => {
                                if (headers[index]) {
                                    td.setAttribute('data-label', headers[index]);
                                }
                                if (!td.querySelector('.td-content')) {
                                    const content = td.innerHTML;
                                    td.innerHTML = `<div class="td-content">${content}</div>`;
                                }
                            });
                        });
                    });
                }
            }
            transformTables();
            window.addEventListener('resize', transformTables);
        });
    </script>
</body>
</html>
