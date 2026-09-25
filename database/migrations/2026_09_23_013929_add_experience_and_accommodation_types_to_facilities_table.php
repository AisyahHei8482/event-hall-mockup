<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE facilities MODIFY COLUMN type ENUM('facility', 'accommodation', 'activity', 'dining', 'experience') DEFAULT 'facility'");

        Schema::table('facilities', function (Blueprint $table) {
            $table->enum('accommodation_type', [
                'hotel-rooms', 'bungalows', 'trainer-rooms', 'dormitories', 'dallas-suites-hostel',
            ])->nullable()->after('type');

            $table->enum('experience_type', [
                'leisure', 'wedding', 'meeting-and-event', 'dining',
            ])->nullable()->after('accommodation_type');
        });
    }

    public function down(): void
    {
        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn(['accommodation_type', 'experience_type']);
        });

        DB::statement("ALTER TABLE facilities MODIFY COLUMN type ENUM('facility', 'accommodation', 'activity', 'dining') DEFAULT 'facility'");
    }
};
