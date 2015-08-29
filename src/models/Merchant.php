<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;

class Merchant extends \Illuminate\Database\Eloquent\Model implements SluggableInterface {

    use SluggableTrait;

	protected $table = 'aff_merchants';
	public $timestamps = true;

	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];
	protected $guarded = array('id');
	protected $fillable = array('network_campaign_id', 'name', 'title', 'slug', 'description', 'logo_url', 'merchant_url', 'city_street', 'rss_url', 'status');

    protected $sluggable = array(
        'build_from' => 'title',
        'save_to'    => 'slug',
        'max_length' => null,
        'method' => ['GreekSlugGenerator','get_slug'],
        'separator' => '-',
        'unique' => true,
        'include_trashed' => false,
        'on_update' => false,
        'reserved' => null,
        'use_cache' => false,
    );

    public function save(array $options = array())
    {
        $this->sluggify(true);
        return parent::save($options);
    }

	public function feeds()
	{
		return $this->hasMany('Feed', 'merchant_id');
	}

    public function network()
    {
        return $this->belongsTo('AffiliateNetwork');
    }

}