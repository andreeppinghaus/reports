<?php

global $title, $description, $is_private, $fields;
$title = "Contagem de Ocorrências";
$description = "Lista contabilizando as ocorrências por espécie em relação à validação, às informações SIG e aos cálculos de EOO e AOO.";
$is_private = true;
include 'occurrences_count_fields.php';
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

$data  = [];
foreach($taxons as $taxon) {
  if($taxon->taxonomicStatus == 'accepted') {
      if(!isset($data[$taxon->scientificNameWithoutAuthorship])) {
        $data[$taxon->scientificNameWithoutAuthorship]= new StdClass;
        $data[$taxon->scientificNameWithoutAuthorship]->acceptedNameUsage = $taxon->scientificNameWithoutAuthorship;
        $data[$taxon->scientificNameWithoutAuthorship]->family = $taxon->family;
        $data[$taxon->scientificNameWithoutAuthorship]->total = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->valid = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->invalid = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->validated = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->not_validated = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->sig_ok = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->sig_nok = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->sig = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->no_sig = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->used = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->unused = 0;
        $data[$taxon->scientificNameWithoutAuthorship]->eoo = "n/a";
        $data[$taxon->scientificNameWithoutAuthorship]->aoo = "n/a";
      }
  } else if($taxon->taxonomicStatus == 'synonym') {
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
        || (isset($doc->acceptedNameUsage) && preg_match($m1,$doc->acceptedNameUsage))) {
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

      $d = $data[$doc->acceptedNameUsage];
      if(!isset($d)) {
        var_dump($doc);
        exit;
      }
      $d->total++;

      if(isset($doc->georeferenceVerificationStatus)) {
        if($doc->georeferenceVerificationStatus == "1" || $doc->georeferenceVerificationStatus == "ok") {
          $doc->georeferenceVerificationStatus = "ok";
          $d->sig_ok++;
        } else {
          $d->sig_nok++;
        }
        $d->sig++;
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
              if (array_key_exists("taxonomy", $doc->validation)){
                  if(
                      (
                          //!isset($doc->validation->taxonomy)
                          //||
                          $doc->validation->taxonomy == null
                          || $doc->validation->taxonomy == 'valid'
                      )
                      &&
                      (
                          //!isset($doc->validation->georeference)
                          //||
                          $doc->validation->georeference == null
                          || $doc->validation->georeference == 'valid'
                      )
                      &&
                      (
                          !isset($doc->validation->native)
                          //|| $doc->validation->native == null
                          || $doc->validation->native != 'non-native'
                      )
                      &&
                      (
                          !isset($doc->validation->presence)
                          //|| $doc->validation->presence == null
                          || $doc->validation->presence != 'absent'
                      )
                      &&
                      (
                          !isset($doc->validation->cultivated)
                          //|| $doc->validation->cultivated == null
                          || $doc->validation->cultivated != 'yes'
                      )
                      &&
                      (
                          !isset($doc->validation->duplicated)
                          //|| $doc->validation->duplicated == null
                          || $doc->validation->duplicated != 'yes'
                      )
                  ) {
                      $doc->valid="true";
                  } else {
                      $doc->valid="false";
                  }
              } else { $doc->valid = ""; }
          }
        } else {
            $doc->valid = "";
        }
      } else {
          $doc->valid = "";
      }

      if($doc->valid == 'true') {
        $d->valid++;
        $d->validated++;
      } else if($doc->valid == 'false') {
        $d->invalid++;
        $d->validated++;
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
    //sleep(2);
    $i=0;
  }

  $url = 'http://jb049/occurrences/'.$base.'/specie/'.urlencode($d->acceptedNameUsage).'?json=true';
  $sess='af2b646a99f0347f511827ceb414e3cd10c45cd6592284987976f1fb875527c4';
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

