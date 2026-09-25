<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('package_id')->nullable()->after('facility_id')->constrained()->nullOnDelete();
            $table->unsignedInteger('nights')->default(1)->after('check_out');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('facility_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropColumn(['package_id', 'nights']);
            $table->foreignId('facility_id')->nullable(false)->change();
        });
    }
};
