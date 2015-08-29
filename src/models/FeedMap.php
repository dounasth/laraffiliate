<?php

class FeedMap extends Eloquent {

	protected $table = 'aff_feed_map';
	public $timestamps = false;
	protected $fillable = array('feed_id', 'field', 'value');

	public function feed()
	{
		return $this->belongsTo('Feed', 'id');
	}

}