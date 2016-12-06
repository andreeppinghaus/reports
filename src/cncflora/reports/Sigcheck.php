<?php

namespace cncflora\reports ;

class Sigcheck {

  public $title = "Informações SIG Filtrada";
  public $description = "Lista com as occorrencias para SIG com checagem se estão todas ok.";
  public $is_private = true;
  public $filters = ['checklist','family','species'];
  public $fields = [];

  public $fields_array = [
    "occurrenceID"=>'id'
    ,"specieID"=>'specieID'
    ,"family"=>'family'
    ,"acceptedNameUsage"=>'specie'
    ,"institutionCode"=>'inst_code'
    ,"collectionCode"=>'col_code'
    ,"catalogNumber"=>'catalog_n'
    ,"recordedBy"=>'recordedby'
    ,"recordNumber"=>'record_n'
    ,"year"=>'year'
    ,"month"=>'month'
    ,"day"=>'day'
    ,"stateProvince"=>'state'
    ,"municipality"=>'city'
    ,"locality"=>'locality'
    ,"decimalLongitude"=>'longitude'
    ,"decimalLatitude"=>'latitude'
    ,"coordinateUncertaintyInMeters"=>'precision'
    ,"georeferenceProtocol"=>'protocol'
    ];

  public $precisions_allowed=[
    "1 a 5 km",
    "250 a 1000 m",
    "5 a 10 km",
    "centroide de municipio",
    "centroide de uc",
    "0 a 250 m",
    "10 a 50 km",
    "50 a 100 km",
    "",
    "1 a 10 km"];

  public function __construct() {
    $this->fields = array_merge($this->fields, array_values($this->fields_array) );
  }

  function run($csv,$checklist,$family=null,$specie=null) {
    fputcsv($csv,$this->fields, ';');

    $repoOcc = new \cncflora\repository\Occurrences($checklist);
    $repoTaxon = new \cncflora\repository\Taxon($checklist);

    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    $used=0;
    foreach($families as $f) {
      if($specie==null) {
        $spps = $repoTaxon->listFamily($f);
      } else {
        $spps = [$repoTaxon->getSpecie($specie)];
      }
      foreach($spps as $spp) {
        $names = $repoTaxon->listNames($spp['scientificNameWithoutAuthorship']);
        $occs  = $repoOcc->listOccurrences($names,false);
        foreach($occs as $occ) {
          if(!$repoOcc->isValidated($occ)) {
            continue;
            //throw new \Exception('Espécie '.$spp['family'].' '.$spp['scientificNameWithoutAuthorship'].' possui pontos não validados (ex.: '.$occ['_id'].').');
          }
          $field='coordinateUncertaintyInMeters';
          if(isset($occ[$field]) && !is_null($occ[$field]) && !in_array($occ[$field],$this->precisions_allowed)) {
            continue;
            //throw new \Exception('Espécie  '.$spp['family'].' '.$spp['scientificNameWithoutAuthorship'].' possui precisões inválidas (ex.:'.$occ['_id'].' -> '.$occ[$field].').');
          }
          if($repoOcc->canUse($occ)) {
            $used++;
            $data  = [];
            $occ=$repoOcc->flatten([$occ])[0];
            $occ["acceptedNameUsage"] = $spp["scientificNameWithoutAuthorship"];
            $occ['specieID'] = str_replace(" ", "_", $spp["scientificNameWithoutAuthorship"]);
            foreach($this->fields_array as $k=>$n) {
              if(!isset($occ[$k])) $occ[$k]='';
              if($checklist=='endemicas_rio_de_janeiro' && $k=="coordinateUncertaintyInMeters" && isset($occ['georeferencePrecision']) && $occ['georeferencePrecision'] != "")
                $occ[$k] = $occ['georeferencePrecision'];
              if($checklist=='livro_vermelho_2013' && !mb_check_encoding($occ[$k], 'UTF-8')) {
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
    if($used ==0) {
      throw new \Exception('Nenhuma ocorrência usável.');
    }

  }

}
