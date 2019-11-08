<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingStatusesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tracking_statuses', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('account_id');
			$table->date('dateshippedtocustomer')->nullable();
			$table->date('deliverydatetocustomer')->nullable();
			$table->string('antitheft')->nullable();
			$table->string('customertrackingnumber')->nullable();
			$table->string('returntrackinginfo')->nullable();
			$table->string('warrantysticker')->nullable();
			$table->boolean('rma')->default(false);
			$table->boolean('warrantyexhausted')->default(false);
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
		Schema::dropIfExists('tracking_statuses');
	}
}
