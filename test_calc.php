<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $calculator = app(\App\Services\QuotationCalculator::class);
    $result = $calculator->calculate([
        'event_hall_id' => 1,
        'event_date' => '2026-12-01',
        'start_time' => '09:00',
        'end_time' => '12:00',
        'guests' => 100,
        'addons' => []
    ]);
    dump($result);
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
