<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$f = \App\Models\Franchise::find(2);
if ($f) {
    try {
        $f->forceDelete();
        echo 'Deleted successfully';
    } catch (\Exception $e) {
        echo 'Error: ' . $e->getMessage();
    }
} else {
    echo 'Not found';
}
