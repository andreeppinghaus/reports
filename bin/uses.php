<?php

include 'base.php';

$fields=['family','scientificName','use','resource'];
fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->uses) && is_array($d->uses)) {
      foreach($d->uses as $t) {
        if(isset($t->use)) {
          $data=[ $d->taxon->family,$d->taxon->scientificNameWithoutAuthorship, $t->use, $t->resource ];
          fputcsv($csv,$data);
        }
      }
    }
  }
}

