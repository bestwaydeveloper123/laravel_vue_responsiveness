<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJunkyardAddressesTable extends Migration
{
  public function up()
  {
    Schema::create('junkyard_addresses', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedBigInteger('vendor_id');
      $table->string('shopname')->nullable();
      $table->string('street1')->nullable();
      $table->string('street2')->nullable();
      $table->string('city')->nullable();
      $table->string('state')->nullable();
      $table->string('zipcode')->nullable();
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('junkyard_addresses');
  }
}
