<?php

include 'base.php';

echo "\"family\";\"scientificName\";\"bioma\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->biomas) && is_array($d->ecology->biomas)) {
      foreach($d->ecology->biomas as $t) {
        echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
        echo $t;
        echo "\n";
      }
    }
  }
}

