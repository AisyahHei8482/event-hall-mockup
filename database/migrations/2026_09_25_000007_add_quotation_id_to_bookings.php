<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // This runs after both bookings AND quotations tables exist
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('quotation_id')->nullable()->after('event_hall_id')
                ->constrained('quotations')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['quotation_id']);
            $table->dropColumn('quotation_id');
        });
    }
};
