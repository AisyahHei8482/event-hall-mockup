<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Configurable add-ons per hall (extend existing addons table pattern)
        // New column: event_hall_id (nullable) — global addons have both null
        Schema::table('addons', function (Blueprint $table) {
            $table->foreignId('event_hall_id')->nullable()->after('facility_id')
                ->constrained('event_halls')->nullOnDelete();
            $table->string('category')->nullable()->after('name'); // catering, equipment, decoration, etc
            $table->string('unit')->nullable()->after('price');    // per hour, per piece, per day
            $table->boolean('is_quantifiable')->default(true)->after('unit');
            $table->unsignedSmallInteger('max_quantity')->nullable()->after('is_quantifiable');
        });

        // Configurable pricing rules per hall
        Schema::create('hall_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_hall_id')->constrained()->cascadeOnDelete();
            $table->string('label');  // "Weekday Base", "Weekend Peak", etc
            $table->enum('rate_type', ['hourly', 'half_day', 'full_day', 'custom'])->default('hourly');
            $table->enum('day_type', ['all', 'weekday', 'weekend', 'public_holiday'])->default('all');
            $table->enum('time_type', ['all', 'peak', 'off_peak', 'custom_range'])->default('all');
            $table->time('time_from')->nullable(); // for custom_range/peak
            $table->time('time_to')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('half_day_hours', 5, 2)->nullable(); // how many hours = half day
            $table->decimal('full_day_hours', 5, 2)->nullable(); // how many hours = full day
            $table->date('season_start')->nullable(); // seasonal pricing
            $table->date('season_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('priority')->default(0); // higher wins
            $table->timestamps();

            $table->index(['event_hall_id', 'day_type', 'is_active']);
        });

        // Configurable time slots per hall, per day-of-week, per date
        Schema::create('hall_time_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_hall_id')->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('label')->nullable(); // "Morning", "Afternoon", etc
            $table->json('days_of_week')->nullable(); // [1,2,3,4,5] = Mon-Fri, null = all
            $table->date('specific_date')->nullable(); // override for specific date
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['event_hall_id', 'is_active']);
        });

        // Block-out periods (maintenance, no-book dates)
        Schema::create('hall_blockouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_hall_id')->constrained()->cascadeOnDelete();
            $table->string('reason')->nullable();
            $table->enum('blockout_type', ['full_day', 'time_range', 'maintenance'])->default('full_day');
            $table->date('date_from');
            $table->date('date_to');
            $table->time('time_from')->nullable();
            $table->time('time_to')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['event_hall_id', 'date_from', 'date_to']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hall_blockouts');
        Schema::dropIfExists('hall_time_slots');
        Schema::dropIfExists('hall_pricing_rules');
        Schema::table('addons', function (Blueprint $table) {
            $table->dropForeign(['event_hall_id']);
            $table->dropColumn(['event_hall_id', 'category', 'unit', 'is_quantifiable', 'max_quantity']);
        });
    }
};
