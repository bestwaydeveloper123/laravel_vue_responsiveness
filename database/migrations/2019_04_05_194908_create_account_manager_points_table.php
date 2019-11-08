<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountManagerPointsTable extends Migration
{
  public function up()
  {
    Schema::create('account_manager_points', function (Blueprint $table) {
      $table->increments('id');
      $table->unsignedBigInteger('account_id');
      $table->string('user_name');
      $table->string('team');
      $table->string('type');
      $table->string('seller');
      $table->double('price_paid')->default(0);
      $table->double('part_price')->default(0);
      $table->double('shipping_price')->default(0);
      $table->double('pickup_charge')->default(0);
      $table->string('stock_number')->nullable();
      $table->unsignedInteger('multiplier')->default(1);
      $table->date('shipping_date')->nullable();
      $table->string('hardware')->nullable();
      $table->string('description')->nullable();
      $table->double('total')->default(0);
      $table->timestamps();
    });
  }

  public function down()
  {
    Schema::dropIfExists('account_manager_points');
  }
}
