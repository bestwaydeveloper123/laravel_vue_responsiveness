<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToProgrammingEntries extends Migration
{
  public function up()
  {
    Schema::table('programming_entries', function (Blueprint $table) {
      $table->boolean('wrongecuhw')->after('wronghw')->default(false);
      $table->boolean('wrongparttype')->after('wrongecuhw')->default(false);
      $table->boolean('purchaseinfomismatch')->after('wrongparttype')->default(false);
      $table->boolean('securitymismatch')->after('purchaseinfomismatch')->default(false);
      $table->boolean('badlydamagedunit')->after('securitymismatch')->default(false);
    });
  }

  public function down()
  {
    Schema::table('programming_entries', function (Blueprint $table) {
      $table->dropColumn('wrongecuhw');
      $table->dropColumn('wrongparttype');
      $table->dropColumn('purchaseinfomismatch');
      $table->dropColumn('securitymismatch');
      $table->dropColumn('badlydamagedunit');
    });
  }
}
