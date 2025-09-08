<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id(); // ledger_id
            $table->string('party_id'); // string instead of unsignedBigInteger
            $table->string('party_type'); // supplier / customer / employee
            $table->string('ref_type'); // reference type, e.g., purchase, sale
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->text('description')->nullable();
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->timestamps();
        
            $table->index(['party_id', 'party_type']);
            $table->index('invoice_no');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};
