<?php


class ImportProducts extends \Illuminate\Database\Eloquent\Model {

    protected $connection = 'affilimport';
	protected $table = 'products';
	public $timestamps = false;

    protected $fillable = ['merchant_id','feed_id','product_id','row'];

    public function getRowAttribute($value)
    {
        return unserialize($value);
    }

    public function setRowAttribute($value)
    {
        $this->attributes['row'] = serialize($value);
    }

    public function merchant() {
        return $this->hasOne('Merchant', 'id', 'merchant_id');
    }

    public function feed() {
        return $this->hasOne('Feed', 'id', 'feed_id');
    }

    public function product() {
        return $this->hasOne('Bonweb\Laracart\Product', 'id', 'product_id');
    }

}