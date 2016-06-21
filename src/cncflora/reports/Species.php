<?php

namespace cncflora\reports;

class Species {

  public $title = "Espécies";
  public $description = "Lista com todas as espécies do recorte.";
  public $is_private = false;
  public $fields = ["família","nome científico","autor"];
  public $filters = ['checklist','family'];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo = new \cncflora\repository\Taxon($checklist);

    if($family != null) {
      $families = [$family];
    } else {
      $families = $repo->listFamilies();
    }

    foreach($families as $family) {
      $spps = $repo->listFamily($family);
      foreach($spps as $doc) {
        $data=[ strtoupper($doc["family"]) ,$doc["scientificNameWithoutAuthorship"] ,$doc["scientificNameAuthorship"] ];
        fputcsv($csv,$data);
      }
    }
  }

}
