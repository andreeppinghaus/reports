<?php

namespace cncflora\reports ;


class Sig {

  public $title = "Informações SIG";
  public $description = "Lista com as informações SIG por espécie.";
  public $is_private = true;
  public $filters = ['checklist','family','species'];
  public $fields = array('familia','nome aceito');
  public $fields_array = array(
      "occurrenceID" => "id da ocorrência",
      "institutionCode"=>"Cod. da inst.",
      "collectionCode"=>"Cod. da Col.",
      "year"=>"ano",
      "month"=>"mes",
      "day"=>"dia",
      "stateProvince"=>"Estado",
      "municipality"=>"municipio",
      "locality"=>"localidade",
      "decimalLatitude" => "latitude",
      "decimalLongitude" => "longitude",
      "georeferenceProtocol" => "geo protocolo",
      "georeferenceVerificationStatus" => "status SIG",
      "georeferencedBy" => "analista SIG",
      "coordinateUncertaintyInMeters" => "geo precisão",
      "valid" => "válido",
      "validation_taxonomy" => "taxonomia válida",
      "validation_cultivated" => "cultivada ex-situ",
      "validation_duplicated" => "registro de duplicata",
      "validation_native" => "nativa na localidade",
      "validation_georeference" => "georeferência válida",
      "year" => "ano da coleta",
      "contributor" => "colaboradores",
      "remarks" => "observações"
  );

  public function __construct() {
    $this->fields = array_merge($this->fields, array_values($this->fields_array) );
  }

  function run($csv,$checklist,$family=null,$specie=null) {
    fputcsv($csv,$this->fields);

    $repoOcc = new \cncflora\repository\Occurrences($checklist);
    $repoTaxon = new \cncflora\repository\Taxon($checklist);

    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    foreach($families as $f) {
      if($specie==null) {
        $spps = $repoTaxon->listFamily($f);
      } else {
        $spps = [$repoTaxon->getSpecie($specie)];
      }
      foreach($spps as $spp) {
        $names = $repoTaxon->listNames($spp['scientificNameWithoutAuthorship']);
        $occs  = $repoOcc->flatten($repoOcc->listOccurrences($names,false));
        foreach($occs as $occ) {
          $data  = [$f,$spp['scientificNameWithoutAuthorship']];
          foreach($this->fields_array as $k=>$n) {
            if(!isset($occ[$k])) $occ[$k]='';
              if($checklist=='livro_vermelho_2013') {
                $data[] = utf8_decode($occ[$k]);
              } else {
                $data[] = $occ[$k];
              }
          }
          fputcsv($csv,$data);
        }
      }
    }

  }



}
