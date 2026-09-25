<footer class="bg-forest-900 text-forest-100 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid grid-cols-1 md:grid-cols-3 gap-10">
        <div>
            <h3 class="text-white font-bold text-lg mb-3">Savanna Hill Resort</h3>
            <p class="text-sm text-forest-300">Truly Different Within a Traditional Neighbourhood. Sungai Tiram, Ulu Tiram, Johor Bahru — 27km from JB city centre.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Quick Links</h4>
            <ul class="space-y-2 text-sm text-forest-300">
                <li><a href="{{ route('facilities.index') }}" class="hover:text-white">Facilities</a></li>
                <li><a href="{{ route('contact.create') }}" class="hover:text-white">Contact & Inquiries</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Contact</h4>
            <ul class="space-y-2 text-sm text-forest-300">
                <li><i class="fa-solid fa-location-dot mr-2"></i>Sungai Tiram, Ulu Tiram, Johor</li>
                <li><i class="fa-solid fa-phone mr-2"></i>+60 7-XXX XXXX</li>
                <li><i class="fa-solid fa-envelope mr-2"></i>info@savannahill.com.my</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-forest-800 py-4 text-center text-xs text-forest-400">
        &copy; {{ date('Y') }} Savanna Hill Resort. All rights reserved.
    </div>
</footer>
