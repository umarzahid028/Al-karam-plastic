<?php
// database/migrations/xxxx_add_paid_amount_and_status_to_purchases.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaidAmountAndStatusToPurchases extends Migration
{
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            if (!Schema::hasColumn('purchases','paid_amount')) {
                $table->decimal('paid_amount', 15, 2)->default(0)->after('total_amount');
            }
            if (!Schema::hasColumn('purchases','status')) {
                $table->enum('status',['pending','partial','paid'])->default('pending')->after('paid_amount');
            }
        });
    }

    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['paid_amount','status']);
        });
    }
}
