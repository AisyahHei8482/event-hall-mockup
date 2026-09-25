<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$franchises = \App\Models\Franchise::with('activeHalls.addons')->get();
$view = view('quotations.create', ['franchises' => $franchises, 'selectedHall' => null])->render();
file_put_contents('test_rendered.html', $view);
echo "Rendered!";
