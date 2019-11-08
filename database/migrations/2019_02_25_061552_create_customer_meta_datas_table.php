<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomermetadatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_meta_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->nullable();
            $table->string('shipto')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            // $table->string('shippinginfo')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('email2')->nullable();
            $table->string('level')->nullable();
            $table->string('shopname')->nullable();
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
        Schema::dropIfExists('customermetadatas');
    }
}
