<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRefundedToTrackingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_statuses', function (Blueprint $table) {
            $table->string('refunded')->after('returnShippingCost')->nullable();
            $table->dateTime('label_creation_date')->after('refunded')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_statuses', function (Blueprint $table) {
            $table->dropColumn('refunded');
            $table->dropColumn('label_creation_date');
        });
    }
}
