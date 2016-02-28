<?php

/**
 * Created by PhpStorm.
 * User: nimda
 * Date: 1/13/16
 * Time: 1:29 PM
 */
class LaraffiliateSiteController extends \LaradminBaseController
{

    //http://affiliate.linkwi.se/feeds/v1.1/CD14098/columns-program_id,program_name,short_description,valid_from,end_date,coupon_code,is_coupon,title,descr,image_url,type,site_url,tracking_url,creative_id,category/catinc-0/catex-0/proginc-399,11450/progex-0/select-coupon/couponfeed.xml

    public function coupons()
    {
        $xml = Cache::remember("couponsXml", Config::get("laraffiliate::general.cache.offers"), function() {
            $merchant_ids = Merchant::enabled()->lists('network_campaign_id');
            $merchant_ids = implode(',', array_filter($merchant_ids));

            $client = new \Bonweb\Laraffiliate\HttpClient();
//        $res = $client->get('https://affiliate.linkwi.se/en/rest_programs.html?format=json&joined=all&scope=contains&lang=gr&program_status=active&has_datafeed=1&categories=111', [
//            'auth' => ['CD14098_API', '6r4WlBpPmYsxTcyBPmkG']
//        ]);
            $cfurl = "http://affiliate.linkwi.se/feeds/v1.1/CD14098/columns-program_id,program_name,short_description,valid_from,end_date,coupon_code,is_coupon,title,descr,image_url,type,site_url,tracking_url,creative_id,category/catinc-0/catex-0/proginc-{$merchant_ids}/progex-0/select-coupon/couponfeed.xml";
            $res = $client->get($cfurl);
            return $res->xml()->asXML();
        });
        $xml = new SimpleXMLElement($xml);
        return View::make('laraffiliate::site.coupons.list')->withXml($xml);
    }

    public function coupon($id)
    {
        $offer = Cache::remember("couponsXml-".$id, Config::get("laraffiliate::general.cache.offers"), function() use ($id) {
            $merchant_ids = Merchant::enabled()->lists('network_campaign_id');
            $merchant_ids = implode(',', array_filter($merchant_ids));

            $client = new \Bonweb\Laraffiliate\HttpClient();
            $cfurl = "http://affiliate.linkwi.se/feeds/v1.1/CD14098/columns-program_id,program_name,short_description,valid_from,end_date,coupon_code,is_coupon,title,descr,image_url,type,site_url,tracking_url,creative_id,category/catinc-0/catex-0/proginc-{$merchant_ids}/progex-0/select-coupon/couponfeed.xml";
            $res = $client->get($cfurl);

            $offer = $res->xml()->xpath('*[creative_id="'.$id.'"]');
            $offer = reset($offer);
            if ($offer) {
                $offer = $offer->asXml();
            }
            else {
                $offer = false;
            }
            return $offer;
        });

        $offer = ($offer) ? new SimpleXMLElement($offer) : false ;

        list($merchant, $products) = Cache::remember("couponsXml-".$id.'-additional', Config::get("laraffiliate::general.cache.offers"), function() use ($offer) {
            $merchant = false;
            $products = [];
            if ($offer) {
                $merchant = Merchant::enabled()->where('network_campaign_id', '=', $offer->program_id)->first();
                if ($merchant) {
                    $impProducts = $merchant->importedProducts()->take(20)->lists('product_id');
                    $products = \Bonweb\Laracart\Product::find($impProducts);
                }
            }
            return [$merchant, $products];
        });

        if (!$offer) {
            return Redirect::route('site.coupons.list', [], 301);
        }
        else {

            return View::make('laraffiliate::site.coupons.one')->withOffer($offer)->withMerchant($merchant)->withProducts($products);
        }
    }

}