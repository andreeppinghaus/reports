<?php

namespace cncflora\reports ;

class FamiliesContributors {

  public $title = "Contribuidores das Famílias";
  public $description = "Lista com os colaboradores por família.";
  public $is_private = true;
  public $filters = ["checklist","family"];
  public $fields_array = array(
    "family" => "Família",
    "profile.metadata.creator" => "Analistas do perfil (ANALYST)",
    "profile.metadata.contributor" => "Validadores (VALIDATOR)",
    "occurrence.validation.by" => "Validadores das ocorrências",
    "profile.metada.contact" => "Email dos contribuidores",
    "georeferencedBy" => "Analistas SIG",
    "assessment.assessor" => "Avaliadores (ASSESSOR)",
    "assessment.evaluator" => "Revisores (EVALUATOR)"
  );

  function run($csv,$checklist,$family=null,$specie=null) {
    fputcsv($csv,$this->fields_array, ';');

    $repoOcc = new \cncflora\repository\Occurrences($checklist);
    $repoTaxon = new \cncflora\repository\Taxon($checklist);
    $repoProf = new \cncflora\repository\Profiles($checklist);
    $repo = new \cncflora\repository\Assessment($checklist);

    if($family==null) {
      $families = $repoTaxon->listFamilies();
    } else {
      $families = [$family];
    }

    foreach($families as $f) {
      $profiles = $repoProf->listByFamily($f);
      $assessments = $repo->listByFamily($f);
      $occs  = $repoOcc->listByFamily($f);
      $aux=[];
      $data=[];
      $georeferencedBy = "";
      $validationBy = "";
      $assessor = "";
      $evaluator = "";
      $creator = "";
      $contributor = "";
      $contact = "";
      foreach ($occs as $occ) {
        if(isset($occ['georeferencedBy']) && !empty($occ['georeferencedBy']) && strpos($georeferencedBy, $occ['georeferencedBy']) === false)
          $georeferencedBy .= $occ['georeferencedBy'] . ", ";
        if(isset($occ['validator']) && !empty($occ['validator']) && strpos($validationBy, $occ['validator']) === false)
          $validationBy .= $occ['validator'] . ", ";
      }

      foreach ($assessments as $asst) {
        if(isset($asst['assessor']) && !empty($asst['assessor']) && strpos($assessor, $asst['assessor']) === false)
          $assessor .= $asst['assessor'] . ", ";
        if(isset($asst['evaluator']) && !empty($asst['evaluator']) && strpos($evaluator, $asst['evaluator']) === false)
          $evaluator .= $asst['evaluator'] . ", ";
      }
      foreach ($profiles as $profs) {
        if(isset($profs['creator']) && !empty($profs['creator']) && strpos($creator, $profs['creator']) === false)
          $creator .= $profs['creator'] . ", ";
        if(isset($profs['contributor']) && !empty($profs['contributor']) && strpos($contributor, $profs['contributor']) === false)
          $contributor .= $profs['contributor'] . ", ";
        if(isset($profs['metadata']['contact']) && !empty($profs['metadata']['contact']))
          $contact .= $profs['metadata']['contact'] . ", ";
      }

      $data[]=$f;
      $data[]=$this->prepareField($creator);
      $data[]=$this->prepareField($contributor);
      $data[]=$this->prepareField($validationBy);
      $data[]=$this->prepareField($contact);
      $data[]=$this->prepareField($georeferencedBy);
      $data[]=$this->prepareField($assessor);
      $data[]=$this->prepareField($evaluator);

      fputcsv($csv,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
    }
  }

  public function prepareField($string) {
    if(!is_null($string)){
      $str = str_replace(";", ",", $string);
      $str = substr_replace($str, "", strlen($str)-2, strlen($str));
      $aux = explode(",", $str);
      foreach ($aux as $value)
        $aux2[] = trim($value);

      return implode(", ", array_unique($aux2));
    }

  }
}
