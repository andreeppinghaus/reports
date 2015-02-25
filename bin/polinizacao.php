<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"pollination\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->reproduction) && isset($d->reproduction->pollinationSyndrome) && is_array($d->reproduction->pollinationSyndrome)) {
      foreach($d->reproduction->pollinationSyndrome as $t) {
        echo $d->taxon->family.",".$d->taxon->scientificNameWithoutAuthorship.",";
        echo $t;
        echo "\n";
      }
    }
  }
}

