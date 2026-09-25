<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_hall_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('franchise_id')->nullable()->constrained()->nullOnDelete();

            // Guest details (may not be registered user)
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone')->nullable();
            $table->string('event_type')->nullable();
            $table->unsignedInteger('guests')->default(1);

            // Event details
            $table->date('event_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('duration_hours', 5, 2)->nullable();
            $table->text('requirements')->nullable();

            // Pricing breakdown
            $table->decimal('subtotal', 10, 2)->default(0);          // hall rental
            $table->decimal('addons_total', 10, 2)->default(0);      // add-ons total
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->string('discount_reason')->nullable();
            $table->decimal('tax_rate', 5, 2)->default(0);           // % e.g. 6.00
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('service_charge_rate', 5, 2)->default(0); // % e.g. 10.00
            $table->decimal('service_charge_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('deposit_required', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);

            // Status lifecycle
            $table->enum('status', [
                'draft', 'generated', 'sent', 'viewed', 'accepted',
                'rejected', 'expired', 'converted', 'cancelled'
            ])->default('draft');

            $table->date('valid_until')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->string('rejection_reason')->nullable();
            $table->text('terms_conditions')->nullable();
            $table->text('internal_notes')->nullable();

            // Override tracking
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();

            $table->index(['status', 'event_date']);
            $table->index(['guest_email', 'status']);
        });

        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained()->cascadeOnDelete();
            $table->string('item_type'); // hall_rental, addon, catering, decoration, etc
            $table->string('description');
            $table->unsignedSmallInteger('quantity')->default(1);
            $table->string('unit')->nullable(); // hours, pieces, per event
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->foreignId('addon_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
        Schema::dropIfExists('quotations');
    }
};
