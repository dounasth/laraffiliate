<?php

namespace Bonweb\Laraffiliate\Parsers;

use Bonweb\Laracart\Product;
use Bonweb\Laracart\ProductData;
use Bonweb\Laradmin\Util;

class LinkwiseToLaracartProductParser {

    protected $feed;
    protected $ready_categories;
    protected $tempStorage;

    public $config = array(
        'initial-status' => 'A',
        'name-from' => 'PRODUCT_NAME',
        'seo-title-from' => 'PRODUCT_NAME',
        'seo-description-from' => 'PRODUCT_NAME',
        'seo-keywords-from' => 'PRODUCT_NAME',
        'tag-from' => 'CATEGORY',
        'tag-delimiters' => '',
        'meta-fields' => array(),
    );
    public $config_view = 'laraffiliate::parsers-configs.linkwise-product';

    public function __construct(\Feed $feed)
    {
        $this->feed = $feed;
        $this->config = array_merge($this->config, $feed->mapAsArray());
    }

    public function parseProducts(){
        set_time_limit(1800);
        ini_set('memory_limit', '1024M');
        header('Content-Type: text/html; charset=utf-8');

        niceprintr('starts parsing the feed\'s products');

        //  Get feed's mapped categories. Only their products will be imported
        $this->ready_categories = \ImportCategories::where('feed_id', '=', $this->feed->id)
            ->where('mapto_category_id', '>', 0)->lists('mapto_category_id', 'category');

        niceprintr($this->ready_categories);

        $ids = $this->feed->merchant->importedProducts->lists('product_id');
        Product::whereIn('id', $ids)->update(['status'=>'D']);

        $this->feed->download();

        require_once \Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
        MagicParser_parse($this->feed->localFile(), array($this, 'parseRow'), $this->feed->format->format);

        niceprintr('all parsed');
        \Cache::flush();
        Util::pingEngines();
        return 'all parsed';
    }

    public function parseRow($row) {
        if (    isset($row[$this->feed->category_field]) &&
                isset($row['IMAGE_URL']) && fn_is_not_empty($row['IMAGE_URL']) &&
                isset($row['PRICE']) && fn_is_not_empty($row['PRICE'])
        ) {
            if (array_key_exists($row[$this->feed->category_field], $this->ready_categories)) {

                $product = Product::withTrashed()->whereSku( $row['LW_PRODUCT_ID'] )->first();
                $data = new ProductData();

                if (!$product) {
                    $product = new Product();
                    $data->status = $product->status = $this->config['initial-status'];
                }
                else {
                    $data->status = $this->config['initial-status'];
                    $data->slug = $product->slug;
                }

                $data->affiliateUrl = $row['TRACKING_URL'];
                $data->title = $this->makeName($row);
                $data->sku = $row['LW_PRODUCT_ID'];
                $data->main_image = $row['IMAGE_URL'];
                $data->full_description = isset($row['DESCRIPTION']) ? $row['DESCRIPTION'] : '' ;
                $data->categories = array(
                    $this->ready_categories[$row[$this->feed->category_field]] => ['type' => 'M']
                );
                $data->tags = $this->makeTags($row);

                $data->price = $row['PRICE'];
                $data->list_price = isset($row['FULL_PRICE']) ? $row['FULL_PRICE'] : $row['PRICE'] ;

                $product->saveFromData($data);
                $product->saveMeta( $this->makeMeta($row) );

                $seo = $this->makeSeo($row);
                if ($product->seo) {
                    $product->seo->fill($seo)->save();
                }
                else {
                    $seo = \Bonweb\Laradmin\Seo::create($seo);
                    $product->seo()->save($seo);
                }

                $imp = \ImportProducts::whereProductId($product->id)->first();

                if (!$imp) {
                    $imp = new \ImportProducts();
                    $imp->merchant_id = $this->feed->merchant->id;
                    $imp->feed_id = $this->feed->id;
                }
                $imp->product_id = $product->id;
                $imp->row = $row;
                $imp->save();
            }
        }
    }

    public function previewProducts() {
        set_time_limit(120);
        ini_set('memory_limit', '1024M');
        header('Content-Type: text/html; charset=utf-8');

        //  Get feed's mapped categories. Only their products will be imported
        $this->ready_categories = \ImportCategories::where('feed_id', '=', $this->feed->id)
            ->where('mapto_category_id', '>', 0)->lists('mapto_category_id', 'category');

        $this->feed->download();
        require_once \Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
        MagicParser_parse($this->feed->localFile(), array($this, 'parsePreviews'), $this->feed->format->format);
    }

    public function parsePreviews($row) {
        if (isset($row[$this->feed->category_field]) && isset($row['IMAGE_URL']) && fn_is_not_empty($row['IMAGE_URL'])) {
            if (array_key_exists($row[$this->feed->category_field], $this->ready_categories)) {
                $html = "
                <tr>
                    <td><img src='{$row['IMAGE_URL']}' style='max-height: 100px;'/></td>
                    <td>{$row['LW_PRODUCT_ID']}</td>
                    <td>".$this->makeName($row)."<br/><br/>Tags: ".$this->makeTags($row)."</pre></td>
                    <td><pre>".print_r($this->makeMeta($row), true)."</pre></td>
                </tr>
                ";
                echo $html;
            }
        }
    }

    protected function makeName($row) {
        return $this->makeFromFields('name-from', $row);
    }

    protected function makeFromFields($config_field, $row, $joiner=' ') {
        $str = array();
        foreach (explode(',', $this->config[$config_field]) as $field) {
            $field = trim($field);
            if (isset($row[$field])) {
                $str[] = $row[$field];
            }
        }
        $str = implode($joiner, $str);
        return $str;
    }

    public function makeMeta($row) {
        $meta = array();
        foreach($this->config['meta-fields'] as $product_field => $feed_field) {
            if (isset($row[$feed_field])) {
                $meta[$product_field] = $row[$feed_field];
            }
        }
        return $meta;
    }

    public function makeSeo($row) {
        $seo = array(
            'title' =>  $this->makeFromFields('seo-title-from', $row),
            'description' => $this->makeFromFields('seo-description-from', $row),
            'keywords' => $this->makeFromFields('seo-keywords-from', $row),
        );
        $seo['keywords'] = str_ireplace(' ', ',', $seo['keywords']);
        return $seo;
    }

    public function makeTags($row) {
        $tags = $this->makeFromFields('tag-from', $row, ',');
        $delimiters = explode(',', $this->config['tag-delimiters']);
        foreach ($delimiters as $delimiter) {
            $tags = str_ireplace($delimiter, ',', $tags);
        }
        $tags = explode(',', $tags);
        $tags = array_filter($tags);
        $tags = array_map('trim', $tags);
        $tags = implode(',', $tags);
        return $tags;
    }

}

?>