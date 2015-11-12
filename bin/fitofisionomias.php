<?php

global $title, $description, $is_private, $fields;
$title = "Fitofisionomias";
$description = "Lista com fitofisionomias por espécie.";
$is_private = false;
//$fields=['family','scientificName','fitofisionomie'];
// Field translation
$fields=['familia','nome aceito','fitofisionomia'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->fitofisionomies) && is_array($d->ecology->fitofisionomies)) {
      foreach($d->ecology->fitofisionomies as $t) {
        $data = [ $d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,$t];
        fputcsv($csv,$data);
      }
    }
  }
}

