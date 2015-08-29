<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class ImportCategories extends Eloquent {

    protected $connection = 'affilimport';
	protected $table = 'categories';
	public $timestamps = false;

}