<?php

namespace cncflora\reports;

class Exsitu  {

  public $title = "Exsitu";
  public $description = "Informações para Exsitu";
  public $is_private = false;
  public $fields = ["familia","nome científico","autor","categoria","ocorrencias","usos","acoes","sinonimos"];
  public $filters=["checklist",'family'];

  function run($csv,$checklist,$family="") {
    fputcsv($csv,$this->fields);

    $repo0=new \cncflora\repository\Assessment($checklist);
    $repo1=new \cncflora\repository\Profiles($checklist);
    $repo2=new \cncflora\repository\Taxon($checklist);
    $repo3 = new \cncflora\repository\Occurrences($checklist);

    if($family!=null) {
      $assessments=$repo0->listFamily($family);
      $profiles=$repo1->listFamily($family);
    } else {
      $assessments=$repo0->listAll();
      $profiles=$repo1->listAll();
    }

    foreach($assessments as $doc) {
      $data=[];
      $data["family"] = $doc["taxon"]["family"];
      $data["name"]   = $doc["taxon"]["scientificNameWithoutAuthorship"];
      $data["author"] = $doc["taxon"]["scientificNameAuthorship"];
      if(isset($doc["category"])) {
        $data['category'] = $doc["category"];
      } else {
        $data['category'] = "";
      }

      $data['occurrences']=0;
      $data['actions']=[];
      $data['uses']=[];
      $data['synonyms']=[];
      foreach($profiles as $d) {
        if($d['taxon']['scientificNameWithoutAuthorship'] == $doc['taxon']['scientificNameWithoutAuthorship']) {
          if(isset($d["uses"]) && is_array($d["uses"])) {
            foreach($d["uses"] as $t) {
              if(isset($t["use"])) {
                $data['uses'][] = $t['use'];
              }
            }
          }
          if(isset($d["actions"]) && is_array($d["actions"])) {
            foreach($d["actions"] as $t) {
              if(isset($t["action"])) {
                $data['actions'][] = $t['action'];
              }
            }
          }
        }
      }
      $names=[$doc['taxon']['scientificNameWithoutAuthorship']];
      $syns = $repo2->listSynonyms($doc['taxon']['scientificNameWithoutAuthorship']);
      foreach($syns as $s) {
        $names[]=$s['scientificNameWithoutAuthorship'];
        $data['synonyms'][]=$s['scientificNameWithoutAuthorship'];
      }
      $occs  =  $repo3->listOccurrences($names,false);
      $stats = $repo3->getStats($occs,false);
      $data['occurrences']=$stats['can_use'];

      $data=[
        $data["family"],
        $data["name"],
        $data["author"],
        $data["category"],
        $data["occurrences"],
        implode(" ; ",$data["uses"]),
        implode(" ; ",$data["actions"]),
        implode(" ; ",$data["synonyms"])
      ];
      fputcsv($csv,$data);
    }
  }
}

