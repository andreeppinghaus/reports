<?php

namespace cncflora\reports ;

class SpeciesModified {

  public $title = "Espécies Modificadas";
  public $description = "Lista com as espécies modificadas de um recorte a partir de uma data especificada.";
  public $is_private = false;
  public $fields = ['familia','nome científico','data ultima modificação'];
  public $filters = [ "checklist",'family'];

  function run($csv,$checklist,$family=null,$occ_date) {
    fputcsv($csv,$this->fields, ';');

    $repoOcc = new \cncflora\repository\Occurrences($checklist);
    $repoTaxon = new \cncflora\repository\Taxon($checklist);

    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    $got=[];
    $spp_timestamp_ok = true;
    $last_modified = 0;
    $date_timestamp = strtotime($occ_date);
    foreach($families as $f) {
      $spps = $repoTaxon->listFamily($f);

      foreach($spps as $spp) {
        $names = $repoTaxon->listNames($spp['scientificNameWithoutAuthorship']);
        $occs  = $repoOcc->flatten($repoOcc->listOccurrences($names,false));
        foreach($occs as $occ) {
          $id = $occ['occurrenceID'];
          if(isset($got[$id])) {
            continue;
          }
          $got[$id]=true;

          if(isset($occ['metadata']['modified']) && $occ['metadata']['modified'] > $date_timestamp){
            $spp_timestamp_ok = false;
            break;
          }
          else if (isset($occ['metadata']['modified']) && $occ['metadata']['modified'] <= $date_timestamp){
            if ($last_modified < $occ['metadata']['modified'])
              $last_modified = $occ['metadata']['modified'];
          }
        }
        if($spp_timestamp_ok){
          $data  = [$f,$spp['scientificNameWithoutAuthorship'],date('d/m/Y', $last_modified)];
          fputcsv($csv,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
        }

      }
    }

  }

}
