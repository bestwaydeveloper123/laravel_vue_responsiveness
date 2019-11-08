<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRmaFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rma_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_id')->nullable();
            $table->text('original_problem')->nullable(); 
            $table->text('steps_taken_to_diagnose_problem')->nullable(); 
            $table->text('problems_with_part')->nullable(); 
            $table->text('steps_taken_to_diagnose_with_part')->nullable(); 
            $table->text('additional_notes')->nullable(); 
            $table->string('customer_signature')->nullable(); 
            $table->string('customer_name')->nullable(); 
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
        Schema::dropIfExists('rma_forms');
    }
}
