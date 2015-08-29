<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffFeedsTable extends Migration {

	public function up()
	{
		Schema::create('aff_feeds', function(Blueprint $table) {
			$table->increments('id');
			$table->bigInteger('merchant_id');
			$table->bigInteger('format_id');
			$table->tinyInteger('is_downloaded')->default('0');
			$table->tinyInteger('is_parsed')->default('0');
			$table->tinyInteger('is_imported')->default('0');
			$table->string('name', 255);
			$table->text('feed_url');
			$table->text('sample');
            $table->string('status', 1);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	public function down()
	{
		Schema::drop('aff_feeds');
	}
}