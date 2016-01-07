<?php

global $title, $description, $is_private, $fields;
$title = "Habitats";
$description = "Lista com habitats por espécie.";
$is_private = false;
//$fields = ['family','scientificName','habitat'];
// Field translation
$fields = ['familia','nome científico','habitat'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->habitats) && is_array($d->ecology->habitats)) {
      foreach($d->ecology->habitats as $t) {
        $data=[ $d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,$t ];
        fputcsv($csv,$data);
      }
    }
  }
}

