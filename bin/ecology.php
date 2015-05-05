<?php

include 'base.php';

$fields = ['family','scientificName','lifeForm','fenology','luminosity','substratum','longevity','resprout'];
fputcsv($csv,$fields);

foreach($all->rows as $row) {
  $d = $row->doc;
  if($d->metadata->type=='profile') {
    if(isset($d->ecology) && is_object($d->ecology)) {
      foreach($d->ecology as $k=>$v) {
        if(is_array($v)) {
          $d->ecology->$k = implode(' ; ',$v);
        }
      }
      $data =[
        $d->taxon->family
        , $d->taxon->scientificNameWithoutAuthorship
        , $d->ecology->lifeForm
        , $d->ecology->fenology
        , $d->ecology->luminosity
        , $d->ecology->substratum
        , $d->ecology->longevity
        , $d->ecology->resprout
        ];
      fputcsv($csv,$data);
    }
  }
}

