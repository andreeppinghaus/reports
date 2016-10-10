<?php

namespace cncflora\reports;

class Synonyms {
public $title = "Sinônimos";
public $description = "Lista com os sinônimos de todas as espécies e os respectivos nomes aceitos.";
public $is_private = false;
public $fields = ["família","nome científico","autor","nome aceito"];
public $filters = ["checklist","family","species"];

  function run($csv,$checklist,$family=null,$specie=null) {
    fputcsv($csv,$this->fields);

    $repo = new \cncflora\repository\Taxon($checklist);

    if($family != null) {
      $families = [$family];
    } else {
      $families = $repo->listFamilies();
    }

    foreach($families as $f) {
      if($specie ==null) {
        $spps = $repo->listFamily($f);
      } else {
        $spps = [$repo->getSpecie($specie)];
      }
      foreach($spps as $spp) {
        $syns = $repo->listSynonyms($spp['scientificNameWithoutAuthorship']);

        foreach($syns as $s) {
            $data=[
             $s["family"]
            ,$s["scientificNameWithoutAuthorship"]
            ,$s["scientificNameAuthorship"]
            ,$s["acceptedNameUsage"]
            ];

          fputcsv($csv,$data);
        }
      }
    }
  }

}
