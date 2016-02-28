<?php

class LaraffiliateFeedController extends \LaradminBaseController
{

    protected $current_feed;
    protected $feed_fields = array();

    protected $tmpStorage = null;

    public function listAll() {
        return View::make('laraffiliate::feeds.list')->withFeeds( Feed::all() );
    }

    public function listTrashed() {
        return View::make('laraffiliate::feeds.list')->withFeeds( Feed::onlyTrashed()->get() );
    }

    public function update($id=0) {
        $feed = Feed::findOrNew($id);

        return View::make('laraffiliate::feeds.update')->withFeed( $feed )
            ->withMerchants(Merchant::lists('name', 'id'))
            ->withFormats(FeedFormat::lists('title', 'id'));
    }

    public function save($id=0) {
        if (Input::get('saveNew', 0)) {
            $feed = new Feed();
        }
        else {
            $feed = Feed::findOrNew($id);
        }
        $feed->fill(Input::get('feed', []));
        $feed->save();
        return Redirect::route('feed.update', [$feed->id])->withMessage( AlertMessage::success('Feed saved') );
    }

    public function delete($id) {
        $o = Feed::withTrashed()->where('id','=',$id)->first();
        if ($o->id) {
            $message = AlertMessage::success("Feed {$o->name} ({$o->id}) deleted");
            if ($o->trashed()) {
                $o->forceDelete();
            }
            else {
                $o->delete();
            }
            return Redirect::back()->withMessage( $message );
        }
        else {
            return Redirect::back()->withMessage( $message = AlertMessage::error("Feed for deletion not found") );
        }
    }

    public function productsSample($feed_id, $feed_category) {
        $feed_category = urldecode($feed_category);
        set_time_limit(120);
        $this->current_feed = Feed::findOrFail($feed_id);
        if ($this->current_feed->download()) {
            if ($this->current_feed->download()) {
                require_once Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
                $this->tmpStorage = array();
                $this->tmpStorage['counter'] = 0;
                $this->tmpStorage['sample'] = array();
                $this->tmpStorage['category'] = $feed_category;
                MagicParser_parse($this->current_feed->localFile(), array($this, 'gatherProductsSample'), $this->current_feed->format->format);
                return View::make('laraffiliate::feeds.products-sample')->withCategory($feed_category)->withSample($this->tmpStorage['sample']);
            }
        }
        exit;
    }

    public function gatherProductsSample($row){
        global $MagicParser_xml_done;
        if ($this->tmpStorage['counter'] < 12 ) {
            $cfield = $this->current_feed->category_field;
            if (isset($row[$cfield]) && $row[$cfield] == $this->tmpStorage['category']) {
                $this->tmpStorage['sample'][] = $row;
                $this->tmpStorage['counter'] += 1;
            }
        }
        else $MagicParser_xml_done = true;
        return;
    }

    public function refreshSample($id) {
        set_time_limit(120);
        $feed = Feed::findOrFail($id);
        if ($feed->feed_url) {
            if ($feed->download()) {
                require_once Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
                MagicParser_parse($feed->localFile(), array($this, 'parseSample'), $feed->format->format);
                $feed->sample = $this->feed_fields;
                $feed->save();
                return Redirect::back()->withMessage(AlertMessage::success("Feed sample updated."));
            }
            else return Redirect::back()->withMessage(AlertMessage::error("Feed could not be downloaded. Try again."));
        }
        else return Redirect::back()->withMessage(AlertMessage::error("Feed URL empty. Fill it in and try again."));
    }



    public function mapCategories($id){
        set_time_limit(120);
        $this->current_feed = Feed::findOrFail($id);
        if ($this->current_feed->download()) {
            require_once Config::get('laraffiliate::general.path') . '/util/MagicParser.php';
            $this->tmpStorage = array();
            MagicParser_parse($this->current_feed->localFile(), array($this, 'parseCategories'), $this->current_feed->format->format);
            foreach ($this->tmpStorage as $category => $v) {
                $ic = ImportCategories::where('category', '=', $category)->where('feed_id', '=', $this->current_feed->id)->first();
                if (!$ic) {
                    $ic = new ImportCategories();
                    $ic->feed_id = $this->current_feed->id;
                    $ic->category = $category;
                    $ic->mapto_category_id = 0;
                    $ic->save();
                }
            }
        }
        $feed = Feed::findOrFail($id);
        $categoryModelClass = new $feed->target_category_model;
        return View::make('laraffiliate::feeds.map-categories')
            ->withFeed( $feed )
            ->with('categoryModel', $categoryModelClass)
            ->withCategories( ImportCategories::where('feed_id', '=', $id)->get() );
    }

    public function parseFeed($id, $run=false) {
        $feed = Feed::findOrFail($id);
        $parser = new $feed->parser($feed);
        if ($run) {
            $parser->parseProducts();
            return Response::make('');
        }
        else {
            return View::make('laraffiliate::feeds.preview-products')->withParser($parser);
        }

    }

    public function mapFeedCat($feed_cat_id, $cat_id){
        $response = array();
        $response['status'] = false;

        $ic = ImportCategories::find($feed_cat_id);
        if ($ic) {
            $ic->mapto_category_id = $cat_id;
            $ic->save();
            $response['status'] = true;
        }

        $headers = array(
            'Content-type'=> 'application/json; charset=utf-8',
//            'Cache-Control' => 'max-age='.Config::get('api::general.jsonCacheControl'),
        );
        return Response::json($response, 200, $headers, JSON_UNESCAPED_UNICODE);
    }

    public function unmapFeedCat($feed_cat_id){
        $response = array();
        $response['status'] = false;

        $ic = ImportCategories::find($feed_cat_id);
        if ($ic) {
            $ic->mapto_category_id = 0;
            $ic->save();
            $response['status'] = true;
        }

        $headers = array(
            'Content-type'=> 'application/json; charset=utf-8',
//            'Cache-Control' => 'max-age='.Config::get('api::general.jsonCacheControl'),
        );
        return Response::json($response, 200, $headers, JSON_UNESCAPED_UNICODE);
    }

    public function mapFields($feed_id){
        $feed = Feed::findOrFail($feed_id);
        return View::make('laraffiliate::feeds.map-fields')->withFeed($feed);
    }

    public function saveMapFields($feed_id){
        $config = Input::get('config', array());

        foreach ($config as $field => $value) {
            $fm = FeedMap::where('feed_id', '=', $feed_id)->where('field', '=', $field)->first();
            if (!$fm) {
                $fm = new FeedMap();
                $fm->feed_id = $feed_id;
                $fm->field = $field;
            }
            if (is_array($value)) {
                $value = base64_encode(serialize($value));
            }
            $fm->value = $value;
            $fm->save();
        }
        return Redirect::back()->withMessage(AlertMessage::success('Configuration saved!'));
    }

    public function jsonFeedFields($id) {
        $feed = Feed::findOrFail($id);
        $headers = array(
            'Content-type'=> 'application/json; charset=utf-8',
//            'Cache-Control' => 'max-age='.Config::get('api::general.jsonCacheControl'),
        );
        return Response::json(array_keys($feed->sample), 200, $headers, JSON_UNESCAPED_UNICODE);
    }

    /* HELPERS */

    public function parseSample($row) {
//        if (fn_is_empty($this->feed_fields)){
            $this->feed_fields = array_merge($this->feed_fields, $row);
//        }
    }

    public function parseCategories($row) {
        $cfield = $this->current_feed->category_field;
        if (isset($row[$cfield]) && !array_key_exists($row[$cfield], $this->tmpStorage)) {
            $this->tmpStorage[$row[$cfield]] = 1;
        }
    }

    public function addCategory($feed_id, $feed_category) {
        $feed_category = urldecode($feed_category);
        $feed = Feed::findOrFail($feed_id);
        $categoryModelClass = new $feed->target_category_model;
        $categories = $categoryModelClass->sorted()->get()->toTree();
        return View::make('laraffiliate::feeds.make-category', compact('feed', 'feed_category', 'categories'));
        exit;
    }

    public function savePositionedCategory() {
        $feedid = Input::get('feedid', '');
        $feed = Feed::findOrFail($feedid);
        $category = new $feed->target_category_model;

        $name = Input::get('name', '');
        $seoname = Input::get('seoname', '');
        $pos = Input::get('pos', '');

        $sibling = Input::get('sibling', '');
        $neighbor = new $feed->target_category_model;
        $neighbor = $neighbor->findOrFail($sibling);

        $parent = Input::get('parent', '');

        $category->parent_id = $parent;
        $category->title = $name;
        $category->save();
        $seo = ['title' => $seoname];
        if ($category->seo) {
            $category->seo->fill($seo)->save();
        }
        else {
            $seo = \Bonweb\Laradmin\Seo::create($seo);
            $category->seo()->save($seo);
        }

        if ($neighbor) {
            if ($pos == 'before') {
                $category->before($neighbor)->save();
            }
            elseif ($pos == 'after') {
                $category->after($neighbor)->save();
            }
        }

        return Response::json($category->toArray());
//        return View::make('laraffiliate::feeds.make-category', compact('feed_category', 'categories'));
        exit;
    }
}

?>