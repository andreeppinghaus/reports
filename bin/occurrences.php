<?php

global $title, $description, $is_private, $fields;
$title = "Ocorrências";
$description = "Lista com todas as ocorrências do recorte por espécie.";
$is_private = true;
include 'occurrences_fields.php';
$fields = array();
foreach ($fields_array as $f){
    array_push($fields, $f);
}
include 'base.php';

fputcsv($csv,$fields);
$fields = array_keys($fields_array);

$taxons = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
      if(isset($doc->scientificNameWithoutAuthorship) && strlen($doc->scientificNameWithoutAuthorship) > 1) {
        $taxons[]=$doc;
      }
    }
}

foreach($taxons as $taxon) {
  if($taxon->taxonomicStatus == 'synonym') {
    foreach($taxons as $taxon2) {
      if($taxon2->taxonomicStatus=='accepted') {
        if($taxon2->acceptedNameUsage==$taxon->acceptedNameUsage || $taxon2->scientificName==$taxon->acceptedNameUsage || $taxon2->scientificNameWithoutAuthorship==$taxon->acceptedNameUsage) {
          $taxon->acceptedNameUsageWithoutAuthorship = $taxon2->scientificNameWithoutAuthorship;
        }
      }
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
            $doc->acceptedNameUsage = $taxon->scientificNameWithoutAuthorship;
          } else if($taxon->taxonomicStatus == 'synonym') {
            $doc->acceptedNameUsage = $taxon->acceptedNameUsageWithoutAuthorship;
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
            // Join coordinateUncertaintyInMeters and georeferencePrecision fields
            if ($f == 'coordinateUncertaintyInMeters') {
                if(!isset($doc->$f) && isset($doc->georeferencePrecision)) {
                    $doc->$f = $doc->georeferencePrecision;
                }
            }
            // Join remarks and occurrenceRemarks
            if ($f == 'remarks') {
                if(!isset($doc->$f) && isset($doc->occurrenceRemarks)) {
                    $doc->$f = $doc->occurrenceRemarks;
                }
                elseif (isset($doc->occurrenceRemarks)){
                     $doc->$f = $doc->$f." ".$doc->occurrenceRemarks;
                }
            }
          if(isset($doc->$f)) {
            //Substitute ; with , to not destroy CSV format
            $data[] = str_replace(";", ",", $doc->$f);
          } else {
            $data[] = "";
          }
        }
        fputcsv($csv,$data);
      }
    }
}

