<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('buyer_id');
            $table->string('invoice_no')->index();
            $table->date('invoice_date');
            $table->decimal('total_amount', 15, 2)->default(0.00);
            $table->text('remarks')->nullable();
            $table->timestamps();
    
            $table->foreign('buyer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('customer_invoices');
    }
    
};
