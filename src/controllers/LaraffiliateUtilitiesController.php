<?php
/**
 * Created by PhpStorm.
 * User: nimda
 * Date: 1/22/16
 * Time: 5:14 PM
 */

use \Bonweb\Laracart\Category;

class LaraffiliateUtilitiesController extends \LaradminBaseController {

    public function linkwiseMerchants() {
        $input = Input::all();
        if (fn_is_not_empty($input)) {
            if (isset($input['categories']) && fn_is_not_empty($input['categories'])) {
                $input['categories'] = implode(',', array_flatten($input['categories']));
            }
        }
        else {
            $input['has_datafeed'] = 1;
        }
        $input['format'] = 'json';
        $input['joined'] = 'all';
        $input['lang'] = 'gr';
        $input['program_status'] = 'active';

        $merchants = Cache::remember('linkwise-networks-'.md5(serialize($input)), 60, function() use ($input) {
            $client = new \Bonweb\Laraffiliate\HttpClient();
            $res = $client->get("https://affiliate.linkwi.se/en/rest_programs.html?".http_build_query($input), [
                'auth' => ['CD14098_API', '6r4WlBpPmYsxTcyBPmkG']
            ]);
            if (fn_is_not_empty($res->json())) {
                return $res->json();
            }
            else return false;
        });
        return View::make('laraffiliate::utilities.linkwise.merchants')->withMerchants( $merchants );
    }

    public function linkiseMerchantAdd($mid){
        $data = Cache::remember('linkwise-merchant-'.$mid, 60, function() use ($mid) {
            $client = new \Bonweb\Laraffiliate\HttpClient();
            $res = $client->get("https://affiliate.linkwi.se/en/rest_programs.html?joined=yes&format=json&p_id=$mid", [
                'auth' => ['CD14098_API', '6r4WlBpPmYsxTcyBPmkG']
            ]);
            if (fn_is_not_empty($res->json())) {
                return $res->json();
            }
            else return false;
        });

        $data = reset($data);

        if ($data) {
            $merchant = Merchant::where('network_campaign_id', '=', $mid)->first();
            $merchant = ($merchant) ? $merchant : new Merchant() ;
            $merchant->touch();
            $merchant->network_campaign_id = $data['id'];
            $merchant->name = $data['name'];
            $merchant->title = $data['name'];
            $merchant->description = $data['short_description'].$data['description'];
            $merchant->logo_url = $data['name'];
            $merchant->merchant_url = "http://go.linkwi.se/z/{$mid}-0/CD14098/?lnkurl=".urlencode($data['url']);
            $merchant->status = 'A';
            $merchant->save();
            $message = AlertMessage::success('Merchant saved');
        }
        else $message = AlertMessage::error(('Merchant not found'));

        return Redirect::to($_SERVER['HTTP_REFERER'])->withMessage($message);
    }

    public function skroutzCategories() {
        $categories = ImportSkroutzCategories::tree();
        return View::make('laraffiliate::utilities.skroutz-categories', compact('categories'));
    }

    public function skroutzCategoriesImport($parent_id) {
        $categories = ImportSkroutzCategories::tree($parent_id, false);
        $this->loopAndSave($categories);
        return Redirect::to($_SERVER['HTTP_REFERER'])->withMessage( AlertMessage::success('Categories imported') );
    }

    public function googleCategories() {
        $categories = $this->makeGoogleCatsArray();
        return View::make('laraffiliate::utilities.google-categories', compact('categories'));
    }

    public function googleCategoriesImport($parent) {
        $categories = $this->makeGoogleCatsArray();
        $nc = Category::findBySlug('imported');
        $parentId = (isset($nc->id) && $nc->id) ? $nc->id : 0 ;
        $this->loopAndSaveDotted($categories[$parent], $parentId);
        return Redirect::to($_SERVER['HTTP_REFERER'])->withMessage( AlertMessage::success('Categories imported') );
    }

    protected function loopAndSave($categories) {
        foreach ($categories as $category) {
            $sub = Category::find($category['sub']);
            $sub = ($sub) ? $sub->id : 0 ;
            $nc = new Category();
            $nc->id = $category['id'];
            $nc->parent_id = $sub;
            $nc->title = $category['title'];
            $nc->slug = $category['name'];
            $nc->status = 'A';
            $nc->save();
            if (fn_is_not_empty($category['subsub'])) {
                $this->loopAndSave($category['subsub']);
            }
        }
    }

    protected function loopAndSaveDotted($categories, $parent=0) {
        foreach ($categories as $category => $subcats) {
            $nc = new Category();
            $nc->parent_id = $parent;
            $nc->title = $category;
            $nc->status = 'A';
            $nc->save();
            if (fn_is_not_empty($subcats) && is_array($subcats) && fn_is_not_empty($nc->id)) {
                $this->loopAndSaveDotted($subcats, $nc->id);
            }
        }
    }

    protected function makeGoogleCatsArray() {
        $data = file_get_contents(public_path().'/taxonomy.en-US.txt');
        $data = str_ireplace('&', 'and', $data);
        $data = str_ireplace(' > ', '.', $data);
        $data = explode("\n", $data);
        array_shift($data);
        $categories = [];
        foreach ($data as $row) {
            if (fn_is_not_empty($row)) array_set($categories, $row, 1);
        }
        return $categories;
    }

}