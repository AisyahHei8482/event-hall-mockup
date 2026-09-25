<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Unique token for customer-facing quotation review URL
            $table->string('customer_token')->nullable()->unique()->after('quote_number');
            // Customer can submit modification requests
            $table->text('customer_notes')->nullable()->after('requirements');
            // Track customer-requested changes
            $table->timestamp('customer_submitted_at')->nullable()->after('converted_at');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['customer_token', 'customer_notes', 'customer_submitted_at']);
        });
    }
};
