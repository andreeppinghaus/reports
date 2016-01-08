<?php

namespace cncflora\reports;

class OccurrencesCount {

  public $title = "Contagem de Ocorrências";
  public $description = "Lista contabilizando as ocorrências por espécie em relação à validação, às informações SIG e aos cálculos de EOO e AOO.";
  public $is_private = true;
  public $fields=['família','nome aceito'];
  public $filters=["checklist","family","species"];
  public $fields_array = ["valid" => "válidos",
                          "invalid" => "inválidos",
                          "validated" => "validados",
                          "not_validated" => "não validados",
                          "sig_ok" => "SIG OK",
                          "sig_nok" => "SIG não OK",
                          "sig_reviewed" => "SIG revisado",
                          "not_sig_reviewed" => "SIG não revisado",
                          "can_use" => "usados nos cálculos",
                          "can_not_use" => "não usados nos cálculos",
                          "sig_done"=>"SIG pronto",
                          "done"=>"pronto",
                          "total" => "total"
                          //"eoo" => "eoo",
                          //"aoo" => "aoo"
                          ];

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
        $occs  =  $repoOcc->listOccurrences($names,false);
        $stats = $repoOcc->getStats($occs,false);
        $data  = [$f,$spp['scientificNameWithoutAuthorship']];
        foreach($this->fields_array as $k=>$n) {
          $data[] = $stats[$k];
        }
        fputcsv($csv,$data);
      }
    }

  }

}
