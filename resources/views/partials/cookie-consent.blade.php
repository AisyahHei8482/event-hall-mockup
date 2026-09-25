<div x-data="{
        show: false,
        init() {
            try { this.show = !localStorage.getItem('shr_cookie_consent'); } catch (e) { this.show = true; }
        },
        accept() {
            try { localStorage.setItem('shr_cookie_consent', '1'); } catch (e) {}
            this.show = false;
            fetch('{{ route('consent.store') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                body: JSON.stringify({ type: 'cookies' }),
            }).catch(() => {});
        },
    }"
    x-show="show" x-cloak
    class="fixed bottom-0 inset-x-0 z-50 bg-forest-900 text-forest-100 px-4 py-4 sm:px-6">
    <div class="max-w-5xl mx-auto flex flex-col sm:flex-row items-center gap-4 text-sm">
        <p class="flex-1">
            We use cookies to run this site and, with your consent, to understand how it's used.
            Read our <a href="{{ route('privacy-policy') }}" class="underline hover:text-white">Privacy Policy</a>.
        </p>
        <button @click="accept()" class="shrink-0 bg-forest-500 hover:bg-forest-400 text-white font-semibold px-5 py-2 rounded-full transition">
            Accept
        </button>
    </div>
</div>
