<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Feed extends Eloquent {

	protected $table = 'aff_feeds';
	public $timestamps = true;

	use SoftDeletingTrait;

	protected $dates = ['deleted_at'];
	protected $fillable = array(
        'merchant_id', 'format_id', 'is_downloaded', 'is_parsed', 'is_imported', 'name', 'feed_url',
        'sample', 'status', 'category_field', 'parser', 'target_category_model'
    );
//    protected $casts = [
//        'sample' => 'array',
//    ];

    public function getSampleAttribute($value) {
        return unserialize( base64_decode( $value ) );
    }

    public function setSampleAttribute($value) {
        $this->attributes['sample'] = base64_encode( serialize( $value ) );
    }

	public function merchant()
	{
		return $this->belongsTo('Merchant', 'merchant_id', 'id');
	}

    public function importCategories()
    {
        return $this->hasMany('ImportCategories', 'feed_id', 'id');
    }

	public function map()
	{
		return $this->hasMany('FeedMap', 'feed_id', 'id');
	}

	public function mapAsArray() {
        $map = $this->map()->lists('value', 'field');
        foreach($map as $k=>$v) {
            if (base64_decode($v, true)) {
                $data = @unserialize(base64_decode($v, true));
                if ($data !== false) {
                    $map[$k] = $data;
                }
                else $map[$k] = $v;
            }
            else $map[$k] = $v;
        }
        return $map;
    }

	public function format()
	{
		return $this->belongsTo('FeedFormat', 'format_id', 'id');
	}

    public function localFile() {
        return $save_to = app_path()."/feeds/{$this->id}.xml";
    }

    public function download($force=false) {
        if ($this->feed_url && ( !file_exists($this->localFile()) || time() - filemtime($this->localFile()) > 3600 || $force ) ) {
            $client = new \Bonweb\Laraffiliate\HttpClient();
            $client->get($this->feed_url, ['save_to' => $this->localFile()]);
        }
        return file_exists($this->localFile());
    }

    public function fields() {
        $fields = array();
        if ($this->sample) {
            foreach ($this->sample as $k => $v) {
                $fields[$k] = $k;
            }
        }
        return $fields;
    }

    public function scopeEnabled($query)
    {
        return $query->whereStatus('A');
    }

}