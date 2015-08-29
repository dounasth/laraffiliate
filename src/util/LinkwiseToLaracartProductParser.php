<?php

namespace Bonweb\Laraffiliate\Parsers;

use Bonweb\Laracart\Product;
use Bonweb\Laracart\ProductData;

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
        'meta-fields' => array(),
    );
    public $config_view = 'laraffiliate::parsers-configs.linkwise-product';

    public function __construct(\Feed $feed)
    {
        $this->feed = $feed;
        $this->config = array_merge($this->config, $feed->mapAsArray());
    }

    public function parseProducts(){
        set_time_limit(120);
        ini_set('memory_limit', '512M');
        header('Content-Type: text/html; charset=utf-8');

        niceprintr('starts parsing the feed\'s products');

        //  Get feed's mapped categories. Only their products will be imported
        $this->ready_categories = \ImportCategories::where('feed_id', '=', $this->feed->id)
            ->where('mapto_category_id', '>', 0)->lists('mapto_category_id', 'category');

        niceprintr($this->ready_categories);

        $this->feed->download();
        require_once \Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
        MagicParser_parse($this->feed->localFile(), array($this, 'parseRow'), $this->feed->format->format);

        niceprintr('all parsed');
        exit;
    }

    public function parseRow($row) {
        if (isset($row[$this->feed->category_field])) {
            if (array_key_exists($row[$this->feed->category_field], $this->ready_categories)) {

                $product = Product::withTrashed()->whereSku( $row['LW_PRODUCT_ID'] )->first();
                $data = new ProductData();

                if (!$product) {
                    $product = new Product();
                    $data->status = $product->status = 'A';
                }
                else {
                    $data->status = $product->status;
                    $data->slug = $product->slug;
                }

                $data->title = $this->makeName($row);
                $data->sku = $row['LW_PRODUCT_ID'];
                $data->main_image = $row['IMAGE_URL'];
                $data->full_description = isset($row['DESCRIPTION']) ? $row['DESCRIPTION'] : '' ;
                $data->categories = array(
                    $this->ready_categories[$row[$this->feed->category_field]] => ['type' => 'M']
                );

                $data->price = $row['PRICE'];
                $data->list_price = isset($row['FULL_PRICE']) ? $row['FULL_PRICE'] : $row['PRICE'] ;

                $product->saveFromData($data);
                $product->saveMeta( $this->makeMeta($row) );

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
        ini_set('memory_limit', '512M');
        header('Content-Type: text/html; charset=utf-8');

        //  Get feed's mapped categories. Only their products will be imported
        $this->ready_categories = \ImportCategories::where('feed_id', '=', $this->feed->id)
            ->where('mapto_category_id', '>', 0)->lists('mapto_category_id', 'category');

        $this->feed->download();
        require_once \Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
        MagicParser_parse($this->feed->localFile(), array($this, 'parsePreviews'), $this->feed->format->format);
    }

    public function parsePreviews($row) {
        if (isset($row[$this->feed->category_field])) {
            if (array_key_exists($row[$this->feed->category_field], $this->ready_categories)) {
                $html = "
                <tr>
                    <td><img src='{$row['IMAGE_URL']}' style='max-height: 100px;'/></td>
                    <td>{$row['LW_PRODUCT_ID']}</td>
                    <td>".$this->makeName($row)."</td>
                    <td><pre>".print_r($this->makeMeta($row), true)."</pre></td>
                </tr>
                ";
                echo $html;
            }
        }
    }

    protected function makeName($row) {
        $name = array();
        foreach (explode(',', $this->config['name-from']) as $field) {
            $field = trim($field);
            if (isset($row[$field])) {
                $name[] = $row[$field];
            }
        }
        $name = implode(' ', $name);
        return $name;
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

}

?>