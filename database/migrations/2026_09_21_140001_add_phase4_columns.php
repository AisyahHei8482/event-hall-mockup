<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('loyalty_points')->default(0)->after('phone');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->string('virtual_tour_url')->nullable()->after('cover_image');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('loyalty_points');
        });

        Schema::table('facilities', function (Blueprint $table) {
            $table->dropColumn('virtual_tour_url');
        });
    }
};
