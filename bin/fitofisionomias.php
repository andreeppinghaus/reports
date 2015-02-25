<?php

include 'base.php';

echo "\"family\";\"scientificName\";\"fitofisionomie\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->fitofisionomies) && is_array($d->ecology->fitofisionomies)) {
      foreach($d->ecology->fitofisionomies as $t) {
        echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
        echo $t;
        echo "\n";
      }
    }
  }
}

