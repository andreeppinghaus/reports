<?php

namespace cncflora\repository;

class Species{

    public function getCurrentTaxon($name, $checklist) {
      $name = trim($name);
      $flora = json_decode(file_get_contents(FLORADATA."/api/v1/specie?scientificName=".rawurlencode($name)))->result;

      if($flora==null) {
        $flora = ["not_found"=>true];
      } else if($flora->scientificNameWithoutAuthorship != $name) {
        $flora->changed=true;
      } else {
        $repo = new \cncflora\repository\Taxon($checklist);
        //$repo->listSynonyms($spp['scientificNameWithoutAuthorship']);
        $syns = $repo->listSynonyms($name);
        $floraSyns = $flora->synonyms;
        error_log(print_r($syns, TRUE));
        $synsNames = [];
        foreach($syns as $syn) {
          error_log(print_r($syn, TRUE));
          if(isset($syn->scientificNameWithoutAuthorship))
            $synsNames[] = $syn->scientificNameWithoutAuthorship;
        }
        sort($synsNames);

        $floraSynsNames =[];
        foreach($floraSyns as $syn) {
          $floraSynsNames[] = $syn->scientificNameWithoutAuthorship;
        }
        sort($floraSynsNames);

        if(implode(",",$floraSynsNames) != implode(",",$synsNames)) {
            $flora->synonyms_changed=true;
        }
      }

      return $flora;
    }
}
