<?php

namespace cncflora\reports ;

class Groups {
  public $title = "Grupos taxonomicos";
  public $description = "Lista com todas os grupos taxonômicas. Demora bastante para gerar.";
  public $is_private = false;
  public $fields = ["grupo","família", "espécie"];
  public $filters = ['checklist'];

  function run($csv,$checklist) {
    fputcsv($csv,$this->fields);

    $url_base = 'http://cncflora.jbrj.gov.br:80/floradata/api/v1/species?family=';

    $repo = new \cncflora\repository\Taxon($checklist);
    $families = $repo->listFamilies();
    foreach($families as $family){
      $species = $repo->listFamily($family);

      $floras = json_decode(file_get_contents($url_base.rawurlencode($family)));

      $field = 'scientificNameWithoutAuthorship';
      $found = [];
      foreach($floras->result as $f) {
        $found[$f->$field] = $f;
      }
      foreach($species as $specie) {
        $flora=null;
        if(isset($found[$specie[$field]])) {
          $flora = $found[$specie[$field]];
        }

        if($flora==null) {
          fputcsv($csv,[
            "N/A"
            ,$specie['family']
            ,$specie['scientificNameWithoutAuthorship']
            ]);
        } else {
          fputcsv($csv,[
            explode(';',$flora->higherClassification)[1]
            ,$specie['family']
            ,$specie['scientificNameWithoutAuthorship']
            ]);
        }

      }
    }
  }
}

