<?php


namespace cncflora\reports;

class Dispersion {
  public $title = "Síndromes de Dispersão";
  public $description = "Lista com as síndromes de dispersão por espécie.";
  public $is_private = false;
  public $fields = ['familia','nome científico','síndrome de dispersão'];
  public $filters =["checklist","family"];

  function run($csv,$checklist,$family="") {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      if(isset($d["reproduction"]) && isset($d["reproduction"]["dispersionSyndrome"]) && is_array($d["reproduction"]["dispersionSyndrome"])) {
        foreach($d["reproduction"]["dispersionSyndrome"] as $t) {
          $data = [ $d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],$t];
          fputcsv($csv,$data);
        }
      }
    }
  }
}
