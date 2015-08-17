<?php

include 'base.php';

#$fields = ["occurrenceID","bibliographicCitation","institutionCode","collectionCode","catalogNumber","recordNumber","recordedBy","occurrenceRemarks","year","month","day","identifiedBy","yearIdentified","monthIdentified","dayIdentified","stateProvince","municipality","locality","decimalLatitude","decimalLongitude","family","genus","specificEpithet","infraspecificEpithet","scientificName","georeferenceRemarks","georeferenceProtocol","georeferenceVerificationStatus","georeferencedBy","georeferencedDate","georeferencePrecision","coordinateUncertaintyInMeters","acceptedNameUsage","valid","validation_taxonomy","validation_cultivated","validation_duplicated","validation_native","validation_georeference","contributor","dateLastModified","remarks","comments"];
//$fields = ["occurrenceID","family","acceptedNameUsage","decimalLatitude","decimalLongitude","georeferenceProtocol","georeferenceVerificationStatus","georeferencedBy","georeferencedDate","georeferencePrecision","coordinateUncertaintyInMeters","acceptedNameUsage","valid","validation_taxonomy","validation_cultivated","validation_duplicated","validation_native","validation_georeference","contributor","remarks"];
$fields = ["occurrenceID","family", "familyAccepted", "acceptedNameUsage", "acceptedNameUsageWithoutAuthorship", "decimalLatitude","decimalLongitude","georeferenceProtocol","georeferenceVerificationStatus","georeferencedBy","georeferencedDate","georeferencePrecision","coordinateUncertaintyInMeters","valid","validation_taxonomy","validation_cultivated","validation_duplicated","validation_native","validation_georeference", "year", "contributor","remarks"];

fputcsv($csv,$fields);

$taxons = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
      if(isset($doc->scientificNameWithoutAuthorship) && strlen($doc->scientificNameWithoutAuthorship) > 1) {
        $taxons[]=$doc;
      }
    }
}

foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='occurrence') {
      $got =false;
      foreach($taxons as $k=>$taxon) {
        $m1 = "/.*".$taxon->scientificNameWithoutAuthorship.".*/";
        if(  (isset($doc->scientificName) && preg_match($m1,$doc->scientificName) )
          || (isset($doc->scientificNameWithoutAuthorship) && preg_match($m1,$doc->scientificNameWithoutAuthorship) )
          || (isset($doc->acceptedNameUsage) && preg_match($m1,$doc->acceptedNameUsage) )) {
          $got=true;
          if($taxon->taxonomicStatus == 'accepted') {
            $doc->acceptedNameUsage = $taxon->scientificName;
            $doc->acceptedNameUsageWithoutAuthorship = $taxon->scientificNameWithoutAuthorship;
            $doc->familyAccepted = $taxon->family;
          } else if($taxon->taxonomicStatus == 'synonym') {
                foreach($taxons as $taxon2){
                  if($taxon2->taxonomicStatus=='accepted') {
                    if (($taxon2->scientificNameWithoutAuthorship == $taxon->acceptedNameUsage)
                        || ($taxon2->scientificName == $taxon->acceptedNameUsage)) {
                        $doc->acceptedNameUsage = $taxon2->scientificName;
                        $doc->familyAccepted = $taxon2->family;
                        $doc->acceptedNameUsageWithoutAuthorship = $taxon2->scientificNameWithoutAuthorship;
                    }
                  }
                }
          }
          break;
        }
      }
      if(!$got) {
        echo "Missing ".$doc->_id."\n";
      } else {
        echo "Got ".$doc->_id."\n";
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
                } else if($doc->validation->status == "invalid") {
                  $doc->valid="false";
                } else {
                  $doc->valid="";
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

        $doc->contributor = $doc->metadata->contributor ;
        $doc->dateLastModified = date("Y-m-d H:i:s" ,$doc->metadata->modified );

        $data = [];
        foreach($fields as $f) {
          if(isset($doc->$f)) {
            $data[] = $doc->$f;
          } else {
            $data[] = "";
          }
        }
        fputcsv($csv,$data);
      }
    }
}

