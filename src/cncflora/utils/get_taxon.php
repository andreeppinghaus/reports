<?php

include_once 'config.php';
include_once 'http.php';

function get_taxon_accepted($es, $db) {
    $hits = search($es,$db,"taxon","taxonomicStatus:\"accepted\"");

    $docs = [];

    foreach($hits as $hit){
        unset($hit->_rev);
        $docs[]=$hit;
    }

    return $docs;
}
function get_taxon_synonyms($es, $db, $spp) {

    $q = "taxonomicStatus:\"synonym\" AND (acceptedNameUsage:\"$spp*\" OR scientificName:\"$spp*\" OR scientificNameWithoutAuthorship: \"$spp*\")";
    $docs = [];

    $hits = search_post(ELASTICSEARCH,$db,'taxon',$q);
    foreach($hits as $hit){
        if ($hit->scientificNameWithoutAuthorship == $spp) {
            continue;
        }
        unset($hit->_rev);
        $docs[]=$hit;
    }

    return $docs;
}
?>
