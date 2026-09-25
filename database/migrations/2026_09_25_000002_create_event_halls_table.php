<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_halls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('capacity')->default(0);
            $table->decimal('floor_area', 10, 2)->nullable(); // sq meters
            $table->string('hall_type')->nullable(); // ballroom, conference, banquet, outdoor, etc
            $table->json('facilities')->nullable();  // ["stage","projector","lcd_screen"]
            $table->json('amenities')->nullable();   // ["parking","wifi","ac"]
            $table->string('cover_image')->nullable();
            $table->json('operating_hours')->nullable(); // override franchise hours if needed
            $table->unsignedSmallInteger('min_booking_hours')->default(1);
            $table->unsignedSmallInteger('max_booking_hours')->nullable();
            $table->unsignedSmallInteger('buffer_before')->default(0); // minutes
            $table->unsignedSmallInteger('buffer_after')->default(0);  // minutes
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['franchise_id', 'is_active']);
        });

        Schema::create('hall_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_hall_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('caption')->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hall_images');
        Schema::dropIfExists('event_halls');
    }
};
