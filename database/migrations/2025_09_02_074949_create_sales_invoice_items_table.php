<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('sales_invoice_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('sales_invoice_id');
        $table->unsignedBigInteger('product_id');
        $table->decimal('qty', 12, 2);
        $table->decimal('price', 12, 2);
        $table->decimal('total', 12, 2);
        $table->timestamps();

        $table->foreign('sales_invoice_id')
              ->references('id')->on('sales_invoices')
              ->onDelete('cascade');
    });
}


    public function down(): void
    {
        Schema::dropIfExists('sales_invoice_items');
    }
};
