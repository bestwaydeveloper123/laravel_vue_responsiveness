<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChryslerSurveyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chrysler_surveys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('controller')->nullable();
            $table->string('module_type')->nullable();
            $table->string('hardware_pn')->nullable();
            $table->string('software_pn')->nullable();
            $table->string('older_software_pn')->nullable();
            $table->integer('year')->nullable();
            $table->string('body')->nullable();
            $table->string('engine')->nullable();
            $table->string('software_description')->nullable();
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
        Schema::dropIfExists('chrysler_survey');
    }
}
