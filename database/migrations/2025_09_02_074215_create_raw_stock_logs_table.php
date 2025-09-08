<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('raw_stock_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rawpro_id');
            $table->enum('trans_type', ['in', 'out']);
            $table->decimal('qty', 15, 2);
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->date('trans_date');
            $table->timestamps();

            $table->foreign('rawpro_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_stock_logs');
    }
};
