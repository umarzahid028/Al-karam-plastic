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
        Schema::create('raw_material_issue_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('issue_id')->constrained('raw_material_issues')->onDelete('cascade');
            $table->foreignId('rawpro_id')->constrained('raw_materials')->onDelete('cascade');
            $table->decimal('qty', 10, 2);
            $table->string('unit');
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
