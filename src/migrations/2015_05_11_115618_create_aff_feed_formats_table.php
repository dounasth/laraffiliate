<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAffFeedFormatsTable extends Migration {

    public function up()
    {
        Schema::create('aff_feed_formats', function(Blueprint $table) {
            $table->increments('id');
            $table->string('title', 255);
            $table->string('format', 255);
        });
    }

    public function down()
    {
        Schema::drop('aff_feed_formats');
    }
}