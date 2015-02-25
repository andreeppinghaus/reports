<?php

include 'base.php';

echo "\"family\";\"scientificName\";\"action\";\"situation\"\n";

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->actions) && is_array($d->actions)) {
      foreach($d->actions as $t) {
        if(isset($t->action)) {
          echo $d->taxon->family.";".$d->taxon->scientificNameWithoutAuthorship.";";
          echo $t->action.";";
          if(isset($t->situation)) {
            echo $t->situation.";";
          } else {
            echo ";";
          }
          echo "\n";
        }
      }
    }
  }
}

