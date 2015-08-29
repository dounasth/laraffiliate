<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffMerchantsTable extends Migration {

	public function up()
	{
		Schema::create('aff_merchants', function(Blueprint $table) {
			$table->increments('id');
            $table->bigInteger('network_campaign_id');
            $table->string('name', 255);
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->text('description');
            $table->string('logo_url', 255);
            $table->string('merchant_url', 255);
            $table->string('city_street', 255);
            $table->string('rss_url', 255);
            $table->string('status', 1);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('aff_merchants');
	}
}