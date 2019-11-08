<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSrnumberAndPaypalidToAccountManagerPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_manager_points', function (Blueprint $table) {
            $table->string('sr_number')->after('stock_number')->nullable();
            $table->string('paypal_id')->after('sr_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_manager_points', function (Blueprint $table) {
            $table->dropColumn('in_stock');
            $table->dropColumn('paypal_id');
        });
    }
}
