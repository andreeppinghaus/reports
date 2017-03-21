<?php

namespace cncflora\reports ;

class ProfilesPerFamily {

  public $title = "Perfil das espécies por família";
  public $description = "Lista com os perfis das espécies por família.";
  public $is_private = false;
  public $fields = ["id","família","nome científico"];
  public $filters = ['checklist','family'];
  public $fields_array = array(
    "id" => "id do perfil",
    "family" => "Família do perfil",
    "scientificNameWithoutAuthorship" => "Nome científico"
  );

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields_array, ';');

    $repoTaxon = new \cncflora\repository\Taxon($checklist);
    $repoProf = new \cncflora\repository\Profiles($checklist);

    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    foreach($families as $f) {
      $profiles = $repoProf->listFamily($f);

      foreach($profiles as $prof) {
        $data = [
          $prof['id'],
          $prof['taxon']['family'],
          $prof['taxon']['scientificNameWithoutAuthorship']
          ];
        fputcsv($csv,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
      }
    }
  }
}
