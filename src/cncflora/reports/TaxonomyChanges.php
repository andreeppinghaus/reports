<?php

namespace cncflora\reports ;

class TaxonomyChanges {
  public $title = "Mudanças taxonômicas";
  public $description = "Lista com todas as mudanças taxonômicas. Demora bastante para gerar.";
  public $is_private = false;
  public $fields = ["família", "espécie", "mudança"];
  public $filters = ['checklist'];

  function run($csv,$checklist) {
    fputcsv($csv,$this->fields);

    $url_base = 'http://cncflora.jbrj.gov.br:80/floradata/api/v1/specie?scientificName=';
    // TODO: use query by family

    $repo = new \cncflora\repository\Taxon($checklist);
    $families = $repo->listFamilies();
    foreach($families as $family){
      $species = $repo->listFamily($family);
      foreach($species as $specie) {
        $flora = json_decode(file_get_Contents($url_base.rawurlencode($specie['scientificNameWithoutAuthorship'])));
        $synonyms = $repo->listSynonyms($specie['scientificNameWithoutAuthorship']);
        if(empty($flora->result)) {
          $data = [$family,$specie["scientificNameWithoutAuthorship"],'not_found'];
          fputcsv($csv,$data);
        } else {
          if($flora->result->scientificNameWithoutAuthorship != $specie["scientificNameWithoutAuthorship"]) {
            $data = [$family,$specie[ "scientificNameWithoutAuthorship" ],'is_synonym'];
            fputcsv($csv,$data);
          } else {
            foreach($flora->result->synonyms as $flora_syn) {
              $got=false;
              foreach($synonyms as $my_syn) {
                if($my_syn[ "scientificNameWithoutAuthorship" ] == $flora_syn->scientificNameWithoutAuthorship) {
                  $got=true;
                }
              }
              if(!$got) {
                $data = [$family,$specie[ "scientificNameWithoutAuthorship" ],'have_new_synonym'];
                fputcsv($csv,$data);
              }
            }
            foreach($synonyms as $my_syn) {
              $got=false;
              foreach($flora->result->synonyms as $flora_syn) {
                if($my_syn[ "scientificNameWithoutAuthorship" ] == $flora_syn->scientificNameWithoutAuthorship) {
                  $got=true;
                }
              }
              if(!$got) {
                $data = [$family,$specie[ "scientificNameWithoutAuthorship" ],'lost_synonym'];
                fputcsv($csv,$data);
              }
            }
          }
        }
      }
    }
  }
}
