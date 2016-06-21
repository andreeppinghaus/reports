<?php

namespace cncflora\reports;

class Ecology {
  public $title = "Ecologia";
  public $description = "Lista com as características da ecologia por espécie.";
  public $is_private = false;
  public $fields = ['familia','nome científico','habito','fenologia','luminosidade','substrato','longevidade','rebroto'];
  public $filters = ['checklist','family'];

  function run($csv,$checklist,$family=null){
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      if(isset($d["ecology"]) && is_object($d["ecology"])) {
        foreach($d["ecology"] as $k=>$v) {
          if(is_array($v)) {
            $d["ecology"]->$k = implode(' ; ',$v);
          }
        }
        $data =[
          $d["taxon"]["family"]
          , $d["taxon"]["scientificNameWithoutAuthorship"]
          , $d["ecology"]["lifeForm"]
          , $d["ecology"]["fenology"]
          , $d["ecology"]["luminosity"]
          , $d["ecology"]["substratum"]
          , $d["ecology"]["longevity"]
          , $d["ecology"]["resprout"]
          ];
        fputcsv($csv,$data);
      }
    }
  }

}
