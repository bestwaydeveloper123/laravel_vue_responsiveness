<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFordSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ford_surveys', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hardware')->nullable();
            $table->string('catch_word')->nullable();
            $table->string('part_number')->nullable();
            $table->string('model_year')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('engine')->nullable();
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
        Schema::dropIfExists('ford_survey');
    }
}
