<?php

namespace cncflora\reports;

class Status {

  public $title = "Status do Workflow";
  public $description = "Lista com o status do workflow dos perfis de espécies e as respectivas avaliações.";
  public $is_private = false;
  public $fields = ["família","nome científico","autor","status na análise","status na avaliação","categoria","critério"];
  public $filters=['checklist','family'];

  function run($csv,$checklist,$family=null) {
    fputcsv($csv,$this->fields);

    $repo0=new \cncflora\repository\Taxon($checklist);
    $repo1=new \cncflora\repository\Profiles($checklist);
    $repo2=new \cncflora\repository\Assessment($checklist);

    if($family!=null) {
      $taxa=$repo0->listFamily($family);
      $profiles=$repo1->listFamily($family);
      $assessments=$repo2->listFamily($family);
    } else {
      $taxa=$repo0->listAll();
      $profiles=$repo1->listAll();
      $assessments=$repo2->listAll();
    }

    $data = [];
    foreach($taxa as $t) {
      $data[$t["scientificNameWithoutAuthorship"]] = [
        "family"=>$t["family"],
        "name"=>$t["scientificNameWithoutAuthorship"],
        "author"=>$t["scientificNameAuthorship"],
        "acceptedNameUsage"=>$t["scientificName"],
        "analysis"=>"",
        "assessment"=>"",
        "category"=>"",
        "criteria"=>""
      ];
    }

    foreach($profiles as $p) {
      if(isset($data[$p['taxon']['scientificNameWithoutAuthorship']])) {
        $data[$p['taxon']['scientificNameWithoutAuthorship']];
        if(isset($p['metadata']['status'])) {
          $data[$p['taxon']['scientificNameWithoutAuthorship']]['analysis']=$p['metadata']['status'];
        }
      }
    }

    foreach($assessments as $a) {
      if(isset( $data[$a['taxon']['scientificNameWithoutAuthorship']] )) {
        $d = $data[$a['taxon']['scientificNameWithoutAuthorship']];
        if(isset($a['metadata']['status'])) {
          $d['assessment']=$a['metadata']['status'];
        }
        if(isset($a['criteria'])) {
          $d['criteria']=$a['criteria'];
        }
        if(isset($a['category'])) {
          $d['category']=$a['category'];
        }
        $data[$a['taxon']['scientificNameWithoutAuthorship']] = $d;
      }
    }

    foreach($data as $taxon) {
      $d=[
        $taxon["family"]
        ,$taxon["name"]
        ,$taxon["author"]
        ,$taxon["analysis"]
        ,$taxon["assessment"]
        ,$taxon["category"]
        ,$taxon["criteria"]
      ];
      fputcsv($csv,$d);
    }
  }
}

