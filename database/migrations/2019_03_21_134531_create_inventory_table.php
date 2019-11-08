<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->nullable();
            $table->string('hardware')->nullable();
            $table->string('software')->nullable();
            $table->string('xyzposition')->nullable();
            $table->string('gxxorlsa')->nullable();

            $table->string('survey')->nullable();
            $table->string('year')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('engine')->nullable();
            $table->string('body')->nullable();
            $table->string('trans')->nullable();
            $table->string('emissions')->nullable();
            $table->string('description')->nullable();

            $table->string('catchcode')->nullable();
            $table->string('partnumber')->nullable();

            $table->string('controller')->nullable();
            $table->string('moduletype')->nullable();
            $table->string('oldsoftware')->nullable();

            $table->string('_18881_pn')->nullable();
            $table->string('catch')->nullable();
            $table->string('_12a650')->nullable();
            $table->string('epc_pn')->nullable();
            $table->string('mazda_model')->nullable();
            $table->string('sec')->nullable();

            $table->string('publish')->nullable();
            $table->string('title')->nullable();
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
        Schema::dropIfExists('inventory');
    }
}
