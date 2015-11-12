<?php

global $title, $description, $is_private, $fields;
$title = "Síndromes de Polinização";
$description = "Lista com as síndromes de polinização por espécie.";
$is_private = false;
$fields = ['family','scientificName','pollination'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->reproduction) && isset($d->reproduction->pollinationSyndrome) && is_array($d->reproduction->pollinationSyndrome)) {
      foreach($d->reproduction->pollinationSyndrome as $t) {
        $data=[ $d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,$t ];
        fputcsv($csv,$data);
      }
    }
  }
}

