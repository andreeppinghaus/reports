<?php

namespace cncflora\reports;

class Pollination {

  public $title = "Síndromes de Polinização";
  public $description = "Lista com as síndromes de polinização por espécie.";
  public $is_private = false;
  public $fields = ["familia","nome científico","síndrome de polinização"];
  public $filters = ['checklist','family'];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      if(isset($d["reproduction"]) && isset($d["reproduction"]["pollinationSyndrome"]) && is_array($d["reproduction"]["pollinationSyndrome"])) {
        foreach($d["reproduction"]["pollinationSyndrome"] as $t) {
          $data=[ $d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],$t ];
          fputcsv($csv,$data);
        }
      }
    }
  }

}
