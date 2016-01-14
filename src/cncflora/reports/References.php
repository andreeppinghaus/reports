<?php

namespace cncflora\reports;

class References {
  public $title = "Referências";
  public $description = "Lista com todas as referências utilizadas no recorte, separadas por espécie e indicação de onde são utilizadas.";
  public $is_private = false;
  public $fields = ["familia","nome científico","tipo de documento", "campo no documento", "referências"];
  public $filters = ['checklist','family','species'];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo0=new \cncflora\repository\Profiles($checklist);
    $repo1=new \cncflora\repository\Assessment($checklist);

    if($family!=null) {
      $profiles=$repo0->listFamily($family);
      $assessments=$repo1->listFamily($family);
    } else {
      $profiles=$repo0->listAll();
      $assessments=$repo1->listAll();
    }

    foreach($profiles as $d) {
      foreach($d as $field=>$value) {
        if(is_object($value)) {
          if(isset($value["references"]) && is_array($value["references"])) {
            foreach($value["references"] as $r) {
              if(is_string($r) && strlen(trim($r)) >= 2) {
                  $data = [$d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],"perfil de espécie",$field,$r];
                fputcsv($csv,$data);
              }
            }
          }
        } else if(is_array($value)) {
          foreach($value as $value2) {
            if(is_object($value2)) {
              if(isset($value2["references"]) && is_array($value2["references"])) {
                foreach($value2["references"] as $r) {
                  if(is_string($r) && strlen(trim($r)) >= 2) {
                    $data = [$d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],"perfil de espécie",$field,$r];
                    fputcsv($csv,$data);
                  }
                }
              }
            }
          }
        }
      }
    }
    foreach($assessments as $d) {
      if(isset($d["references"]) && is_array($d["references"])) {
        foreach($d["references"] as $r) {
          if(is_string($r) && strlen(trim($r)) >= 2) {
            $data = [$d["taxon"]["family"],$d["taxon"]["scientificNameWithoutAuthorship"],"avaliação","",$r];
            fputcsv($csv,$data);
          }
        }
      }
    }
  }
}
