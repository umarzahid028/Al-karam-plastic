<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->date('invoice_date')->nullable()->after('id');
        $table->string('payment_method')->nullable()->after('invoice_date');
    });
    
}

public function down()
{
    Schema::table('purchases', function (Blueprint $table) {
        $table->dropColumn('payment_method');
    });
}

};
