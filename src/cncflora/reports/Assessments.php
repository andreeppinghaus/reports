<?php

// TODO: DO!

namespace cncflora\reports;

class Assessments{

  public $title = "Avaliações";
  public $description = "Lista com as avaliações de risco de extensão de cada espécie.";
  public $is_private = false;
  public $fields = ["familia","nome científico","autor","status no workflow","categoria","critério", "avaliador", "revisor", "justificativa", "data da avaliacao", "bioma"];
  public $filters=["checklist",'family'];

  function run($csv,$checklist,$family="") {
    fputcsv($csv,$this->fields);

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
                        "assessment_date"=>"",
                        "link"=>"",
                        "bioma" =>""
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
                    $taxons[$doc->taxon->scientificNameWithoutAuthorship]['criteria'] = str_replace(";", ",", $doc->criteria);
                }
                if(isset($doc->assessor)) {
                    $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessor'] = $doc->assessor;
                }
                if(isset($doc->evaluator)) {
                    $taxons[$doc->taxon->scientificNameWithoutAuthorship]['evaluator'] = $doc->evaluator;
                }
                if(isset($doc->rationale)) {
                    $taxons[$doc->taxon->scientificNameWithoutAuthorship]['rationale'] = strip_tags(ltrim($doc->rationale, " ?"));
                }
                $taxons[$doc->taxon->scientificNameWithoutAuthorship]['assessment_date'] = date('Y-m-d', $doc->metadata->modified);
                //$taxons[$doc->taxon->scientificNameWithoutAuthorship]['link'] = 'http://cncflora.jbrj.gov.br/portal/pt-br/profile/'.$doc->taxon->scientificNameWithoutAuthorship;
            }
        }
        if($doc->metadata->type=='profile') {
            if (array_key_exists($doc->taxon->scientificNameWithoutAuthorship, $taxons)){
                if(isset($doc->ecology) && isset($doc->ecology->biomas) && is_array($doc->ecology->biomas)) {
                    $taxons[$doc->taxon->scientificNameWithoutAuthorship]['bioma'] = implode(", ", $doc->ecology->biomas);
                }
            }
        }
    }

    foreach($taxons as $taxon) {
        if ($taxon["assessment"] == ""){
             continue;
        }
      $data=[
        $taxon["family"],$taxon["name"],$taxon["author"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["assessor"],$taxon["evaluator"],$taxon["rationale"], $taxon["assessment_date"],$taxon["bioma"]
        //$taxon["family"],$taxon["name"],$taxon["author"],$taxon["assessment"],$taxon["category"],$taxon["criteria"],$taxon["assessor"],$taxon["evaluator"],$taxon["rationale"], $taxon["assessment_date"],$taxon["link"],$taxon["bioma"]
    ];
      fputcsv($csv,$data);
    }
  }
}
