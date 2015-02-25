<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"dispersion\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->reproduction) && isset($d->reproduction->dispersionSyndrome) && is_array($d->reproduction->dispersionSyndrome)) {
      foreach($d->reproduction->dispersionSyndrome as $t) {
        echo $d->taxon->family.",".$d->taxon->scientificNameWithoutAuthorship.",";
        echo $t;
        echo "\n";
      }
    }
  }
}

