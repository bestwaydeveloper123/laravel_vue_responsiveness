<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('part_information', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_purchased');
            $table->string('pcm_hw')->nullable();
            $table->string('computer_type')->nullable();
            $table->string('price_paid')->nullable();
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
        Schema::dropIfExists('part_information');
    }
}
