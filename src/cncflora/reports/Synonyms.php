<?php

namespace cncflora\reports;

class Synonyms {
public $title = "Sinônimos";
public $description = "Lista com os sinônimos de todas as espécies e os respectivos nomes aceitos.";
public $is_private = false;
public $fields = ["família","sinônimo(s)","autor","nome aceito"];
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

        $name = trim($spp['scientificNameWithoutAuthorship']);
        $flora = json_decode(file_get_contents(FLORADATA."/api/v1/specie?scientificName=".rawurlencode($name)))->result;

        foreach($syns as $s) {
          if($flora->scientificNameWithoutAuthorship != $s["acceptedNameUsage"]){
            $data = [
              $s["family"]
              ,$s["acceptedNameUsage"].", ".$s["scientificNameWithoutAuthorship"]
              ,$s["scientificNameAuthorship"]
              ,$flora->scientificNameWithoutAuthorship
              ,'alterado'
            ];
          }else{
            $data=[
             $s["family"]
            ,$s["scientificNameWithoutAuthorship"]
            ,$s["scientificNameAuthorship"]
            ,$s["acceptedNameUsage"]
            ];
          }
          fputcsv($csv,$data);
        }
      }
    }
  }

}
