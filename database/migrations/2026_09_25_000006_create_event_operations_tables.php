<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Operational schedule for each confirmed booking/event
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('activity_type'); // setup, event, teardown, catering, decoration, cleaning, maintenance
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['booking_id', 'scheduled_date']);
        });

        // Tasks within event schedules
        Schema::create('event_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_schedule_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('vendor')->nullable();
            $table->time('due_time')->nullable();
            $table->enum('status', ['pending', 'assigned', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Staff assignments to bookings/events
        Schema::create('staff_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role'); // event_coordinator, catering_staff, security, etc
            $table->date('date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'date']);
            $table->index(['user_id', 'date']);
        });

        // Franchise user assignments (which staff manages which franchise)
        Schema::create('franchise_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('franchise_role')->default('manager'); // owner, manager, staff
            $table->timestamps();

            $table->unique(['franchise_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('franchise_users');
        Schema::dropIfExists('staff_assignments');
        Schema::dropIfExists('event_tasks');
        Schema::dropIfExists('event_schedules');
    }
};
