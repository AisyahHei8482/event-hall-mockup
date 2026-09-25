@extends('layouts.app')

@section('title', 'Privacy Policy - Savanna Hill Resort')

@section('content')
    <section class="bg-forest-800 text-white py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold">Privacy Policy</h1>
            <p class="mt-3 text-forest-200">How Savanna Hill Resort collects, uses, and protects your personal data.</p>
        </div>
    </section>

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-8 text-forest-800 leading-relaxed">
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">1. Information We Collect</h2>
            <p>We collect information you provide directly, such as your name, email address, phone number, booking details, and payment status, when you register an account, make a booking, submit an inquiry, or leave a review. We also collect technical data such as IP address and cookie identifiers to keep the site secure and functioning.</p>
        </div>
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">2. How We Use Your Information</h2>
            <p>Your information is used to process bookings and payments, communicate booking confirmations and updates, respond to inquiries, administer our loyalty programme, and improve our services. We do not sell your personal data to third parties.</p>
        </div>
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">3. Cookies</h2>
            <p>We use essential cookies to operate the site (e.g. keeping you signed in) and, with your consent, analytics cookies to understand how the site is used. You can manage your cookie preferences at any time via the cookie banner shown on your first visit.</p>
        </div>
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">4. Your Rights</h2>
            <p>Under applicable data protection laws (including the Malaysian Personal Data Protection Act 2010 and, where applicable, the GDPR), you have the right to access, correct, or request deletion of your personal data, and to withdraw consent at any time. You may manage your profile information from <a href="{{ route('guest.profile.edit') }}" class="text-forest-700 font-semibold hover:underline">My Account</a> or contact us to exercise these rights.</p>
        </div>
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">5. Data Retention & Security</h2>
            <p>We retain personal data only as long as necessary for the purposes described above, and apply reasonable technical and organisational measures — including encryption of sensitive fields and access controls — to protect it from unauthorised access.</p>
        </div>
        <div>
            <h2 class="text-xl font-bold text-forest-900 mb-2">6. Contact Us</h2>
            <p>For questions about this policy or to exercise your data protection rights, please reach out via our <a href="{{ route('contact.create') }}" class="text-forest-700 font-semibold hover:underline">contact page</a>.</p>
        </div>
    </section>
@endsection
