<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('customer_invoice_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('customer_invoice_id');
        $table->unsignedBigInteger('product_id');
        $table->decimal('qty', 12, 2);
        $table->decimal('price', 12, 2);
        $table->decimal('total', 12, 2);
        $table->timestamps();

        $table->foreign('customer_invoice_id')->references('id')->on('customer_invoices')->onDelete('cascade');
        $table->foreign('product_id')->references('id')->on('raw_materials')->onDelete('cascade');
    });
}

public function down()
{
    Schema::dropIfExists('customer_invoice_items');
}

};
