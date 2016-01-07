<?php

namespace cncflora\reports;

class Biomas {

  public $title = "Biomas";
  public $description = "Lista de biomas por espécie.";
  public $is_private = false;
  public $fields = ["familia","nome científico","bioma"];
  public $filters=["checklist","family"];

  function run($csv,$checklist="",$family=null,$specie=null) {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }
    foreach($profiles as $d) {
      if(isset($d["ecology"]) && isset($d["ecology"]["biomas"]) && is_array($d["ecology"]["biomas"])) {
        foreach($d["ecology"]["biomas"] as $t) {
          $data = [$d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],$t];
          fputcsv($csv,$data);
        }
      }
    }
  }
}
