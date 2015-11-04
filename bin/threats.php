<?php

global $title, $is_private;
$title = "Ameaças";
$is_private = false;
include 'base.php';

$fields = ["family","scientificName","threat","incidence","timing","decline","details","references"];
fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->threats) && is_array($d->threats)) {
      foreach($d->threats as $t) {
        if(isset($t->threat)) {
          $data = [
             $d->taxon->family
            ,$d->taxon->scientificNameWithoutAuthorship
            ,$t->threat
            ,$t->incidence
            ,implode(";",$t->timing)
            ,implode(";",$t->decline)
            ,$t->details
            ,implode(";",$t->references)
          ];
          fputcsv($csv,$data);
        }
      }
    }
  }
}

