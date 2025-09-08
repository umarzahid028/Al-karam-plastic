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
        Schema::create('raw_suppliers', function (Blueprint $table) {
            $table->id(); // auto-increment primary key
            $table->string('supplier_code')->unique(); // tumhara custom code, e.g., SUP-001
            $table->string('company_name');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('contact_no')->nullable();
            $table->decimal('opening_balance', 12, 2)->default(0); // default 0
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_suppliers');
    }
};
