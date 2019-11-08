<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_trackings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->nullable();
            $table->boolean('refunded')->default(0);
            $table->integer('spoke_to')->nullable();
            $table->boolean('confirmation')->default(0);
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
        Schema::dropIfExists('vendor_trackings');
    }
}
