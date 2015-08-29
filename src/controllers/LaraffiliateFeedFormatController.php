<?php

class LaraffiliateFeedFormatController extends \LaradminBaseController
{

    public function listAll() {
        return View::make('laraffiliate::feed_formats.list')->withFormats( FeedFormat::all() );
    }

    public function listTrashed() {
        return View::make('laraffiliate::feed_formats.list')->withFormats( FeedFormat::onlyTrashed()->get() );
    }

    public function update($id=0) {
        $format = FeedFormat::findOrNew($id);
        return View::make('laraffiliate::feed_formats.update')->withFormat( $format );
    }

    public function save($id=0) {
        if (Input::get('saveNew', 0)) {
            $format = new FeedFormat();
        }
        else {
            $format = FeedFormat::findOrNew($id);
        }
        $format->fill(Input::get('feed_format', []));
        $format->save();
        return Redirect::route('feed_format.update', [$format->id])->withMessage( AlertMessage::success('Feed Format saved') );
    }

    public function delete($id) {
        $o = FeedFormat::findOrNew($id);
        if ($o->id) {
            $message = AlertMessage::success("Feed Format {$o->title} ({$o->id}) deleted");
            $o->delete();
            return Redirect::back()->withMessage( $message );
        }
        else {
            return Redirect::back()->withMessage( $message = AlertMessage::error("FeedFormat for deletion not found") );
        }
    }

}

?>