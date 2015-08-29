<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffFeedMapTable extends Migration {

	public function up()
	{
		Schema::create('aff_feed_map', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('feed_id');
			$table->string('from', 255);
			$table->string('to', 255);
		});
	}

	public function down()
	{
		Schema::drop('aff_feed_map');
	}
}