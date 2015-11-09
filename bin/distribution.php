<?php

global $title, $description, $is_private, $fields;
$title = "Distribuição";
$description = "Lista com endemismo e altitude por espécie.";
$is_private = false;
$fields = ['family','scientificName','endemic','altitude'];
include 'base.php';

#$fields = ['family','scientificName','endemic','altitude','eoo','aoo'];
fputcsv($csv,$fields);

$taxons = [];
$names  = [];
foreach($all->rows as $row) {
    $doc = $row->doc;
    if($doc->metadata->type=='taxon') {
      if(isset($doc->scientificNameWithoutAuthorship) && strlen($doc->scientificNameWithoutAuthorship) > 1) {
        $taxons[] = $doc;
        if($doc->taxonomicStatus == 'accepted') {
          $names[$doc->scientificNameWithoutAuthorship]=[$doc->scientificNameWithoutAuthorship];
        }
      }
    }
}

foreach($taxons as $taxon) {
  if($taxon->taxonomicStatus == 'synonym') {
    foreach($taxons as $taxon2) {
      if($taxon2->acceptedNameUsage==$taxon->acceptedNameUsage || $taxon2->scientificNameWithoutAuthorship==$taxon->acceptedNameUsage) {
        $names[$taxon2->scientificNameWithoutAuthorship][] = $taxon->scientificNameWithoutAuthorship;
      }
    }
  }
}

foreach($all->rows as $row) {
  if(!is_object($row) || !isset($row->doc)) continue;
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    $data = [$d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,"",""];

    if(isset($d->distribution) && is_object($d->distribution)) {
      $data[2] =$d->distribution->brasilianEndemic;

      if(isset($d->distribution->altitude)) {
        $a = $d->distribution->altitude;
        if(isset($a->absolute)) {
          $data[3] = $a->absolute;
        } else if(isset($a->minimum) && isset($a->maximum)) {
          $data[3] = $a->minimum."~".$a->maximum;
        } else {
          $data[3] = $a->minimum.$a->maximum;
        }
      }
    }

    /*
    $url = 'http://cncflora.jbrj.gov.br/occurrences/'.$base.'/specie/'.urlencode($d->taxon->scientificNameWithoutAuthorship).'?json=true';
    $sess='1b71063e4887a009fd6742376c6f92a52036817eb443ac8479750dac81233d42';
    shell_exec("curl '".$url."' -L -H 'Cookie: rack.session=".$sess."' > tmp_occs.json");
    $resp = file_get_contents('tmp_occs.json');
    $json = json_decode($resp);

    $data[4] = $json->stats->eoo;
    $data[5] = $json->stats->aoo;
     */
    fputcsv($csv,$data);
  }
}

