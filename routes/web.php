<?php

use App\Http\Controllers\AccommodationController;
use App\Http\Controllers\Admin\ActivityLogController as AdminActivityLogController;
use App\Http\Controllers\Admin\AddonController as AdminAddonController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Admin\FinanceController as AdminFinanceController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;
use App\Http\Controllers\Admin\OtaController;
use App\Http\Controllers\Admin\PackageController as AdminPackageController;
use App\Http\Controllers\Admin\PromotionController as AdminPromotionController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SeasonalRateController as AdminSeasonalRateController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\TwoFactorChallengeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\EventHallController;
use App\Http\Controllers\ExclusiveOfferController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\Guest\BookingController as GuestBookingController;
use App\Http\Controllers\Guest\ProfileController;
use App\Http\Controllers\Guest\ReceiptController;
use App\Http\Controllers\Guest\TwoFactorController as GuestTwoFactorController;
use App\Http\Controllers\Guest\WishlistController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SitemapController;
 use App\Http\Controllers\CustomerQuotationController;
use App\Http\Controllers\Management\ManagementDashboardController;
use App\Http\Controllers\Management\FranchiseController as ManagementFranchiseController;
use App\Http\Controllers\Management\EventHallController as ManagementEventHallController;
use App\Http\Controllers\Management\QuotationController as ManagementQuotationController;
use App\Http\Controllers\Management\BookingManagementController;
use App\Http\Controllers\Management\ReportController;
use App\Http\Controllers\Management\StaffController as ManagementStaffController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

// ── Event Hall Standalone Domain ──────────────────────────────────────────────
if (env('EVENT_HALL_DEMO_MODE', false) && env('EVENT_HALL_DOMAIN')) {
    Route::domain(env('EVENT_HALL_DOMAIN'))->group(function () {
        Route::get('/', [\App\Http\Controllers\EventHallDemoController::class, 'index'])->name('event-halls.demo.home');
        Route::get('/payment-mock', [\App\Http\Controllers\EventHallDemoController::class, 'mockPayment'])->name('event-halls.demo.payment');
        Route::post('/payment-mock', [\App\Http\Controllers\EventHallDemoController::class, 'processMockPayment'])->name('event-halls.demo.payment.process');
        Route::get('/login', [\App\Http\Controllers\EventHallDemoController::class, 'login'])->name('event-halls.demo.login');
    });
}

// Ensure the main home route doesn't override the domain home if matched, though domain groups take precedence.
Route::get('/', function () {
    if (config('app.event_hall_context')) {
        return app()->make(\App\Http\Controllers\EventHallDemoController::class)->index();
    }
    return app()->make(HomeController::class)->index();
})->name('home');

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/locale/{locale}', [HomeController::class, 'setLocale'])->name('locale.set');

Route::get('/facilities', [FacilityController::class, 'index'])->name('facilities.index');
Route::get('/facilities/{facility:slug}', [FacilityController::class, 'show'])->name('facilities.show');
Route::get('/facilities/{facility:slug}/calendar.ics', [CalendarController::class, 'show'])->name('facilities.calendar');
Route::post('/facilities/{facility:slug}/reviews', [ReviewController::class, 'store'])
    ->middleware('throttle:6,1')->name('reviews.store');

Route::get('/accommodation', [AccommodationController::class, 'index'])->name('accommodation.index');
Route::get('/accommodation-type/{type}', [AccommodationController::class, 'show'])->name('accommodation.show');

Route::get('/experience', [ExperienceController::class, 'index'])->name('experience.index');
Route::get('/experience-type/{type}', [ExperienceController::class, 'show'])->name('experience.show');

Route::get('/exclusive-offers', [ExclusiveOfferController::class, 'index'])->name('exclusive-offers.index');

Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{post:slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/packages', [PackageController::class, 'index'])->name('packages.index');
Route::get('/packages/{package:slug}', [PackageController::class, 'show'])->name('packages.show');

Route::get('/contact', [InquiryController::class, 'create'])->name('contact.create');
Route::post('/contact', [InquiryController::class, 'store'])
    ->middleware('throttle:6,1')->name('contact.store');

Route::get('/privacy-policy', fn () => view('legal.privacy-policy'))->name('privacy-policy');
Route::post('/consent', [ConsentController::class, 'store'])->name('consent.store');
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// ── Event Halls (Customer) ──────────────────────────────────────────────────
Route::prefix('event-halls')->name('event-halls.')->group(function () {
    Route::get('/', [EventHallController::class, 'index'])->name('index');
    Route::get('/{hall}', [EventHallController::class, 'show'])->name('show');
    Route::get('/{hall}/packages', [EventHallController::class, 'packages'])->name('packages');
    Route::get('/{hall}/availability', [EventHallController::class, 'availability'])->name('availability');
});

// ── Quotations (Customer) ───────────────────────────────────────────────────
Route::prefix('get-quote')->name('quotations.')->group(function () {
    Route::get('/', [QuotationController::class, 'create'])->name('create');
    Route::post('/', [QuotationController::class, 'store'])->middleware('throttle:10,1')->name('store');
    Route::post('/calculate', [QuotationController::class, 'calculate'])->middleware('throttle:30,1')->name('calculate');
    Route::get('/{quotation:quote_number}', [QuotationController::class, 'show'])->name('show');
    Route::get('/{quotation:quote_number}/pdf', [QuotationController::class, 'pdf'])->name('pdf');
    Route::post('/{quotation:quote_number}/accept', [QuotationController::class, 'accept'])->name('accept');
    Route::post('/{quotation:quote_number}/reject', [QuotationController::class, 'reject'])->name('reject');
});

Route::get('/facilities/{facility:slug}/book', [BookingController::class, 'create'])->name('bookings.create');
Route::post('/facilities/{facility:slug}/book', [BookingController::class, 'store'])
    ->middleware('throttle:10,1')->name('bookings.store');
Route::get('/bookings/{booking:booking_number}/confirmation', [BookingController::class, 'confirmation'])->name('bookings.confirmation');
Route::get('/bookings/{booking:booking_number}/pay', [PaymentController::class, 'show'])->name('bookings.pay');
Route::post('/bookings/{booking:booking_number}/pay', [PaymentController::class, 'process'])
    ->middleware('throttle:10,1')->name('bookings.pay.process');
Route::get('/bookings/{booking:booking_number}/pay/success', [PaymentController::class, 'success'])->name('bookings.pay.success');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('throttle:5,1');
    Route::get('/two-factor-challenge', [TwoFactorChallengeController::class, 'create'])->name('two-factor.login');
    Route::post('/two-factor-challenge', [TwoFactorChallengeController::class, 'store'])
        ->middleware('throttle:5,1')->name('two-factor.login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/my-bookings', [GuestBookingController::class, 'index'])->name('guest.bookings.index');
    Route::post('/my-bookings/{booking}/cancel', [GuestBookingController::class, 'cancel'])->name('guest.bookings.cancel');
    Route::get('/my-bookings/{booking}/receipt', [ReceiptController::class, 'show'])->name('guest.bookings.receipt');
    Route::get('/my-rewards', [LoyaltyController::class, 'index'])->name('loyalty.index');

    Route::get('/my-wishlist', [WishlistController::class, 'index'])->name('guest.wishlist.index');
    Route::post('/wishlist/{facility:slug}', [WishlistController::class, 'store'])->name('guest.wishlist.store');
    Route::delete('/wishlist/{facility:slug}', [WishlistController::class, 'destroy'])->name('guest.wishlist.destroy');

    Route::get('/my-account', [ProfileController::class, 'edit'])->name('guest.profile.edit');
    Route::put('/my-account', [ProfileController::class, 'update'])->name('guest.profile.update');
    Route::put('/my-account/password', [ProfileController::class, 'updatePassword'])->name('guest.profile.password');

    Route::get('/my-account/two-factor', [GuestTwoFactorController::class, 'show'])->name('guest.two-factor.show');
    Route::post('/my-account/two-factor', [GuestTwoFactorController::class, 'store'])->name('guest.two-factor.store');
    Route::post('/my-account/two-factor/confirm', [GuestTwoFactorController::class, 'confirm'])->name('guest.two-factor.confirm');
    Route::delete('/my-account/two-factor', [GuestTwoFactorController::class, 'destroy'])->name('guest.two-factor.destroy');
});

// Admin panel routes accessible to both staff and admin
Route::middleware(['auth', 'staff'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
    Route::patch('/inquiries/{inquiry}', [AdminInquiryController::class, 'update'])->name('inquiries.update');
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews.index');
    Route::patch('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

// Admin panel routes restricted to full admins only
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('facilities', AdminFacilityController::class)->except(['show']);
    Route::resource('packages', AdminPackageController::class)->except(['show']);
    Route::resource('promotions', AdminPromotionController::class)->except(['show']);
    Route::resource('gallery', AdminGalleryController::class)->except(['show']);
    Route::resource('blog', AdminBlogController::class)->except(['show'])->parameters(['blog' => 'post']);
    Route::resource('addons', AdminAddonController::class)->except(['show']);
    Route::resource('seasonal-rates', AdminSeasonalRateController::class)->except(['show'])->parameters(['seasonal-rates' => 'seasonalRate']);
    Route::get('/staff', [StaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [StaffController::class, 'store'])->name('staff.store');
    Route::patch('/staff/{staff}', [StaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy'])->name('staff.destroy');
    Route::get('/ota', [OtaController::class, 'index'])->name('ota.index');
    Route::get('/activity-log', [AdminActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/settings', [AdminSettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    Route::get('/finance', [AdminFinanceController::class, 'index'])->name('finance.index');
    Route::get('/finance/export', [AdminFinanceController::class, 'export'])->name('finance.export');
});

// ── Management Portal ───────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->prefix('management')->name('management.')->group(function () {
    Route::get('/', [ManagementDashboardController::class, 'index'])->name('dashboard');

    // Franchises
    Route::resource('franchises', ManagementFranchiseController::class);

    // Event Halls
    Route::resource('halls', ManagementEventHallController::class)->except(['show']);
    Route::get('/halls/{hall}', [ManagementEventHallController::class, 'show'])->name('halls.show');
    Route::get('/halls/{hall}/calendar', [ManagementEventHallController::class, 'calendar'])->name('halls.calendar');
    Route::post('/halls/{hall}/pricing-rules', [ManagementEventHallController::class, 'storePricingRule'])->name('halls.pricing-rules.store');
    Route::patch('/halls/{hall}/pricing-rules/{rule}', [ManagementEventHallController::class, 'updatePricingRule'])->name('halls.pricing-rules.update');
    Route::delete('/halls/{hall}/pricing-rules/{rule}', [ManagementEventHallController::class, 'destroyPricingRule'])->name('halls.pricing-rules.destroy');
    Route::post('/halls/{hall}/time-slots', [ManagementEventHallController::class, 'storeTimeSlot'])->name('halls.time-slots.store');
    Route::patch('/halls/{hall}/time-slots/{slot}', [ManagementEventHallController::class, 'updateTimeSlot'])->name('halls.time-slots.update');
    Route::delete('/halls/{hall}/time-slots/{slot}', [ManagementEventHallController::class, 'destroyTimeSlot'])->name('halls.time-slots.destroy');
    Route::post('/halls/{hall}/blockouts', [ManagementEventHallController::class, 'storeBlockout'])->name('halls.blockouts.store');
    Route::patch('/halls/{hall}/blockouts/{blockout}', [ManagementEventHallController::class, 'updateBlockout'])->name('halls.blockouts.update');
    Route::delete('/halls/{hall}/blockouts/{blockout}', [ManagementEventHallController::class, 'destroyBlockout'])->name('halls.blockouts.destroy');
    Route::post('/halls/{hall}/addons', [ManagementEventHallController::class, 'storeAddon'])->name('halls.addons.store');
    Route::patch('/halls/{hall}/addons/{addon}', [ManagementEventHallController::class, 'updateAddon'])->name('halls.addons.update');
    Route::delete('/halls/{hall}/addons/{addon}', [ManagementEventHallController::class, 'destroyAddon'])->name('halls.addons.destroy');
    Route::patch('/halls/{hall}/amenities', [ManagementEventHallController::class, 'updateAmenities'])->name('halls.amenities.update');

    // Quotations
    Route::get('/quotations', [ManagementQuotationController::class, 'index'])->name('quotations.index');
    Route::get('/quotations/{quotation}', [ManagementQuotationController::class, 'show'])->name('quotations.show');
    Route::get('/quotations/{quotation}/edit', [ManagementQuotationController::class, 'edit'])->name('quotations.edit');
    Route::put('/quotations/{quotation}', [ManagementQuotationController::class, 'update'])->name('quotations.update');
    Route::post('/quotations/{quotation}/send', [ManagementQuotationController::class, 'send'])->name('quotations.send');
    Route::post('/quotations/{quotation}/accept', [ManagementQuotationController::class, 'accept'])->name('quotations.accept');
    Route::post('/quotations/{quotation}/reject', [ManagementQuotationController::class, 'reject'])->name('quotations.reject');
    Route::post('/quotations/{quotation}/convert', [ManagementQuotationController::class, 'convert'])->name('quotations.convert');
    Route::post('/quotations/{quotation}/expire', [ManagementQuotationController::class, 'expire'])->name('quotations.expire');
    Route::get('/quotations/{quotation}/pdf', [ManagementQuotationController::class, 'pdf'])->name('quotations.pdf');

    // Bookings
    Route::get('/bookings', [BookingManagementController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/calendar', [BookingManagementController::class, 'calendarJson'])->name('bookings.calendar');
    Route::get('/bookings/{booking}', [BookingManagementController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [BookingManagementController::class, 'update'])->name('bookings.update');
    Route::post('/bookings/{booking}/payment', [BookingManagementController::class, 'recordPayment'])->name('bookings.payment');
    Route::get('/bookings/{booking}/invoice', [BookingManagementController::class, 'invoice'])->name('bookings.invoice');
    Route::post('/bookings/{booking}/schedule', [BookingManagementController::class, 'addSchedule'])->name('bookings.schedule.store');
    Route::patch('/bookings/{booking}/schedule/{schedule}', [BookingManagementController::class, 'updateSchedule'])->name('bookings.schedule.update');
    Route::post('/bookings/{booking}/schedule/{schedule}/tasks', [BookingManagementController::class, 'addTask'])->name('bookings.tasks.store');
    Route::patch('/bookings/{booking}/tasks/{task}', [BookingManagementController::class, 'updateTask'])->name('bookings.tasks.update');
    Route::post('/bookings/{booking}/staff', [BookingManagementController::class, 'assignStaff'])->name('bookings.staff.store');
    Route::patch('/bookings/{booking}/staff/{assignment}', [BookingManagementController::class, 'updateStaff'])->name('bookings.staff.update');
    Route::delete('/bookings/{booking}/staff/{assignment}', [BookingManagementController::class, 'removeStaff'])->name('bookings.staff.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/bookings', [ReportController::class, 'bookings'])->name('reports.bookings');
    Route::get('/reports/bookings/export', [ReportController::class, 'exportBookings'])->name('reports.bookings.export');
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/quotations', [ReportController::class, 'quotations'])->name('reports.quotations');
    Route::get('/reports/operations', [ReportController::class, 'operations'])->name('reports.operations');

    // Staff Management (admin only)
    Route::get('/staff', [ManagementStaffController::class, 'index'])->name('staff.index');
    Route::get('/staff/create', [ManagementStaffController::class, 'create'])->name('staff.create');
    Route::post('/staff', [ManagementStaffController::class, 'store'])->name('staff.store');
    Route::get('/staff/{staff}/edit', [ManagementStaffController::class, 'edit'])->name('staff.edit');
    Route::patch('/staff/{staff}', [ManagementStaffController::class, 'update'])->name('staff.update');
    Route::delete('/staff/{staff}', [ManagementStaffController::class, 'destroy'])->name('staff.destroy');
});

// -- Customer Portal -------------------------------------------------------
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
});

// -- Customer Quotation Review (public, token-based, no auth) --------------
Route::get('/q/{token}', [CustomerQuotationController::class, 'show'])->name('customer.quotation.show');
Route::post('/q/{token}', [CustomerQuotationController::class, 'submit'])->name('customer.quotation.submit');

//artisan 
Route::get('/setup-mockup', function () {
    // 1. Run migrations and seeders together
    // Note: --force is required because Wasmer runs in production mode
    Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    
    // 2. Optimize the application cache
    Artisan::call('optimize');

    return response()->json([
        'status' => 'success',
        'message' => 'Migration, seeding, and optimization completed!',
        'output' => Artisan::output()
    ]);
});

Route::get('/optimize-mockup', function () {
    
    // 2. Optimize the application cache
    Artisan::call('optimize');

    return response()->json([
        'status' => 'success',
        'message' => 'Optimization completed!',
        'output' => Artisan::output()
    ]);
});

Route::get('/debug-storage', function () {
    $disk = Storage::disk('public');

    $files = $disk->allFiles();

    return response()->json([
        'disk_root' => $disk->path(''),
        'disk_files' => $files,
        'jpg_exists' => $disk->exists('halls/ajscWkSAKROTVeLEtDfoximkXWlOP0RqTOmb0AMH.jpg'),
        'jpg_path' => $disk->path('halls/ajscWkSAKROTVeLEtDfoximkXWlOP0RqTOmb0AMH.jpg'),
        'jpg_size' => $disk->exists('halls/ajscWkSAKROTVeLEtDfoximkXWlOP0RqTOmb0AMH.jpg')
            ? $disk->size('halls/ajscWkSAKROTVeLEtDfoximkXWlOP0RqTOmb0AMH.jpg')
            : null,
    ]);
});

Route::get('/debug-storage-link', function () {
    return response()->json([
        'public_storage' => public_path('storage'),
        'exists' => file_exists(public_path('storage')),
        'is_link' => is_link(public_path('storage')),
        'target' => is_link(public_path('storage'))
            ? readlink(public_path('storage'))
            : null,
        'expected' => storage_path('app/public'),
    ]);
});

// Serve storage files fallback for Wasmer Edge
Route::get('/media/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    
    if (!file_exists($filePath)) {
        abort(404);
    }
    
    $mime = mime_content_type($filePath);
    $content = file_get_contents($filePath);
    
    return response($content)->header('Content-Type', $mime);
})->where('path', '.*');