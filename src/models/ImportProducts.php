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
}