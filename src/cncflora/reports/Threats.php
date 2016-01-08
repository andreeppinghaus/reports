<?php

namespace cncflora\reports;

class Threats {
  public $title = "Ameaças";
  public $description = "Lista com as ameaças por espécie.";
  public $is_private = false;
  public $fields = ["família","nome científico","ameaça","incidência","período","declínio","detalhes","referências"];
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
      if(isset($d["threats"]) && is_array($d["threats"])) {
        foreach($d["threats"] as $t) {
          if(isset($t["threat"])) {
            if(!isset($t["timing"])) $t['timing'] = [];
            if(!isset($t["decline"])) $t['decline'] = [];
            if(!isset($t["references"])) $t['references'] = [];
            if(!isset($t["incidence"])) $t['incidence'] = "";
            if(!isset($t["details"])) $t['details'] = "";
            $data = [
               $d["taxon"]["family"]
              ,$d["taxon"]["scientificNameWithoutAuthorship"]
              ,$t["threat"]
              ,$t["incidence"]
              ,implode(";",$t["timing"])
              ,implode(";",$t["decline"])
              ,$t["details"]
              ,implode(";",$t["references"])
            ];
            fputcsv($csv,$data);
          }
        }
      }
    }
  }

}
