<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRealtimetrackingToTrackingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_statuses', function (Blueprint $table) {
            $table->string('realtimetracking')->after('trackingcode')->nullable();
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
            $table->dropColumn('realtimetracking');
        });
    }
}
