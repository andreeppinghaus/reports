<?php


//TODO: do!

global $title, $description, $is_private, $fields;
$title = "Status do Workflow";
$description = "Lista com o status do workflow dos perfis de espécies e as respectivas avaliações.";
$is_private = false;
//$fields = ["family","scientificNameWithoutAuthorship","scientificNameAuthorship","analysis","assessment","category","criteria"];
// Field translation
$fields = ["família","nome científico","autor","status na análise","status na avaliação","categoria","critério"];
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
                    "acceptedNameUsage"=>$doc->scientificName,
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
    //Skip taxon and occurrence
    if (($doc->metadata->type=='taxon') || ($doc->metadata->type=='occurrence')){
        continue;
    }
    //Make sure specie still exists in the "recorte"
    if (array_key_exists($doc->taxon->scientificNameWithoutAuthorship, $taxons)){
        if($doc->metadata->type=='profile') {
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['analysis'] = $doc->metadata->status;
        } else if($doc->metadata->type=='assessment') {
            if(isset($doc->metadata->status)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessment'] = $doc->metadata->status;
            }
            if(isset($doc->category)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['category'] = $doc->category;
            }
            if(isset($doc->criteria)) {
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['criteria'] = $doc->criteria;
            }
        }
    }
}

foreach($taxons as $taxon) {
  $data=[
    $taxon["family"],$taxon["name"],$taxon["author"],$taxon["analysis"],$taxon["assessment"],$taxon["category"],$taxon["criteria"]
  ];
  fputcsv($csv,$data);
}

