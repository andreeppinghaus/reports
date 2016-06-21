<?php

namespace cncflora\reports;

class Habitats {
  
  public $title = "Habitats";
  public $description = "Lista com habitats por espécie.";
  public $is_private = false;
  public $fields = ['familia','nome científico','habitat'];
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
      if(isset($d["ecology"]) && isset($d["ecology"]["habitats"]) && is_array($d["ecology"]["habitats"])) {
        foreach($d["ecology"]["habitats"] as $t) {
          $data=[ $d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],$t ];
          fputcsv($csv,$data);
        }
      }
    }
  }

}
