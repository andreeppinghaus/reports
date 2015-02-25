<?php

include 'base.php';

echo "\"family\";\"scientificName\";\"habit\";\"fenology\";\"luminosity\";\"substratum\";\"longevity\";\"resprout\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && is_object($d->ecology)) {
      echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
      echo $d->ecology->lifeForm.";";
      echo $d->ecology->fenology.";";
      echo $d->ecology->luminosity.";";
      echo $d->ecology->substratum.";";
      echo $d->ecology->longevity.";";
      echo $d->ecology->resprout.";";
      echo "\n";
    }
  }
}

