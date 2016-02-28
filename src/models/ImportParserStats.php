<?php


class ImportParserStats extends \Illuminate\Database\Eloquent\Model {

    protected $connection = 'affilimport';
	protected $table = 'parser_stats';
	public $timestamps = false;

    protected $fillable = ['merchant_id','feed_id','start_time', 'end_time'];

    public function merchant() {
        return $this->hasOne('Merchant', 'id', 'merchant_id');
    }

    public function feed() {
        return $this->hasOne('Feed', 'id', 'feed_id');
    }

}