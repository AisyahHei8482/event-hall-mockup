<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Only add columns that don't already exist (some were added in prior sessions)
        Schema::table('bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('bookings', 'event_hall_id')) {
                $table->foreignId('event_hall_id')->nullable()->after('facility_id')
                    ->constrained('event_halls')->nullOnDelete();
            }
            if (! Schema::hasColumn('bookings', 'booking_type')) {
                $table->enum('booking_type', ['accommodation', 'event_hall'])->default('accommodation')->after('event_hall_id');
            }
            if (! Schema::hasColumn('bookings', 'start_time')) {
                $table->time('start_time')->nullable()->after('check_in');
            }
            if (! Schema::hasColumn('bookings', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
            if (! Schema::hasColumn('bookings', 'duration_hours')) {
                $table->decimal('duration_hours', 5, 2)->nullable()->after('end_time');
            }
            if (! Schema::hasColumn('bookings', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0)->after('guests');
            }
            if (! Schema::hasColumn('bookings', 'event_type')) {
                $table->string('event_type')->nullable()->after('special_requests');
            }
            if (! Schema::hasColumn('bookings', 'internal_notes')) {
                $table->text('internal_notes')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'deposit_amount')) {
                $table->decimal('deposit_amount', 10, 2)->default(0);
            }
            if (! Schema::hasColumn('bookings', 'deposit_paid')) {
                $table->decimal('deposit_paid', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['event_hall_id']);
            $table->dropColumn(['event_hall_id', 'booking_type', 'start_time', 'end_time', 'duration_hours', 'event_type', 'internal_notes', 'deposit_amount', 'deposit_paid']);
        });
    }
};
