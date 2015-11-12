<?php

global $title, $description, $is_private, $fields;
$title = "Síndromes de Dispersão";
$description = "Lista com as síndromes de dispersão por espécie.";
$is_private = false;
//$fields = ['family','scientificName','dispersion'];
// Field translation
$fields = ['familia','nome aceito','síndrome de dispersão'];
include 'base.php';

fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->reproduction) && isset($d->reproduction->dispersionSyndrome) && is_array($d->reproduction->dispersionSyndrome)) {
      foreach($d->reproduction->dispersionSyndrome as $t) {
        $data = [ $d->taxon->family,$d->taxon->scientificNameWithoutAuthorship,$t];
        fputcsv($csv,$data);
      }
    }
  }
}
