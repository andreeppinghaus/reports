<?php

include 'base.php';

$fields = ["family","acceptedNameUsage","valid","invalid","not_validated","sig_ok","sig_nok","no_sig","used","unused","total",'eoo','aoo'];

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

foreach($taxons as $taxon) {
  if($taxon->taxonomicStatus == 'synonym') {
    foreach($taxons as $taxon2) {
      if($taxon2->acceptedNameUsage==$taxon->acceptedNameUsage || $taxon2->scientificNameWithoutAuthorship==$taxon->acceptedNameUsage) { 
        $taxon->acceptedNameUsageWithoutAuthorship = $taxon->scientificNameWithoutAuthorship;
      }
    }
  }
}

$data  = [];
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
        $doc->family = $taxon->family;
        break;
      }
    }
    if(!$got) {
      echo "Missing ".$doc->_id."\n";
      continue;
    } else {
      echo "Got ".$doc->_id."\n";

      if(!isset($data[$doc->acceptedNameUsage])) {
        $data[$doc->acceptedNameUsage]= new StdClass;
        $data[$doc->acceptedNameUsage]->acceptedNameUsage = $doc->acceptedNameUsage;
        $data[$doc->acceptedNameUsage]->family = $doc->family;
        $data[$doc->acceptedNameUsage]->total = 0;
        $data[$doc->acceptedNameUsage]->valid = 0;
        $data[$doc->acceptedNameUsage]->invalid = 0;
        $data[$doc->acceptedNameUsage]->not_validated = 0;
        $data[$doc->acceptedNameUsage]->sig_ok = 0;
        $data[$doc->acceptedNameUsage]->sig_nok = 0;
        $data[$doc->acceptedNameUsage]->no_sig = 0;
        $data[$doc->acceptedNameUsage]->used = 0;
        $data[$doc->acceptedNameUsage]->unused = 0;
      }

      $d = $data[$doc->acceptedNameUsage];
      $d->total++;

      if(isset($doc->georeferenceVerificationStatus)) {
        if($doc->georeferenceVerificationStatus == "1" || $doc->georeferenceVerificationStatus == "ok") {
          $doc->georeferenceVerificationStatus = "ok";
          $d->sig_ok++;
        } else {
          $d->sig_nok++;
        }
      } else {
        $d->no_sig++;
        $doc->georeferenceVerificationStatus = '';
      }

      if(isset($doc->validation)) {
        if(is_object($doc->validation)) {
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

      if($doc->valid == 'true') {
        $d->valid++;
      } else if($doc->valid == 'false') {
        $d->invalid++;
      } else {
        $d->not_validated++;
      }

      if($doc->valid == 'true' && $doc->georeferenceVerificationStatus == 'ok') {
        $d->used++;
      } else {
        $d->unused++;
      }
    }
  }
}


$i=0;
foreach($data as $d) {
  $i++;
  if($i==100) {
    sleep ( 2 );
    $i=0;
  }

  $url = 'http://jb049/occurrences/'.$base.'/specie/'.urlencode($d->acceptedNameUsage).'?json=true';
  $sess='1b71063e4887a009fd6742376c6f92a52036817eb443ac8479750dac81233d42';
  shell_exec("curl '".$url."' -L -H 'Cookie: rack.session=".$sess."' > tmp_occs.json");
  $resp = file_get_contents('tmp_occs.json');
  $json = json_decode($resp);

  $d->aoo = $json->stats->aoo;
  $d->eoo = $json->stats->eoo;

  $row = array();
  foreach($fields as $f) {
    $row[] = $d->$f;
  }
  fputcsv($csv,$row);
}

