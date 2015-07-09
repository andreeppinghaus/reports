<?php

include 'base.php';

$fields = ["occurrenceID","institutionCode","oldCollectionCode","collectionCode","catalogNumber","recordNumber","recordedBy","occurrenceRemarks","year","month","day","identifiedBy","yearIdentified","monthIdentified","dayIdentified","stateProvince","municipality","locality","decimalLatitude","decimalLongitude","family","georeferenceVerificationStatus","acceptedNameUsage","valid","dateLastModified","created","creator","contributor"];

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

$i=0;
$t = count($all->rows);
foreach($all->rows as $row) {
    $i++;
    echo $i." of ".$t."\n";
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
          } else if($taxon->taxonomicStatus == 'synonym') {
            $doc->acceptedNameUsage = $taxon->acceptedNameUsage;
          }
          break;
        }
      }
      if(!$got) {
      } else {
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

        $doc->creator = $doc->metadata->creator ;
        $doc->contributor = $doc->metadata->contributor ;
        $doc->dateLastModified = date("Y-m-d H:i:s" ,$doc->metadata->modified );
        $doc->created = date("Y-m-d H:i:s" ,$doc->metadata->created );

        $revs = json_decode(file_get_contents("http://cncflora.jbrj.gov.br/couchdb/".$base."/".rawurlencode($doc->_id)."?revs_info=true"));
        $last = null;
        foreach($revs->_revs_info as $rev) {
          if($rev->status == 'available') {
            $last = $rev->rev;
          }
        }
        if($last == null) {
          continue;
        }

        $orig = json_decode(file_get_contents("http://cncflora.jbrj.gov.br/couchdb/".$base."/".rawurlencode($doc->_id)."?rev=".$last));

        if($orig->collectionCode == $doc->collectionCode) {
          continue;
        }
        $doc->oldCollectionCode = $orig->collectionCode;


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

