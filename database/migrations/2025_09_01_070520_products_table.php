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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->string('product_group');
            $table->string('unit');
            $table->decimal('sale_price', 10, 2);
            $table->decimal('cost_price', 10, 2);
            $table->string('size')->nullable();
            $table->string('packing_sqr')->nullable();
            $table->integer('pieces_per_bundle')->default(0);
            $table->decimal('weight', 10, 2)->default(0);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
