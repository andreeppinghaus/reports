<?php

define("COUCHDB","http://cncflora.jbrj.gov.br/datahub/plantas_raras_cerrado");

if(!file_Exists("all.json")) {
    $all_str = file_get_contents(COUCHDB."/_all_docs?include_docs=true") ;
    file_put_contents("all.json",$all_str);
}

$all = json_decode(file_get_contents("all.json"));

echo "\"Familia\";\"Nome\";\"Analise\";\"Avaliação\";\"Categoria\";\"Criterio\"\n";

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
    echo "\"".$taxon["family"]."\";\"".$taxon["name"]."\";\"".$taxon["analysis"]."\";\"".$taxon["assessment"]."\";\"".$taxon["category"]."\";\"".$taxon["criteria"]."\"\n";
}

