<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-forest-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-2 text-forest-800 font-bold text-lg">
                <i class="fa-solid fa-tree text-forest-600"></i>
                Savanna Hill Resort
            </a>

            @if(request()->routeIs('event-halls.*') || request()->routeIs('quotations.*'))
            <!-- EVENT HALL NAVIGATION -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium">
                <a href="{{ route('event-halls.index') }}" class="hover:text-amber-600 {{ request()->routeIs('event-halls.index') ? 'text-amber-700' : 'text-slate-900' }}">{{ __('Event Halls') }}</a>
                <a href="{{ route('quotations.create') }}" class="hover:text-amber-600 {{ request()->routeIs('quotations.create') ? 'text-amber-700' : 'text-slate-900' }}">{{ __('Get Quote') }}</a>
                <a href="{{ route('home') }}" class="hover:text-forest-600 text-forest-600 flex items-center gap-1"><i class="fa-solid fa-arrow-left text-[10px]"></i> {{ __('Back to Resort') }}</a>
            </nav>

            <div class="hidden md:flex items-center gap-4">
                @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-amber-700 hover:text-amber-900 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-100">
                            <i class="fa-solid fa-user-circle"></i> {{ auth()->user()->name }} <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-amber-100 py-2 z-50">
                            @if (auth()->user()->isStaff())
                                <a href="{{ route('management.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700"><i class="fa-solid fa-gauge w-5 text-center"></i> Admin</a>
                            @else
                                <a href="{{ route('guest.bookings.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700"><i class="fa-solid fa-calendar-check w-5 text-center"></i> {{ __('My Event Bookings') }}</a>
                                <a href="{{ route('guest.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-amber-50 hover:text-amber-700"><i class="fa-solid fa-user w-5 text-center"></i> {{ __('My Account') }}</a>
                            @endif
                            <div class="border-t border-amber-50 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><i class="fa-solid fa-sign-out-alt w-5 text-center"></i> {{ __('Logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-amber-700 hover:text-amber-900"><i class="fa-regular fa-user mr-1"></i>{{ __('Login') }}</a>
                @endauth
                <a href="{{ route('quotations.create') }}" class="bg-amber-600 hover:bg-amber-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">{{ __('Build Your Event') }}</a>
            </div>
            @else
            <!-- RESORT NAVIGATION -->
            <nav class="hidden md:flex items-center gap-7 text-sm font-medium">
                <a href="{{ route('home') }}" class="hover:text-forest-600 {{ request()->routeIs('home') ? 'text-forest-700' : 'text-forest-900' }}">{{ __('Home') }}</a>

                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('accommodation.index') }}" class="hover:text-forest-600 flex items-center gap-1 {{ request()->routeIs('accommodation.*') ? 'text-forest-700' : 'text-forest-900' }}">
                        {{ __('Accommodation') }} <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </a>
                    <div x-show="open" x-cloak x-transition class="absolute top-full left-0 pt-3 w-56">
                        <div class="bg-white border border-forest-100 rounded-xl shadow-lg py-2">
                            <a href="{{ route('accommodation.index') }}" class="block px-4 py-2 text-forest-800 hover:bg-forest-50">All</a>
                            @foreach (\App\Http\Controllers\AccommodationController::TYPES as $slug => $label)
                                <a href="{{ route('accommodation.show', $slug) }}" class="block px-4 py-2 text-forest-800 hover:bg-forest-50">{{ $label }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('exclusive-offers.index') }}" class="hover:text-forest-600 {{ request()->routeIs('exclusive-offers.*') ? 'text-forest-700' : 'text-forest-900' }}">{{ __('Exclusive Offers') }}</a>

                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <a href="{{ route('experience.index') }}" class="hover:text-forest-600 flex items-center gap-1 {{ request()->routeIs('experience.*') ? 'text-forest-700' : 'text-forest-900' }}">
                        {{ __('Experience') }} <i class="fa-solid fa-chevron-down text-[10px]"></i>
                    </a>
                    <div x-show="open" x-cloak x-transition class="absolute top-full left-0 pt-3 w-56">
                        <div class="bg-white border border-forest-100 rounded-xl shadow-lg py-2">
                            <a href="{{ route('experience.index') }}" class="block px-4 py-2 text-forest-800 hover:bg-forest-50">All</a>
                            @foreach (\App\Http\Controllers\ExperienceController::TYPES as $slug => $meta)
                                <a href="{{ route('experience.show', $slug) }}" class="block px-4 py-2 text-forest-800 hover:bg-forest-50">{{ $meta['label'] }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>

                <a href="{{ route('gallery.index') }}" class="hover:text-forest-600 {{ request()->routeIs('gallery.*') ? 'text-forest-700' : 'text-forest-900' }}">{{ __('Gallery') }}</a>
                <a href="{{ route('blog.index') }}" class="hover:text-forest-600 {{ request()->routeIs('blog.*') ? 'text-forest-700' : 'text-forest-900' }}">{{ __('Blog') }}</a>
                <a href="{{ route('contact.create') }}" class="hover:text-forest-600 {{ request()->routeIs('contact.*') ? 'text-forest-700' : 'text-forest-900' }}">{{ __('Contact Us') }}</a>
            </nav>

            <div class="hidden md:flex items-center gap-4">
                <div class="flex items-center gap-1 text-xs font-medium text-forest-500">
                    <a href="{{ route('locale.set', 'en') }}" class="hover:text-forest-800 {{ app()->getLocale() === 'en' ? 'text-forest-800 underline' : '' }}">EN</a>
                    <span>/</span>
                    <a href="{{ route('locale.set', 'ms') }}" class="hover:text-forest-800 {{ app()->getLocale() === 'ms' ? 'text-forest-800 underline' : '' }}">BM</a>
                </div>
                @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 text-sm font-medium text-forest-700 hover:text-forest-900 bg-forest-50 px-3 py-1.5 rounded-full border border-forest-100">
                            <i class="fa-solid fa-user-circle"></i> {{ auth()->user()->name }} <i class="fa-solid fa-chevron-down text-[10px]"></i>
                        </button>
                        <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-forest-100 py-2 z-50">
                            @if (auth()->user()->isStaff())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-forest-50 hover:text-forest-700"><i class="fa-solid fa-gauge w-5 text-center"></i> Admin</a>
                            @else
                                <a href="{{ route('guest.bookings.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-forest-50 hover:text-forest-700"><i class="fa-solid fa-calendar-check w-5 text-center"></i> {{ __('My Bookings') }}</a>
                                <a href="{{ route('guest.wishlist.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-forest-50 hover:text-forest-700"><i class="fa-solid fa-heart w-5 text-center"></i> {{ __('My Wishlist') }}</a>
                                <a href="{{ route('loyalty.index') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-forest-50 hover:text-forest-700"><i class="fa-solid fa-gift w-5 text-center"></i> {{ __('My Rewards') }}</a>
                                <a href="{{ route('guest.profile.edit') }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-forest-50 hover:text-forest-700"><i class="fa-solid fa-user w-5 text-center"></i> {{ __('My Account') }}</a>
                            @endif
                            <div class="border-t border-forest-50 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><i class="fa-solid fa-sign-out-alt w-5 text-center"></i> {{ __('Logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-forest-700 hover:text-forest-900"><i class="fa-regular fa-user mr-1"></i>{{ __('Login') }}</a>
                @endauth
                <a href="{{ route('event-halls.index') }}" class="bg-amber-100 text-amber-700 hover:bg-amber-200 text-sm font-semibold px-4 py-2 rounded-full transition">{{ __('Event Halls') }}</a>
                <a href="{{ route('facilities.index') }}" class="bg-forest-600 hover:bg-forest-700 text-white text-sm font-semibold px-5 py-2.5 rounded-full transition">{{ __('Book Now') }}</a>
            </div>
            @endif

            <button @click="open = !open" class="md:hidden text-forest-800 text-xl">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>

        <div x-show="open" x-cloak class="md:hidden pb-4 space-y-2">
            @if(request()->routeIs('event-halls.*') || request()->routeIs('quotations.*'))
            <a href="{{ route('event-halls.index') }}" class="block py-2 text-slate-900">{{ __('Event Halls') }}</a>
            <a href="{{ route('quotations.create') }}" class="block py-2 text-slate-900">{{ __('Get Quote') }}</a>
            <a href="{{ route('home') }}" class="block py-2 text-forest-600 font-medium">{{ __('Back to Resort') }}</a>
            @else
            <a href="{{ route('home') }}" class="block py-2 text-forest-900">{{ __('Home') }}</a>
            <a href="{{ route('accommodation.index') }}" class="block py-2 text-forest-900">{{ __('Accommodation') }}</a>
            <a href="{{ route('exclusive-offers.index') }}" class="block py-2 text-forest-900">{{ __('Exclusive Offers') }}</a>
            <a href="{{ route('experience.index') }}" class="block py-2 text-forest-900">{{ __('Experience') }}</a>
            <a href="{{ route('gallery.index') }}" class="block py-2 text-forest-900">{{ __('Gallery') }}</a>
            <a href="{{ route('blog.index') }}" class="block py-2 text-forest-900">{{ __('Blog') }}</a>
            <a href="{{ route('facilities.index') }}" class="block py-2 text-forest-900">{{ __('Facilities') }}</a>
            <a href="{{ route('packages.index') }}" class="block py-2 text-forest-900">{{ __('Packages') }}</a>
            <a href="{{ route('contact.create') }}" class="block py-2 text-forest-900">{{ __('Contact Us') }}</a>
            @endif
            @auth
                @if (auth()->user()->isStaff())
                    <a href="{{ route('management.dashboard') }}" class="block py-2 text-forest-900">Admin</a>
                @else
                    <a href="{{ route('guest.bookings.index') }}" class="block py-2 text-forest-900">{{ __('My Bookings') }}</a>
                    <a href="{{ route('guest.wishlist.index') }}" class="block py-2 text-forest-900">{{ __('My Wishlist') }}</a>
                    <a href="{{ route('loyalty.index') }}" class="block py-2 text-forest-900">{{ __('My Rewards') }}</a>
                    <a href="{{ route('guest.profile.edit') }}" class="block py-2 text-forest-900">{{ __('My Account') }}</a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block py-2 text-forest-900">{{ __('Logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-forest-900">{{ __('Login') }}</a>
            @endauth
        </div>
    </div>
</header>
