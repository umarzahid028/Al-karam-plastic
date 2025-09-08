<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id'); // customer/buyer
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->text('remarks')->nullable();
            $table->timestamps();
    
            $table->foreign('buyer_id')->references('id')->on('raw_suppliers')->onDelete('cascade'); 
            // agar aapke buyers suppliers wali table se alag hain to uska relation idhar dal dena
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_invoices');
    }
};
