<?php

if($argv[1] == 'livro_vermelho') return;

include 'base.php';

$fields = ["occurrenceID","bibliographicCitation","institutionCode","collectionCode","catalogNumber","recordNumber","recordedBy","occurrenceRemarks","year","month","day","identifiedBy","yearIdentified","monthIdentified","dayIdentified","stateProvince","municipality","locality","decimalLatitude","decimalLongitude","family","genus","specificEpithet","infraspecificEpithet","scientificName","georeferenceRemarks","georeferenceProtocol","georeferenceVerificationStatus","georeferencedBy","georeferencedDate","georeferencePrecision","acceptedNameUsage","valid","validation_taxonomy","validation_cultivated","validation_duplicated","validation_native","validation_georeference","remarks","comments"];

fputcsv($csv,$fields);

$taxons = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
        $taxons[]=$doc;
    }
}

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='occurrence') {
      $got =false;
      foreach($taxons as $taxon) {
        $m1 = "/.*".$taxon->scientificNameWithoutAuthorship.".*/";
        if(  (isset($doc->scientificName) && preg_match($m1,$doc->scientificName) )
          || (isset($doc->scientificNameWithoutAuthorship) && preg_match($m1,$doc->scientificNameWithoutAuthorship) )
          || (isset($doc->acceptedNameUsage) && preg_match($m1,$doc->acceptedNameUsage) )) {
          $got=true;
          if($taxon->taxonomicStatus == 'accepted') {
            $doc->acceptedNameUsage = $taxon->scientificName;
          } else if($taxon->taxonomicStatus == 'synonym') {
            $doc->acceptedNameUsage = $taxon->acceptedNameUsage;
          }
        }
      }
      if($got) {
        if(isset($doc->georeferenceVerificationStatus)) {
          if($doc->georeferenceVerificationStatus == "1" || $doc->georeferenceVerificationStatus == "ok") {
            $doc->georeferenceVerificationStatus = "ok";
          }
          if(isset($doc->validation)) {
            if(is_object($doc->validation)) {
              foreach($doc->validation as $k=>$v) {
                $kk = 'validation_'.$k;
                $doc->$kk=$v;
              }
              if(isset($doc->validation->status)) {
                if($doc->validation->status == "valid") {
                  $doc->valid="true";
                } else {
                  $doc->valid="false";
                }
              } else {
                if(
                  (
                       !isset($doc->validation->taxonomy)
                    || $doc->validation->taxonomy == null
                    || $doc->validation->taxonomy == 'valid'
                  )
                  &&
                  (
                       !isset($doc->validation->georeference)
                    || $doc->validation->georeference == null
                    || $doc->validation->georeference == 'valid'
                  )
                  && 
                  (
                       !isset($doc->validation->native)
                    || $doc->validation->native == null
                    || $doc->validation->native != 'non-native'
                  )
                  && 
                  (
                       !isset($doc->validation->presence)
                    || $doc->validation->presence == null
                    || $doc->validation->presence != 'absent'
                  )
                  && 
                  (
                       !isset($doc->validation->cultivated)
                    || $doc->validation->cultivated == null
                    || $doc->validation->cultivated != 'yes'
                  )
                  && 
                  (
                       !isset($doc->validation->duplicated)
                    || $doc->validation->duplicated == null
                    || $doc->validation->duplicated != 'yes'
                  )
                ) {
                  $doc->valid="true";
                } else {
                  $doc->valid="false";
                }
              }
            } else {
              $doc->valid = "";
            }
          } else {
            $doc->valid = "";
          }
        }
        $data = [];
        foreach($fields as $f) {
          if(isset($doc->$f)) {
            $data[] = $doc->$f;
          } else {
            $data[] = "";
          }
        }
        fputcsv($csv,$data);
      } else {
        //var_dump($doc);
      }
    }
}

