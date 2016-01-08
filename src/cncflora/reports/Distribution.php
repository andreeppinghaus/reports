<?php

namespace cncflora\reports;

class Distribution {

  public $title = "Distribuição";
  public $description = "Lista com endemismo e altitude por espécie.";
  public $is_private = false;
  public $fields = ['familia','nome científico','endemismo','altitude'];
  public $filters = [ "checklist",'family'];


  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }
    foreach($profiles as $d) {
      $data = [$d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],"",""];

      if(isset($d["distribution"]) && is_object($d["distribution"])) {
        $data[2] =$d["distribution"]["brasilianEndemic"];

        if(isset($d["distribution"]["altitude"])) {
          $a = $d["distribution"]["altitude"];
          if(isset($a["absolute"])) {
            $data[3] = $a["absolute"];
          } else if(isset($a["minimum"]) && isset($a["maximum"])) {
            $data[3] = $a["minimum"]."~".$a["maximum"];
          } else {
            $data[3] = $a["minimum"].$a["maximum"];
          }
        }
      }

      fputcsv($csv,$data);
    }
  }

}
