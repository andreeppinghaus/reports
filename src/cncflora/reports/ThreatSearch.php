<?php

namespace cncflora\reports;

class ThreatSearch{

  public $title = "ThreatSearch";
  public $description = "Relatório ThreatSearch";
  public $is_private = false;
  public $fields = ["id","SourceID","Family","Genus","Species","Author1", "InfraSpecRank", "InfraSpecName", "Author2", "Scope",
  "AssessmentYear", "ConsAssCategory", "ConAssCriteria", "Reference", "URL", "Comment", "EXCLUDE",
  "TPL_ID", "TPL_Family", "TPL_fullName", "TPL_Author", "TPL_taxonomicStatus", "TPL_acceptedNameID", "TPL_acceptedName",
  "TPL_acceptedNameAuthor", "TPL_acceptedNameTaxStatus"];
  public $filters=["checklist",'family'];

  function run($csv,$checklist,$family="") {
    fputcsv($csv,$this->fields, ';');

    $repo = new \cncflora\repository\Assessment($checklist);

    if($family!=null) {
      $assessments=$repo->listFamily($family);
    } else {
      $assessments=$repo->listAll();
    }

    foreach($assessments as $doc) {
      $data=[];
      $data["id"] = "";
      $data["SourceID"] = $doc["id"];
      $data["Family"] = $doc["taxon"]["family"];
      $nomes = explode(" ", $doc["taxon"]["scientificNameWithoutAuthorship"]);
      $data["Genus"] = $nomes[0];
      $data["Species"] = $nomes[1];

      $data["Author1"] = $doc["taxon"]["scientificNameAuthorship"];
      $data["InfraSpecRank"] = "";
      $data["InfraSpecName"] = "";
      $data["Author2"] = "";

      // if(strpos($doc["taxon"]["scientificNameAuthorship"], "&")){
      //   $authors = explode("&", $doc["taxon"]["scientificNameAuthorship"]);
      //   $data["Author1"] = trim($authors[0]);
      //   $data["InfraSpecRank"] = "";
      //   $data["InfraSpecName"] = "";
      //   $data["Author2"] = trim($authors[1]);
      // }
      // else{
      //   $data["Author1"] = $doc["taxon"]["scientificNameAuthorship"];
      //   $data["InfraSpecRank"] = "";
      //   $data["InfraSpecName"] = "";
      //   $data["Author2"] = "";
      // }

      $data["Scope"] = "";
      $data["AssessmentYear"] = date('Y', $doc["metadata"]["modified"]);//converter a data
      if($doc["category"] != null && isset($doc["category"]))
      $data["ConsAssCategory"] = $doc["category"];

      $data["ConsAssCriteria"] = "";
      if(isset($doc["criteria"]))
        $data["ConsAssCriteria"] = $doc["criteria"];

      $data["Reference"] = "";
      if(isset($doc["references"]) && !empty($doc["references"])){
        foreach ($doc["references"] as $reference) {
          $data["Reference"] .= "- " . $reference . " ";
        }
      }
      $data["URL"] = "http://cncflora.jbrj.gov.br/assessments/".$checklist."/assessment/".$doc["id"];

      $data["Comment"] = $doc["rationale"];
      $data["EXCLUDE"] = "";
      $data["TPL_ID"] = "";
      $data["TPL_Family"] = "";
      $data["TPL_fullName"] = "";
      $data["TPL_Author"] = "";
      $data["TPL_Author"] = "";
      $data["TPL_taxonomicStatus"] = "";
      $data["TPL_acceptedNameID"] = "";
      $data["TPL_acceptedName"] = "";
      $data["TPL_acceptedNameAuthor"] = "";
      $data["TPL_acceptedNameTaxStatus"] = "";

      fputcsv($csv,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
    }
  }
}
