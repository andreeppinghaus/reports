<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"habitat\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && isset($d->ecology->habitats) && is_array($d->ecology->habitats)) {
      foreach($d->ecology->habitats as $t) {
        echo $d->taxon->family.",".$d->taxon->scientificNameWithoutAuthorship.",";
        echo $t;
        echo "\n";
      }
    }
  }
}

