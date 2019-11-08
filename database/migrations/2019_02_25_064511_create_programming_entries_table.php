<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgrammingentriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programming_entries', function (Blueprint $table) {
            $table->increments('id');

            $table->string('account_id');
            $table->string('entrytype')->nullable();
            $table->string('salespersonstockno')->nullable();
            $table->string('frdkeys')->nullable();
            $table->string('chyskimreset')->nullable();
            $table->string('chydonorinfo')->nullable();
            $table->string('chydonorinfo2')->nullable();
            $table->string('chycustinfo')->nullable();
            $table->string('chycustinfo2')->nullable();
            $table->boolean('general')->default(false);
            $table->boolean('doa')->default(false);
            $table->boolean('wronghw')->default(false);
            $table->boolean('needsw')->default(false);
            $table->boolean('needswpn')->default(false);
            $table->boolean('needcustvin')->default(false);
            $table->boolean('vendorsentincorrect')->default(false);
            $table->boolean('needonboardtesting')->default(false);
            $table->text('description')->nullable();
            $table->string('correcthwneeded')->nullable();
            $table->text('programmingnotes')->nullable();
            $table->string('username')->nullable();
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
        Schema::dropIfExists('programming_entries');
    }
}
