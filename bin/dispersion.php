<?php

include 'base.php';

$fields = ['family','scientificName','dispersion'];
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
