<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMazdaSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mazda_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hardware')->nullable();
            $table->string('_18881_pn')->nullable();
            $table->string('catch')->nullable();
            $table->string('_12a650')->nullable();
            $table->string('epc_pn')->nullable();
            $table->string('mazda_model')->nullable();
            $table->string('year')->nullable();
            $table->string('engine')->nullable();
            $table->string('trans')->nullable();
            $table->string('emissions')->nullable();
            $table->string('sec')->nullable();
            $table->text('description')->nullable();
            $table->string('vin_ex')->nullable();
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
        Schema::dropIfExists('mazda_survey');
    }
}
