<?php
namespace cncflora\reports;

class InfoDDs {

  public $title = "Info sobre DDs";
  public $description = "Lista de estados, biomas e fitofisionomia de espécies DDs dentro do recorte";
  public $is_private = true;
  public $fields = ["familia","nome científico","estados","bioma","fitofisionomia"];
  public $filters=["checklist","family"];

  function run($dest,$checklist,$family=null) {
    fputcsv($dest,$this->fields, ';');

    $repo=new \cncflora\repository\Profiles($checklist);
    $repAs = new \cncflora\repository\Assessment($checklist);
    $repoOcc = new \cncflora\repository\Occurrences($checklist);

    if($family!=null) {
      $profiles=$repo->listFamily($family);
    } else {
      $profiles=$repo->listAll();
    }

    foreach($profiles as $d) {
      $categoria = $repAs->listCategoryByName($d["taxon"]["scientificNameWithoutAuthorship"]);
      if(!(isset($categoria[0]) && $categoria[0] == "DD")){
        $occs  = $repoOcc->listOccurrences($d["taxon"]["scientificNameWithoutAuthorship"],false);
        $estados = "";
        foreach ($occs as $occ) {
          if(isset($occ["stateProvince"]) && !empty($occ["stateProvince"]) && strpos(strtolower($estados), strtolower($occ["stateProvince"])) === false){
            $estados .= utf8_decode($occ["stateProvince"]) . ", ";
          }
        }

        if(isset($d["ecology"]) && isset($d["ecology"]["biomas"]) && is_array($d["ecology"]["biomas"])) {
          $biomas = "";
          foreach($d["ecology"]["biomas"] as $bioma) {
            $biomas .= $bioma . ", ";
          }
        }

        if(isset($d["ecology"]) && isset($d["ecology"]["fitofisionomies"]) && is_array($d["ecology"]["fitofisionomies"])) {
          $fitofisionomies = "";
          foreach($d["ecology"]["fitofisionomies"] as $ft) {
            $fitofisionomies .= $ft . ", ";
          }
        }
        $data =[
          $d["taxon"]["family"],
          $d["taxon"]["scientificNameWithoutAuthorship"],
          $estados,
          $biomas,
          $fitofisionomies
        ];
        fputcsv($dest,str_replace(array("\n", "\r"), ' ', str_replace(";", ",", $data)), ';');
      }

    }
  }
}
