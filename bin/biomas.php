<?php

global $title, $is_private;
$title = "Biomas";
$is_private = false;
include 'base.php';

$fields = ["family","scientificName","bioma"];
fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->biomas) && is_array($d->ecology->biomas)) {
      foreach($d->ecology->biomas as $t) {
        $data = [$d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,$t];
        fputcsv($csv,$data);
      }
    }
  }
}
