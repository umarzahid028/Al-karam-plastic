<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_suppliers', function (Blueprint $table) {
            // city ko company_name ke baad rakhne ke liye after() use
            $table->string('city', 255)->nullable()->after('company_name');
        });
    }

    public function down(): void
    {
        Schema::table('raw_suppliers', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
};
