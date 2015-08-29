<?php

function fireEventAsFlatArray($event){
    $entries = array();
    foreach (Event::fire($event) as $k => $v) {
        if (is_array($v)) {
            foreach($v as $kk =>$vv) {
                $entries[$kk] = $vv;
            }
        }
        else $entries[$k] = $v;
    }
    $entries = array_unique($entries);
    return $entries;
}