<?php


class ImportSkroutzCategories extends Eloquent {

    protected $connection = 'affilimport';
	protected $table = 'skroutz_categories';
	public $timestamps = false;

    public static function tree($parent_id = 0, $withParent = false) {
        $tree = self::whereSub($parent_id)->get()->toArray();
        foreach ($tree as $k => $cat) {
            $tree[$k]['subsub'] = self::tree($cat['id']);
        }

        if ($withParent) {
            $parent = self::find($parent_id)->toArray();
            $parent['subsub'] = $tree;
            return array($parent);
        }
        else return $tree;
    }

    public static function printTree($tree, $prefix='') {
        foreach ($tree as $cat) {
            echo $prefix . " " . '<a href="'. route('utilities.skroutz-categories.import', [$cat['id']]) .'">' .$cat['title'].'</a><br/>';
            if (fn_is_not_empty($cat['subsub'])) {
                self::printTree($cat['subsub'], $prefix.'|-- ');
            }
        }
    }

}