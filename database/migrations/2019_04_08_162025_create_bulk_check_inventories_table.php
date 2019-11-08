<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBulkCheckInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bulk_check_inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->nullable();
            $table->string('salesrecord')->nullable();
            $table->string('team')->nullable();
            $table->string('pcmhardwaretype')->nullable();
            $table->string('stock')->nullable();
            $table->string('returned')->nullable();
            $table->string('xyzposition')->nullable();
            $table->string('stocknumber')->nullable();
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
        Schema::dropIfExists('bulk_check_inventories');
    }
}
