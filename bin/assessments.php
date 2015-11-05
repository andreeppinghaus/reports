<?php

global $title, $description, $is_private, $fields;
$title = "Avaliações";
$description = "Lista com as avaliações de risco de extensão de cada espécie.";
$is_private = false;
$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","assessment","category","criteria","assessor","evaluator","rationale"];
include 'base.php';

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
                    "assessment"=>"",
                    "assessor"=>"",
                    "evaluator"=>"",
                    "category"=>"",
                    "rationale"=>"",
                    "criteria"=>""
                );
        }
    }
}

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='assessment') {
        //Make sure specie still exists in the "recorte"
        if (array_key_exists($doc->taxon->scientificNameWithoutAuthorship, $taxons)){
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessment'] = $doc->metadata->status;
            if(isset($doc->category)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['category'] = $doc->category;
            }
            if(isset($doc->criteria)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['criteria'] = $doc->criteria;
            }
            if(isset($doc->assessor)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessor'] = $doc->assessor;
            }
            if(isset($doc->evaluator)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['evaluator'] = $doc->evaluator;
            }
            if(isset($doc->rationale)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['rationale'] = $doc->rationale;
            }
        }
    }
}
foreach($taxons as $taxon) {
  $data=[
    $taxon["family"],$taxon["name"],$taxon["author"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["assessor"],$taxon["evaluator"],$taxon["rationale"]
];
  fputcsv($csv,$data);
}
