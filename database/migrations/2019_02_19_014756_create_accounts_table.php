<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function (Blueprint $table) {
			$table->increments('id');
			$table->string('accountname')->nullable();
			$table->string('accounthnumber')->nullable();
      $table->string('ordertype')->nullable();
      $table->string('trackingnumbertocustomer')->nullable();
			$table->string('accountteam')->nullable();
			$table->string('primaryaccountmanager')->nullable();
			$table->string('secondaryaccountmanager')->nullable();
			$table->string('whomadethesale')->nullable();
			$table->string('secondarysale')->nullable();
			$table->date('datecustomerpurchased')->nullable();
			$table->string('itempurchased')->nullable();
			$table->string('customervin')->nullable();
			$table->string('programmingdetails')->nullable();
			$table->string('pcmhardwaretype')->nullable();
			$table->string('pricepaid')->nullable();
			$table->string('adjustment')->nullable();
			$table->string('reason')->nullable();
			$table->string('resale_number')->nullable();
			$table->string('computertype')->nullable();
			$table->string('docusign')->nullable();
			$table->string('ebayusername')->nullable();
			$table->string('salesrecord')->nullable();
			$table->string('magento_id')->nullable();
			$table->date('requireddeliverydate')->nullable();
			$table->string('contestmultiplier')->nullable();
			$table->boolean('onedayhandling')->default(0);
			$table->boolean('sticker')->default(0);
			$table->boolean('fixplugorcase')->default(0);
			$table->boolean('changelabel')->default(0);
			$table->boolean('removebracket')->default(0);
			$table->boolean('wrongpart')->default(0);
			$table->boolean('prog_mods')->default(0);
			$table->string('shippinginstructions')->nullable();
			// $table->string('antitheft')->nullable();
			// $table->date('deliverydatetocustomer')->nullable();
			// $table->string('returntrackinginfo')->nullable();
			$table->boolean('onboard_testing')->default(0);
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
		Schema::dropIfExists('accounts');
	}
}
