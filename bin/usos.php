<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"use\",\"resource\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->uses) && is_array($d->uses)) {
      foreach($d->uses as $t) {
        if(isset($t->use)) {
          echo $d->taxon->family.",".$d->taxon->scientificNameWithoutAuthorship.",";
          echo $t->use.",";
          echo $t->resource.",";
          echo "\n";
        }
      }
    }
  }
}

