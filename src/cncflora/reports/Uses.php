<?php

namespace cncflora\reports;

class Uses {
  public $title = "Utilização das espécies";
  public $description = "Lista contendo as utilizações dadas a cada espécie do recorte.";
  public $is_private = false;
  public $fields=['família','nome científico','uso','recurso','detalhes','referências'];
  public $filters =['checklist','family'];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo=new \cncflora\repository\Profiles($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      if(isset($d["uses"]) && is_array($d["uses"])) {
        foreach($d["uses"] as $t) {
          if(isset($t["use"])) {
            if(!isset($t['resource'])) $t['resource']='';
            if(!isset($t['details'])) $t['details']='';
            if(!isset($t['references'])) $t['references']=[];
            $data=[
               $d["taxon"]["family"]
              ,$d["taxon"]["scientificNameWithoutAuthorship"]
              ,$t["use"]
              ,$t["resource"]
              ,$t["details"]
              ,implode(";",$t["references"])
             ] ;
            fputcsv($csv,$data);
          }
        }
      }
    }
  }

}
