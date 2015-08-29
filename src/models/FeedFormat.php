<?php

class FeedFormat extends Eloquent {

    protected $table = 'aff_feed_formats';
    public $timestamps = false;
    protected $guarded = array('id');
    protected $fillable = array('title', 'format');

    public function feeds()
    {
        return $this->hasMany('Feed', 'format_id');
    }

}