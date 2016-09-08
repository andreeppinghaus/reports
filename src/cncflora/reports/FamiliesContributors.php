<?php

namespace cncflora\reports ;

class FamiliesContributors {

  public $title = "Contribuidores das Famílias";
  public $description = "Lista com os colaboradores por família.";
  public $is_private = true;
  public $filters = ["checklist","family","species"];
  public $fields = ['familia aceita','nome aceito'];
  public $fields_array = array(
    "family" => "Família",
    "profile.metadata.creator" => "Analistas do perfil (ANALYST)",
    "assessment.evaluator" => "Validadores (VALIDATOR)",
    "occurrence.validation.by" => "Validadores das ocorrências",
    "georeferencedBy" => "Analistas SIG",
    "assessment.assessor" => "Avaliadores (ASSESSOR)"
  );


  public function __construct() {
    $this->fields = array_merge($this->fields, array_keys($this->fields_array));
  }

  function run($csv,$checklist,$family=null,$specie=null) {
    fputcsv($csv,$this->fields, ';');

    $repoOcc = new \cncflora\repository\Occurrences($checklist);
    $repoTaxon = new \cncflora\repository\Taxon($checklist);

    $repo=new \cncflora\repository\Assessment($checklist);
    
    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    $got=[];
    foreach($families as $f) {
      if($specie==null) {
        $spps = $repoTaxon->listFamily($f);
      } else {
        $spps = [$repoTaxon->getSpecie($specie)];
      }
      $assessments=$repo->listFamily($family);
      error_log(print_r($assessments, TRUE));
      $occs  = $repoOcc->listByFamily($f,false);
      //error_log(print_r($occs, TRUE));
      foreach($spps as $spp) {
        $names = $repoTaxon->listNames($spp['scientificNameWithoutAuthorship']);

        foreach($occs as $occ) {
          $id = $occ['occurrenceID'];
          if(isset($got[$id])) {
            continue;
          }
          $got[$id]=true;
          $data  = [$f,$spp['scientificNameWithoutAuthorship']];
          foreach($this->fields_array as $k=>$n) {
            if(!isset($occ[$k])) $occ[$k]='';
            if($k == "concatCollectionCode_CatalogNumber")
                $occ[$k] = $occ['collectionCode'] . $occ['catalogNumber'];
            if($checklist=='livro_vermelho_2013') {
              $data[] = utf8_decode($occ[$k]);
            } else {
              $data[] = $occ[$k];
            }
          }
          fputcsv($csv,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
        }
      }
    }

  }

}
