<?php

include 'base.php';

echo "\"family\",\"scientificName\",\"threat\",\"incidence\",\"timing\",\"decline\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->threats) && is_array($d->threats)) {
      foreach($d->threats as $t) {
        if(isset($t->threat)) {
          echo $d->taxon->family.",".$d->taxon->scientificNameWithoutAuthorship.",";
          echo $t->threat.",";
          echo $t->incidence.",";
          echo implode(";",$t->timing).",";
          echo implode(";",$t->decline).",";
          echo "\n";
        }
      }
    }
  }
}

