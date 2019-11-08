<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_informations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->integer('stock_master_id');
            $table->tinyInteger('stock_lbo')->default(0);
			$table->string('created_by')->nullable();
			$table->string('modified_by')->nullable();
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
        Schema::dropIfExists('stock_informations');
    }
}
