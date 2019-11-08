<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewFiledsToTrackingStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_statuses', function (Blueprint $table) { 
            $table->string('shippinglabel')->after('account_id')->nullable(); 
            $table->double('shippingrate')->after('shippinglabel')->default(0); 
            $table->string('shippingpackage')->after('shippingrate')->nullable(); 
            $table->string('pricefspaidforshipping')->after('shippingpackage')->nullable(); 
            $table->double('vendordatepurchased')->after('pricefspaidforshipping')->default(0); 
            $table->string('trackingcode')->after('vendordatepurchased')->nullable(); 
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
            $table->dropColumn('shippinglabel');
            $table->dropColumn('shippingrate');
            $table->dropColumn('shippingpackage');
            $table->dropColumn('pricefspaidforshipping');
            $table->dropColumn('vendordatepurchased');
            $table->dropColumn('trackingcode'); 
        });
    }
}
