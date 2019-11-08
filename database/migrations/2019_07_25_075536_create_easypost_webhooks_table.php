<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEasypostWebhooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('easypost_webhooks', function (Blueprint $table) {
            $table->increments('id'); 
            $table->string('carrier')->nullable(); 
            $table->string('trackingcode')->nullable(); 
            $table->string('trackingdetails')->nullable(); 
            $table->string('description')->nullable(); 
            $table->string('pretransit')->nullable(); 
            $table->string('intransit')->nullable(); 
            $table->string('refunded')->nullable(); 
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
        Schema::dropIfExists('easypost_webhooks');
    }
}
