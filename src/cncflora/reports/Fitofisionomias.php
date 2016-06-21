<?php

namespace cncflora\reports;

class Fitofisionomias {

  public $title = "Fitofisionomias";
  public $description = "Lista com fitofisionomias por espécie.";
  public $is_private = false;
  public $fields=['familia','nome científico','fitofisionomia'];
  public $filters=["checklist","family"];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      if(isset($d["ecology"]) && isset($d["ecology"]["fitofisionomies"]) && is_array($d["ecology"]["fitofisionomies"])) {
        foreach($d["ecology"]["fitofisionomies"] as $t) {
          $data = [ $d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],$t];
          fputcsv($csv,$data);
        }
      }
    }
  }

}
