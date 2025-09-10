<?php
// database/migrations/xxxx_create_payments_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->enum('type', ['customer','supplier']); // payment type
            $table->unsignedBigInteger('party_id'); // buyer_id or supplier_id
            $table->unsignedBigInteger('invoice_id')->nullable(); // optional link to sales_invoices/purchases
            $table->string('invoice_no')->nullable();
            $table->date('payment_date');
            $table->decimal('amount', 15, 2);
            $table->enum('method', ['cash','bank','credit'])->default('cash');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['type','party_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
