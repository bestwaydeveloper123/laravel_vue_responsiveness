<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('account_id');
            $table->string('purchasedfrom')->nullable();
            $table->string('salespersonstockno')->nullable();
            $table->string('vin')->nullable();
            $table->string('pricepaid')->nullable();
            $table->string('partprice')->nullable();
            $table->string('shippingprice')->nullable();
            $table->string('pickupcharge')->nullable();
            $table->string('datepurchased')->nullable();
            $table->string('deliverydate')->nullable();
            $table->string('trackingnumber')->nullable();
            $table->string('creditcard')->nullable();
            $table->string('trackingcode')->nullable();
            $table->string('packagetype')->nullable();
            $table->string('rate')->nullable();
            $table->string('carrier')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendors');
    }
}
