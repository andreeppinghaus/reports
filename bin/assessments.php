<?php

global $title, $description, $is_private, $fields;
$title = "Avaliações";
$description = "Lista com as avaliações de risco de extensão de cada espécie.";
$is_private = false;
//$fields = ["familia","especie","especie com autor","assessment","categoria","criteria", "avaliador", "revisor", "rationale", "data da avaliacao", "link"];
// Field translation
$fields = ["familia","nome científico","autor","status no workflow","categoria","critério", "avaliador", "revisor", "justificativa", "data da avaliacao"];
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
                    "criteria"=>"",
                    "assessment_date"=>""
                    //"assessment_date"=>"",
                    //"link"=>""
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
            $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessment_date'] = date('Y-m-d', $doc->metadata->modified);
            //$taxons[$doc->taxon->scientificNameWithoutAuthorship]['link'] = 'http://cncflora.jbrj.gov.br/portal/pt-br/profile/'.$doc->taxon->scientificNameWithoutAuthorship;
        }
    }
}
foreach($taxons as $taxon) {
  $data=[
    $taxon["family"],$taxon["name"],$taxon["author"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["assessor"],$taxon["evaluator"],$taxon["rationale"], $taxon["assessment_date"]
    //$taxon["family"],$taxon["name"],$taxon["author"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["assessor"],$taxon["evaluator"],$taxon["rationale"], $taxon["assessment_date"], $taxon["link"]
];
  fputcsv($csv,$data);
}
