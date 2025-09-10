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

            // foreign keys
            $table->unsignedBigInteger('issue_id');
            $table->foreign('issue_id')->references('id')->on('raw_material_issues')->onDelete('cascade');

            $table->unsignedBigInteger('rawpro_id');
            $table->foreign('rawpro_id')->references('id')->on('raw_materials')->onDelete('cascade');

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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('raw_material_issue_items');
        Schema::enableForeignKeyConstraints();
    }
    
};
