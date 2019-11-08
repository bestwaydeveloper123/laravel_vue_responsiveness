<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecondarymanagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('secondary_managers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account_id')->nullable();
            $table->string('secondary_manager')->nullable();
            $table->string('team')->nullable();
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
        Schema::dropIfExists('secondarymanagers');
    }
}
