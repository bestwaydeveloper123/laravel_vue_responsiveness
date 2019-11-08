<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInTrackingStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_statuses', function ($table) {
            $table->string('direction')->after('account_id')->nullable();
            $table->date('returnShippingDate')->after('warrantyexhausted')->nullable();
            $table->boolean('unauthorized')->after('returnShippingDate')->nullable()->default(false);
            $table->boolean('customerProvideTracking')->after('unauthorized')->nullable()->default(false);
            $table->string('returnShippingCost')->after('customerProvideTracking')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_statuses', function ($table) {
            $table->dropColumn('direction');
            $table->dropColumn('returnShippingDate');
            $table->dropColumn('unauthorized');
            $table->dropColumn('customerProvideTracking');
            $table->dropColumn('returnShippingCost');
        });
    }
}
