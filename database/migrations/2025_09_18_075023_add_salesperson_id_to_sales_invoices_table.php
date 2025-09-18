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
        Schema::table('sales_invoices', function (Blueprint $table) {
            // ✅ Add the salesperson_id column
            $table->unsignedBigInteger('salesperson_id')->nullable()->after('buyer_id');

            // (Optional) If you want a foreign key relationship with users table
            $table->foreign('salesperson_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null'); // or cascade if you prefer
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_invoices', function (Blueprint $table) {
            // Drop the foreign key first if it exists
            $table->dropForeign(['salesperson_id']);
            $table->dropColumn('salesperson_id');
        });
    }
};
