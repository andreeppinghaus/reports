<?php

include 'base.php';

$fields = ['family','scientificName','habitat'];
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
