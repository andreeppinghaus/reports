<?php

namespace cncflora\reports ;

class Occurrences {

  public $title = "Ocorrências";
  public $description = "Lista com todas as ocorrências do recorte por espécie.";
  public $is_private = true;
  public $filters = ["checklist","family","species"];
  public $fields = ['familia aceita','nome aceito'];
  public $fields_array = array(
    "occurrenceID" => "id da ocorrência",
    "bibliographicCitation" => "literatura",
    "institutionCode" => "código da instituição",
    "collectionCode" => "código da coleção",
    "catalogNumber" => "número de catálogo/código de barras",
    "recordNumber" => "número do coletor",
    "recordedBy" => "coletor",
    "year" => "ano da coleta",
    "month" => "mês da coleta",
    "day" => "dia da coleta",
    "identifiedBy" => "identificado por",
    "stateProvince" => "estado",
    "municipality" => "município",
    "locality" => "localidade",
    "decimalLatitude" => "latitude",
    "decimalLongitude" => "longitude",
    "family" => "família",
    "genus" => "gênero",
    "specificEpithet" => "epíteto específico",
    "infraspecificEpithet" => "variedade",
    "scientificName" => "nome científico",
    "georeferenceRemarks" => "obs. de SIG",
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
    "metadata_contributor" => "colaboradores",
    "metadata_modified" => "data da última modificação",
    "remarks" => "observações",
    "comments" => "comentários"
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

    $got=[];
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
          $id = $occ['occurrenceID'];
          if(isset($got[$id])) {
            continue;
          }
          $got[$id]=true;
          $data  = [$f,$spp['scientificNameWithoutAuthorship']];
          foreach($this->fields_array as $k=>$n) {
            if(!isset($occ[$k])) $occ[$k]='';
            $data[] = $occ[$k];
          }
          fputcsv($csv,$data);
        }
      }
    }

  }

}
