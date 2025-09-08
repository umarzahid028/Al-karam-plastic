<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_category')->nullable();   // Category
            $table->string('expense_type');                  // Required
            $table->string('vendor')->nullable();            // Vendor / Paid To
            $table->string('payment_method')->nullable();    // Cash / Bank Transfer / etc.
            $table->decimal('amount', 15, 2);                // Amount (PKR)
            $table->date('expense_date');                    // Date
            $table->string('reference_no')->nullable();      // Invoice / Ref No
            $table->string('attachment')->nullable();        // File path
            $table->string('approved_by')->nullable();       // Approved by
            $table->string('salesperson')->nullable();       // Salesperson
            $table->text('description')->nullable();         // Notes
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
