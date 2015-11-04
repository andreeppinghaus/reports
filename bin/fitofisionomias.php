<?php

global $title, $is_private;
$title = "Fitofisionomias";
$is_private = false;
include 'base.php';

$fields=['family','scientificName','fitofisionomie'];
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

