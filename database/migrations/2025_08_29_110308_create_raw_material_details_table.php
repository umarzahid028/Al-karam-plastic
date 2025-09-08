<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('raw_materials', function (Blueprint $table) {
            $table->id();
            $table->string('material_code')->unique();
            $table->string('material_name');
            $table->decimal('purchase_price', 10, 2); // 2 decimal points
            $table->string('unit');     // e.g. kg, liter
            $table->string('packing');  // e.g. bag, box
            $table->integer('stocks')->default(0);

            $table->foreignId('store_id')
                  ->constrained('stores')
                  ->onDelete('cascade'); // agar store delete ho to material bhi delete
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
