<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTitleValuetoToApiOrderinsertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('api_orderinserts', function (Blueprint $table) {
            $table->string('title')->after('team')->nullable();
            $table->string('valueto')->after('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('api_orderinserts', function (Blueprint $table) {
            $table->dropColumn('title');
            $table->dropColumn('valueto');
        });
    }
}
