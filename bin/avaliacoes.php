<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"analysis\",\"assessment\",\"category\",\"criteria\"\n";

$taxons = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        if($doc->taxonomicStatus == 'accepted') {
            $taxons[$doc->scientificNameWithoutAuthorship] =
                arraY("family"=>$doc->family,
                    "name"=>$doc->scientificNameWithoutAuthorship,
                    "analysis"=>"",
                    "assessment"=>"",
                    "category"=>"",
                    "criteria"=>""
                );
        }
    }
}

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='profile') {
        $taxons[$doc->taxon->scientificNameWithoutAuthorship]['analysis'] = $doc->metadata->status;
    } else if($doc->metadata->type=='assessment') {
        $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessment'] = $doc->metadata->status;
        if(isset($doc->category)) {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['category'] = $doc->category;
        }
        if(isset($doc->criteria)) {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['criteria'] = $doc->criteria;
        }
    }
}

foreach($taxons as $taxon) {
    echo "\"".$taxon["family"]."\",\"".$taxon["name"]."\",\"".$taxon["analysis"]."\",\"".$taxon["assessment"]."\",\"".$taxon["category"]."\",\"".$taxon["criteria"]."\"\n";
}

