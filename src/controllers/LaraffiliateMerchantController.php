<?php

class LaraffiliateMerchantController extends \LaradminBaseController
{

    public function listAll() {
        return View::make('laraffiliate::merchants.list')->withMerchants( Merchant::all() );
    }

    public function listTrashed() {
        return View::make('laraffiliate::merchants.list')->withMerchants( Merchant::onlyTrashed()->get() );
    }

    public function update($id=0) {
        $merchant = Merchant::findOrNew($id);
        return View::make('laraffiliate::merchants.update')->withMerchant( $merchant );
    }

    public function save($id=0) {
        if (Input::get('saveNew', 0)) {
            $merchant = new Merchant();
        }
        else {
            $merchant = Merchant::findOrNew($id);
        }
        $merchant->fill(Input::get('merchant', []));
        $merchant->save();
        return Redirect::route('merchant.update', [$merchant->id])->withMessage( AlertMessage::success('Merchant saved') );
    }

    public function delete($id) {
        $o = Merchant::withTrashed()->where('id','=',$id)->first();
        if ($o->id) {
            $message = AlertMessage::success("Merchant {$o->name} ({$o->id}) deleted");
            if ($o->trashed()) {
                $o->forceDelete();
            }
            else {
                $o->delete();
            }
            return Redirect::back()->withMessage( $message );
        }
        else {
            return Redirect::back()->withMessage( $message = AlertMessage::error("Merchant for deletion not found") );
        }
    }

    public function restoreTrashed($id) {
        $o = Merchant::withTrashed()->where('id','=',$id)->first();
        $o->restore();
        $message = AlertMessage::success("Merchant {$o->name} ({$o->id}) restored");
        return Redirect::back()->withMessage( $message );
    }

}

?>