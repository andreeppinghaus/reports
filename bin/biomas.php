<?php

global $title, $description, $is_private, $fields;
$title = "Biomas";
$description = "Lista de biomas por espécie.";
$is_private = false;
//$fields = ["family","scientificName","bioma"];
// Field translation
$fields = ["familia","nome aceito","bioma"];
include 'base.php';

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
