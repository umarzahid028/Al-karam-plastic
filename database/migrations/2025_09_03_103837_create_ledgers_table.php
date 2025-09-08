<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->id();
            $table->string('party_id');          // e.g. SP-003, CUST-001
            $table->string('party_type');        // customer, supplier, user
            $table->string('ref_type');          // purchase, sale, payment, invoice
            $table->string('invoice_no')->nullable();
            $table->date('invoice_date')->nullable();
            $table->text('description')->nullable();
            $table->decimal('debit', 12, 2)->default(0);
            $table->decimal('credit', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ledgers');
    }
};
