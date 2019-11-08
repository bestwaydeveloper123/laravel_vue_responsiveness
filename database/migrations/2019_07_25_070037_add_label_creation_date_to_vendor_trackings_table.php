<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLabelCreationDateToVendorTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_trackings', function (Blueprint $table) { 
            $table->dateTime('label_creation_date')->after('confirmation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('vendor_trackings', function (Blueprint $table) { 
            $table->dropColumn('label_creation_date');
        });
    }
}