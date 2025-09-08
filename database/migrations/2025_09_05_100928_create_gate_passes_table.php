<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('gate_passes', function (Blueprint $table) {
        $table->id();
        $table->string('gate_pass_no')->unique();
        $table->unsignedBigInteger('invoice_id');
        $table->unsignedBigInteger('user_id');
        $table->string('gate_name');
        $table->integer('qty');
        $table->timestamps();

        $table->foreign('invoice_id')->references('id')->on('sales_invoices')->onDelete('cascade');
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('gate_passes');
    }
};
