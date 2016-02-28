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
        if ($this->needsSlugging()) {
            $this->sluggify(true);
        }
        return parent::save($options);
    }

	public function feeds()
	{
		return $this->hasMany('Feed', 'merchant_id');
	}

    public function importedProducts() {
        return $this->hasMany('ImportProducts', 'merchant_id', 'id');
    }

    public function delete() {
        if ($this->forceDeleting) {
            $merchant = Merchant::withTrashed()->where('id','=',11)->first();
            foreach ($merchant->feeds as $feed) {
                foreach ($feed->importCategories as $impCats) {
                    $impCats->delete();
                }
                foreach ($feed->map as $feedMap) {
                    $feedMap->delete();
                }
                $feed->forceDelete();
            }
            foreach ($merchant->importedProducts as $impProd) {
                $impProd->product->forceDelete();
                $impProd->delete();
            }
        }
        else {

        }
        parent::delete();
    }

    public function scopeEnabled($query)
    {
        return $query->whereStatus('A');
    }

    /*public function network()
    {
        return $this->belongsTo('AffiliateNetwork');
    }*/

}