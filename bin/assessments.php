<?php

include 'base.php';

$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","analysis","assessment","category","criteria","rationale"];
fputcsv($csv,$fields);

$taxons = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        if($doc->taxonomicStatus == 'accepted') {
            $taxons[$doc->scientificNameWithoutAuthorship] =
                arraY("family"=>$doc->family,
                    "name"=>$doc->scientificNameWithoutAuthorship,
                    "author"=>$doc->scientificNameAuthorship,
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
        if(isset($doc->rationale)) {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['rationale'] = str_replace('?','', strip_tags($doc->rationale) );
        }
        if(isset($doc->category)) {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['category'] = $doc->category;
        }
        if(isset($doc->criteria)) {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['criteria'] = $doc->criteria;
        }
    }
}

foreach($taxons as $taxon) {
  $data=[
    $taxon["family"],$taxon["name"],$taxon["author"],$taxon["analysis"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["rationale"]
  ];
  fputcsv($csv,$data);
}

