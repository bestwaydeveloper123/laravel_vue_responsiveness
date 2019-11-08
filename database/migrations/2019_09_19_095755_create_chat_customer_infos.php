<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatCustomerInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_customer_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('customer_id', 17);
            $table->string('department', 10);
            $table->string('name', 32)->nullable();
            $table->string('email', 32)->nullable();
            $table->string('phone', 16)->nullable();
            $table->string('question')->nullable();
            $table->string('vin', 16)->nullable();
            $table->string('status', 10)->default('opened');
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
        Schema::dropIfExists('chat_customer_info');
    }
}
