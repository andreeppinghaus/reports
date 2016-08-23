<?php

namespace cncflora\reports;

class Assessments{

  public $title = "Avaliações";
  public $description = "Lista com as avaliações de risco de extensão de cada espécie.";
  public $is_private = false;
  public $fields = ["familia","nome científico","autor","status no workflow","categoria","critério", "avaliador", "revisor", "justificativa", "data da avaliacao", "bioma"];
  public $filters=["checklist",'family'];

  function run($csv,$checklist,$family="") {
    fputcsv($csv,$this->fields, "\t");

    $repo=new \cncflora\repository\Assessment($checklist);

    if($family!=null) {
      $assessments=$repo->listFamily($family);
    } else {
      $assessments=$repo->listAll();
    }

    foreach($assessments as $doc) {
      $data=[];
      $data["family"] = $doc["taxon"]["family"];
      $data["name"]   = $doc["taxon"]["scientificNameWithoutAuthorship"];
      $data["author"] = $doc["taxon"]["scientificNameAuthorship"];
      $data['assessment'] = $doc["metadata"]["status"];
      if(isset($doc["category"])) {
        $data['category'] = $doc["category"];
      } else {
        $data['category'] = "";
      }
      if(isset($doc["criteria"])) {
        $data['criteria'] = str_replace(";", ",", $doc["criteria"]);
      } else {
        $data['criteria'] = "";
      }
      if(isset($doc["assessor"])) {
        $data['assessor'] = $doc["assessor"];
      } else {
        $data['assessor'] = "";
      }
      if(isset($doc["evaluator"])) {
        $data['evaluator'] = $doc["evaluator"];
      } else {
        $data['evaluator'] = "";
      }
      if(isset($doc["rationale"])) {
        $data['rationale'] = strip_tags(ltrim($doc["rationale"], " ?"));
      } else {
        $data['rationale'] = "";
      }
      $data['assessment_date'] = date('Y-m-d', $doc["metadata"]["modified"]);
      $data["bioma"] = "";

      /*
      if (array_key_exists($doc->taxon->scientificNameWithoutAuthorship, $taxons)){
        if(isset($doc->ecology) && isset($doc->ecology->biomas) && is_array($doc->ecology->biomas)) {
          $data['bioma'] = implode(", ", $doc->ecology->biomas);
        }
      }
       */

      $data=[
        $data["family"],
        $data["name"],
        $data["author"],
        $data["assessment"],
        $data["category"],
        $data["criteria"],
        $data["assessor"],
        $data["evaluator"],
        $data["rationale"],
        $data["assessment_date"],
        $data["bioma"]
      ];
      fputcsv($csv,$data, "\t");
    }
  }
}
